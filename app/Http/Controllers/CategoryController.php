<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:expense,income'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['required', 'string', 'max:50'],
        ]);

        Category::create([
            'workspace_id' => $request->user()->current_workspace_id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
        ]);

        return redirect()->back()->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        if ($category->workspace_id !== $request->user()->current_workspace_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:expense,income'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['required', 'string', 'max:50'],
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Request $request, Category $category): RedirectResponse
    {
        if ($category->workspace_id !== $request->user()->current_workspace_id) {
            abort(403);
        }

        $category->delete();

        return redirect()->back()->with('success', 'Categoria excluída com sucesso.');
    }
}
