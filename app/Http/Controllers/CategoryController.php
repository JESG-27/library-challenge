<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(5);
        return view('categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
    }

    public function create()
    {
        return view('categories.create_edit');
    }

    public function edit(Category $category)
    {
        return view('categories.create_edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
{
    $validated = $request->validated();
    $category->update($validated);
    return redirect()->route('categories.index')->with('success', 'Categoría actualizada con éxito.');
}

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada con éxito.');
    }
}
