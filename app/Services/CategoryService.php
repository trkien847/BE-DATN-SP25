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
        $category = $this->categoryRepository->create([
            'name' => $data['name'],
            'status' => 'pending',
            'created_by' => $userId
        ]);

        if (!empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $index => $subcategoryName) {
                if (!empty($subcategoryName)) {
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                        'status' => 'pending',
                        'created_by' => $userId
                    ]);
                }
            }
        }
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
        return $category;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->query()->where('status', 'approved')->with('categoryTypes')->paginate(10);
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
        $category->is_active = $isActive;
        $category->save();
        if ($isActive == 0 && $category->categoryTypes()->count() > 0) {
            $category->categoryTypes()->update(['is_active' => 0]);
        }
        return $category;
    }
    public function toggleSubcategoryActive($id, $isActive)
    {
        $subcategory = CategoryType::find($id);

        // Kiểm tra nếu danh mục cha đang tắt -> Không cho bật danh mục con
        if ($subcategory->parent && $subcategory->parent->is_active == 0) {
            return false;
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
