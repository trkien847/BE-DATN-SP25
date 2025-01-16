<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.list', compact('categories'));
    }
    public function create(CategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());

        Alert::success('Thành công', 'Danh mục đã được tạo thành công!');
        return redirect()->route('categories.list');
    }
}
