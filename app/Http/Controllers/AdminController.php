<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', 'max:100'],
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('image')) {
            $this->storeProductImage($product, $request->file('image'));
        }

        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'details' => 'Admin created product: '.$product->name,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'images', 'specifications']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['brand', 'category']);
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', 'max:100'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $product->update($validated);

        if ($request->hasFile('image')) {
            $product->images()->delete();
            $this->storeProductImage($product, $request->file('image'));
        }

        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'details' => 'Admin updated product: '.$product->name,
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
            'details' => 'Admin deleted product: '.$productName,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function categories()
    {
        $categories = Category::with('parent')->orderByDesc('id')->paginate(12);

        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function editCategory(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function history()
    {
        $history = ProductHistory::with(['user', 'product'])->latest()->paginate(20);

        return view('admin.history', compact('history'));
    }

    private function storeProductImage(Product $product, UploadedFile $image): void
    {
        $filename = $product->sku.'-'.time().'.'.$image->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs('products', $image, $filename);

        $product->images()->create([
            'image_url' => 'storage/'.$path,
            'is_primary' => true,
        ]);
    }
}
