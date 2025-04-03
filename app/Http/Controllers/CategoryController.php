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

    public function create()
    {
        return view('admin.categories.add');
    }
    public function store(CategoryRequest $request)
    {
        $this->categoryService->createCategory($request->all());
        Alert::success('Thành công', 'Danh mục đã được tạo thành công!');
        return redirect()->route('categories.list')->with('success', 'Danh mục đã được tạo thành công!');
    }
    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        return view('admin.categories.edit', compact('category'));
    }
    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->all());
            return redirect()->route('categories.list')->with('success', 'Category and subcategories updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return redirect()->route('categories.list')->with('success', 'Category and subcategories deleted successfully');
    }
    public function toggleActive(Request $request, $id)
    {
        $category = $this->categoryService->toggleActive($id, $request->is_active);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'is_parent' => $category->categoryTypes->count() > 0
        ]);
    }
    public function toggleSubcategoryActive(Request $request, $id)
    {
        $subcategory = $this->categoryService->toggleSubcategoryActive($id, $request->is_active);

        return response()->json([
            'success' => $subcategory !== false,
            'is_active' => $subcategory ? $subcategory->is_active : 0
        ]);
    }
    public function getPendingCategories()
    {
        $categories = $this->categoryService->getPendingCategories();

        return view('admin.categories.pendingCategories', compact('categories'));
    }
    public function acceptCategory($id)
    {
        try {
            $this->categoryService->acceptCategory($id);
            return response()->json([
                'success' => true,
                'message' => 'Danh mục đã được duyệt thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectCategory($id)
    {
        try {
            $this->categoryService->rejectCategory($id);
            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối danh mục'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
