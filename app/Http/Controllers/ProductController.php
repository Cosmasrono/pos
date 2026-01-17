<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->paginate(15);

        return view('products.index', ['products' => $products]);
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('products.create', ['categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products|string|max:100',
            'barcode' => 'nullable|unique:products|string|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'nullable|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        // Default cost_price to 0 if not provided
        if (!isset($validated['cost_price']) || is_null($validated['cost_price'])) {
            $validated['cost_price'] = 0;
        }

        // Auto-calculate total cost: cost_price × quantity_in_stock
        $quantity = $validated['quantity_in_stock'] ?? 0;
        $validated['total_cost'] = $validated['cost_price'] * $quantity;

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function show(Product $product): View
    {
        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product): View
    {
        $categories = Category::all();
        return view('products.edit', ['product' => $product, 'categories' => $categories]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id . '|string|max:100',
            'barcode' => 'nullable|unique:products,barcode,' . $product->id . '|string|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'nullable|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Default cost_price to 0 if not provided
        if (!isset($validated['cost_price']) || is_null($validated['cost_price'])) {
            $validated['cost_price'] = 0;
        }

        // Auto-calculate total cost: cost_price × quantity_in_stock
        $quantity = $validated['quantity_in_stock'] ?? $product->quantity_in_stock;
        $validated['total_cost'] = $validated['cost_price'] * $quantity;

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->update(['is_active' => false]);

        return redirect()->route('products.index')
            ->with('success', 'Product deactivated successfully');
    }

    public function addStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
        ]);

        $product = Product::find($validated['product_id']);
        $previousStock = $product->quantity_in_stock;
        
        // Add stock
        $product->increment('quantity_in_stock', $validated['quantity']);
        
        // Optionally log the stock movement if you have a StockMovement model
        if (class_exists('App\Models\StockMovement')) {
            \App\Models\StockMovement::create([
                'product_id' => $product->id,
                'movement_type' => 'in',
                'quantity' => $validated['quantity'],
                'reference' => $validated['reference'] ?? 'Manual Stock Addition',
                'notes' => 'Added by ' . auth()->user()->name,
            ]);
        }

        return redirect()->route('products.show', $product)
            ->with('success', "Added {$validated['quantity']} units to {$product->name}");
    }
}