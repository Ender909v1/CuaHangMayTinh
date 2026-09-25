<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock_qty', '<=', 5)->count();
        $history = ProductHistory::with(['user', 'product'])->latest()->take(8)->get();

        return view('admin.dashboard', compact('totalProducts', 'activeProducts', 'lowStockProducts', 'history'));
    }

    public function products()
    {
        $products = Product::with(['brand', 'category'])->latest()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'images', 'specifications']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['brand', 'category']);

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', 'max:100'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ]);

        $product->update($validated);

        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'details' => 'Admin updated product: ' . $product->name,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        ProductHistory::create([
            'product_id' => null,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'details' => 'Admin deleted product: ' . $productName,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function history()
    {
        $history = ProductHistory::with(['user', 'product'])->latest()->paginate(20);

        return view('admin.history', compact('history'));
    }
}
