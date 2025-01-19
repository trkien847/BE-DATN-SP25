<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(){
        $data = Reviews::query()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reviews.listReviews',compact('data'));
    }
    public function listedit(Reviews $reviews){
        return view('admin.reviews.editReviews',compact('reviews'));
    }
    // public function edit(Request $request,Reviews $reviews){
    //     $danhgia = $request->all();
    //     $reviews->update($danhgia);
    //     return redirect()->route('admin.reviews.listReviews')->with('message','sửa thành công 1 đánh giá');
    // }  
    public function destroy(Reviews $reviews){
        $reviews->delete();
        return redirect()->route('admin.reviews.listReviews')->with('message','xóa thành công 1 đánh giá');
    }
}
