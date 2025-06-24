<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Check if user is admin
     */
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        return null;
    }

    /**
     * Display a listing of categories
     */
    public function index()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            Category::create($validated);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category "' . $validated['name'] . '" created successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $books = $category->books()->latest()->paginate(10);
        return view('admin.categories.show', compact('category', 'books'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $category->update($validated);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        try {
            $bookCount = $category->books()->count();

            if ($bookCount > 0) {
                return redirect()
                    ->route('admin.categories.index')
                    ->with('error', "Cannot delete category '{$category->name}' because it has {$bookCount} book(s) assigned to it!");
            }

            $categoryName = $category->name;
            $category->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', "Category '{$categoryName}' deleted successfully!");

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
