<?php

namespace App\Services;

use App\Events\NewNotificationEvent;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\User;
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

        $user = auth()->user();
        $category = $this->categoryRepository->create([
            'name' => $data['name'],
            'status' => 'pending',
        ]);
        Log::info("Creating new category, sending notification...");
        if (!empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $index => $subcategoryName) {
                if (!empty($subcategoryName)) {
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                        'status' => 'pending',
                    ]);
                }
            }
        }
        $admins = User::where('role_id', 3)->get();
        foreach ($admins as $admin) {
            Log::info("Sending notification to admin: {$admin->id}");
            $admin->notify(new NewNotificationEvent(
                'Có danh mục mới cần duyệt',
                $user->fullname
            ));
        }
        return $category;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository
            ->with(['categoryTypes' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->where('status', 'approved')
            ->paginate(10);
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepository->with('categoryTypes')->find($id);
    }


    public function updateCategory($id, $data)
    {
        $category = $this->categoryRepository->find($id);
        $category->update(['name' => $data['name']]);

        $existingSubcategories = $category->categoryTypes->keyBy('id');

        if (isset($data['subcategories'])) {
            foreach ($data['subcategories'] as $index => $subcategoryName) {
                $subcategoryId = $data['subcategory_ids'][$index] ?? null;

                if ($subcategoryId && $existingSubcategories->has($subcategoryId)) {
                    $this->categoryTypeRepository->update([
                        'name' => $subcategoryName,
                    ], $subcategoryId);

                    $existingSubcategories->forget($subcategoryId);
                } else {
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                    ]);
                }
            }
        }
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
    public function acceptCategory($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        if ($category->status == 'pending') {
            $category->status = 'approved';
            $category->save();

            // Cập nhật tất cả danh mục con của danh mục này
            $category->categoryTypes()->update(['status' => 'approved']);

            return response()->json(['message' => 'Category and its subcategories approved successfully']);
        }

        return response()->json(['message' => 'Category is not pending'], 400);
    }

    public function rejectCategory($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        if ($category->status == 'pending') {
            $category->status = 'rejected';
            $category->save();

            // Cập nhật tất cả danh mục con của danh mục này
            $category->categoryTypes()->update(['status' => 'rejected']);

            return response()->json(['message' => 'Category and its subcategories rejected successfully']);
        }

        return response()->json(['message' => 'Category is not pending'], 400);
    }

    public function getPendingCategories()
    {
        $pendingCategories = $this->categoryRepository
            ->with(['categoryTypes' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->where('status', 'pending')
            ->paginate(10);

        return $pendingCategories;
    }
}
