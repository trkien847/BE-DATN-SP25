<?php

namespace App\Services;

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
                        'icon' => $data['subcategory_icons'][$index] ?? null
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
                        'icon' => $data['subcategory_icons'][$index]
                    ], $subcategoryId);

                    $existingSubcategories->forget($subcategoryId);
                } else {
                    $this->categoryTypeRepository->create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                        'icon' => $data['subcategory_icons'][$index]
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
}
