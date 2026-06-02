<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        // Papka manzili to'g'rilandi: resources/views/categories/index.blade.php
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        // Status checkbox bo'lgani uchun kelgan/kelmaganini tekshiramiz
        $fields['status'] = $request->has('status');

        Category::create($fields);

        return redirect()->route('categories.index')->with('success', 'Kategoriya qo‘shildi!');
    }

    public function update(Request $request, Category $category)
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $fields['status'] = $request->has('status');

        $category->update($fields);

        return redirect()->route('categories.index')->with('success', 'Kategoriya yangilandi!');
    }

    public function destroy(Category $category)
    {
        if ($category->phones()->count() > 0) {
            return back()->with('error', 'Bu kategoriyaga tegishli telefonlar bor!');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategoriya o‘chirildi!');
    }
}
