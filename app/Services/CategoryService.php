<?php

namespace App\Services;

use App\Models\CategoryType;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\CategoryApprovalResponse;
use App\Repositories\Interfaces\CategoryRepository;
use App\Repositories\Interfaces\CategoryTypeRepository;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    protected $categoryRepository;
    protected $categoryTypeRepository;

    public function __construct(CategoryRepository $categoryRepository, CategoryTypeRepository $categoryType)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryTypeRepository = $categoryType;
    }
    public function createCategory($data)
    {
        $userId = auth()->user()->id;
        $isAdmin = auth()->user()->role_id === 3;

        $category = $this->categoryRepository->create([
            'name' => $data['name'],
            'status' => $isAdmin ? 'approved' : 'pending',
            'created_by' => $userId,
            'approved_by' => $isAdmin ? $userId : null,
            'approved_at' => $isAdmin ? now() : null
        ]);

        if (!empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $index => $subcategoryName) {
                if (!empty($subcategoryName)) {
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                        'status' => $isAdmin ? 'approved' : 'pending',
                        'created_by' => $userId,
                        'approved_by' => $isAdmin ? $userId : null,
                        'approved_at' => $isAdmin ? now() : null
                    ]);
                }
            }
        }

        if (!$isAdmin) {
            $admins = User::where('role_id', 3)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Yêu cầu tạo danh mục mới',
                    'content' => "Người dùng yêu cầu tạo danh mục: {$data['name']}",
                    'type' => 'category_pending_create',
                    'data' => [
                        'category_id' => $category->id,
                        'requester_id' => $userId,
                        'actions' => [
                            'view_details' => route('categories.pending'),
                            'approve_request' => route('category.accept', $category->id),
                            'reject_request' => route('category.reject', $category->id)
                        ]
                    ],
                    'is_read' => false
                ]);
            }
        }

        return $category;
    }

    public function getAllCategories($status = 'approved')
    {
        return $this->categoryRepository->query()
            ->where('status', $status)
            ->with('categoryTypes')
            ->paginate(10);
    }
    public function getAllCategoriesForClient()
    {
        return $this->categoryRepository->query()
            ->where('is_active', 1)
            ->where('status', 'approved')
            ->with('categoryTypes')
            ->get();
    }

    public function searchCategories($keyword, $status = 'approved')
    {
        if (empty($keyword)) {
            return $this->getAllCategories();
        }
        return $this->categoryRepository->searchCategories($keyword, $status);
    }
    public function getCategoryById($id)
    {
        return $this->categoryRepository->with('categoryTypes')->find($id);
    }


    public function updateCategory($id, $data, $userId)
    {
        $category = $this->categoryRepository->find($id);

        $category->update([
            'name' => $data['name'],
            'updated_by' => $userId
        ]);

        $existingSubcategories = $category->categoryTypes->keyBy('id');

        if (isset($data['subcategories'])) {
            foreach ($data['subcategories'] as $index => $subcategoryName) {
                $subcategoryId = $data['subcategory_ids'][$index] ?? null;

                if ($subcategoryId && $existingSubcategories->has($subcategoryId)) {
                    // Update existing subcategory
                    $this->categoryTypeRepository->update([
                        'name' => $subcategoryName,
                        'updated_by' => $userId
                    ], $subcategoryId);

                    $existingSubcategories->forget($subcategoryId);
                } else {
                    // Create new subcategory
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                        'status' => 'approved', // Set as approved directly
                        'created_by' => $userId
                    ]);
                }
            }
        }

        // Delete removed subcategories
        foreach ($existingSubcategories as $subcategory) {
            $subcategory->delete();
        }

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->categoryRepository->find($id);
        $category->categoryTypes()->delete();
        return $category->delete();
    }
    public function toggleActive($id, $isActive)
    {
        $category = $this->categoryRepository->find($id);

        // Check if category has any products
        if ($isActive == 0 && $category->products()->count() > 0) {
            throw new \Exception('Không thể vô hiệu hóa danh mục này vì đang có sản phẩm thuộc danh mục.');
        }

        $category->is_active = $isActive;
        $category->save();

        // If deactivating parent category, deactivate all subcategories
        if ($isActive == 0 && $category->categoryTypes()->count() > 0) {
            $category->categoryTypes()->update(['is_active' => 0]);
        }

        return $category;
    }
    public function toggleSubcategoryActive($id, $isActive)
    {
        $subcategory = CategoryType::find($id);

        // Check if parent category is inactive
        if ($subcategory->category && $subcategory->category->is_active == 0) {
            throw new \Exception('Không thể kích hoạt danh mục con khi danh mục cha đang bị vô hiệu hóa.');
        }

        // Check if subcategory has any products
        if ($isActive == 0 && $subcategory->products()->count() > 0) {
            throw new \Exception('Không thể vô hiệu hóa danh mục này vì đang có sản phẩm thuộc danh mục.');
        }

        $subcategory->is_active = $isActive;
        $subcategory->save();

        return $subcategory;
    }
    public function approveCategory($id, $adminId)
    {
        $category = $this->categoryRepository->find($id);
        $category->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now()
        ]);

        // Tự động phê duyệt các danh mục con
        $category->categoryTypes()->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now()
        ]);

        return $category;
    }

    public function rejectCategory($id, $adminId)
    {
        $category = $this->categoryRepository->find($id);
        $category->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
        ]);

        // Từ chối tất cả danh mục con
        $category->categoryTypes()->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
        ]);

        return $category;
    }
    public function getPendingCategories()
    {
        return $this->categoryRepository->query()->where('status', 'pending')->with('categoryTypes')->paginate(10);
    }
}
