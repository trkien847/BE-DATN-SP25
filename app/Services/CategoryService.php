<?php

namespace App\Services;

use App\Models\CategoryType;
use App\Repositories\Interfaces\CategoryRepository;
use App\Repositories\Interfaces\CategoryTypeRepository;

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
        $category = $this->categoryRepository->create(['name' => $data['name']]);

        if (!empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $index => $subcategoryName) {
                if (!empty($subcategoryName)) {
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                    ]);
                }
            }
        }
        return $category;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->with('categoryTypes')->paginate(10);
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
        if($isActive == 0 && $category->categoryTypes()->count() > 0){
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
}
