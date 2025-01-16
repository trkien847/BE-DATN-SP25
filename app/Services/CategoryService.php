<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function createCategory($data)
    {
        return $this->categoryRepository->create($data);
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }
}
