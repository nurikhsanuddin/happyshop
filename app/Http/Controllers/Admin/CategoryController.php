<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }
    public function store(Request $request)
    {
        Category::create($request->all());
        toast('Data kategori berhasil ditambah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $category->update($request->all());
        toast('Data kategori berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function delete(Request $request)
    {
        $category = Category::find($request->id);
        $category->delete();
        toast('Data kategori berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
}
