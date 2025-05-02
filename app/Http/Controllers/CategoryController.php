<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\CategoryRequest;
use App\Models\Notification;
use App\Services\CategoryService;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->middleware('admin')->only([
            'acceptCategory',
            'rejectCategory',
            'getPendingCategories'
        ]);
    }

    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $categories = $this->categoryService->searchCategories($keyword);

        if ($request->ajax()) {
            return view('admin.categories.category-list-partial', compact('categories'))->render();
        }

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
            $userId = auth()->user()->id;
            $this->categoryService->updateCategory($id, $request->all(), $userId);
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
        try {
            $category = $this->categoryService->toggleActive($id, $request->is_active);
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái thành công',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function toggleSubcategoryActive(Request $request, $id)
    {
        try {
            $subcategory = $this->categoryService->toggleSubcategoryActive($id, $request->is_active);
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái thành công',
                'data' => $subcategory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function getPendingCategories()
    {
        $categories = $this->categoryService->getPendingCategories();

        return view('admin.categories.pendingCategories', compact('categories'));
    }
    public function acceptCategory($id)
    {
        try {
            $adminId = auth()->id();
            $notification = Notification::find(request('notification_id'));
            $this->categoryService->approveCategory($id, $adminId);
            if ($notification) {
                $notification->update(['is_read' => true]);
            }
            if (response()->json()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Danh mục đã được duyệt thành công'
                ]);
            }
            return redirect()->back()->with('success', 'Danh mục đã được duyệt thành công');
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
            $adminId = auth()->id();
            $notification = Notification::find(request('notification_id'));
            $this->categoryService->rejectCategory($id, $adminId);
            if ($notification) {
                $notification->update(['is_read' => true]);
            }
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
