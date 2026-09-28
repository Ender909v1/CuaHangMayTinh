<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Review;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock_qty', '<=', 5)->count();
        $history = ProductHistory::with(['user', 'product'])->latest()->take(8)->get();
        // Dashboard tabs always show the FULL lists (no truncation, no "show all" buttons).
        $products = Product::with(['brand', 'category'])->latest()->get();
        $users = User::orderBy('id')->get();
        $orders = Order::with(['user', 'items.product'])->latest('id')->get();
        $inventory = Product::with(['brand', 'category', 'histories' => $this->stockHistory()])
            ->orderBy('stock_qty')
            ->get();
        $reviews = Review::with(['user', 'product'])->latest('id')->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'activeProducts', 'lowStockProducts', 'history',
            'products', 'users', 'orders', 'inventory', 'reviews'
        ));
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
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', 'max:100'],
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['discount_price'] = $this->normalizeDiscountPrice($validated['discount_price'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');

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
        /** Stock is intentionally not updated here: it can only be changed from the Inventory tab, where a note is required. */
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'type' => ['required', 'string', 'max:100'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['discount_price'] = $this->normalizeDiscountPrice($validated['discount_price'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');

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

    public function orders()
    {
        $orders = Order::with(['user', 'items.product'])->latest('id')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        // Touch updated_at so customers get the "!" alert on their next visit.
        // +1 second: DB datetimes have 1s precision, so without this an update
        // in the same second as the customer's last view would be invisible.
        $order->status = $validated['status'];
        $order->updated_at = now()->addSecond();
        $order->save();

        return back()->with('success', "Order {$order->bill_code} marked as {$order->status}.");
    }

    public function users()
    {
        $users = User::orderBy('id')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->withErrors(['user' => 'Admin accounts cannot be edited.']);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(['customer', 'staff', 'admin'])],
            'is_active' => ['sometimes', 'boolean'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->role = $validated['role'];
        $user->is_active = $request->boolean('is_active');

        if (! empty($validated['password'])) {
            $user->password_hash = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->withErrors(['user' => 'Admin accounts cannot be deleted.']);
        }

        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function inventory()
    {
        $inventory = Product::with(['brand', 'category', 'histories' => $this->stockHistory()])
            ->orderBy('stock_qty')
            ->paginate(15);

        return view('admin.inventory.index', compact('inventory'));
    }

    /**
     * Inventory tab: change a product's stock number.
     * A note is mandatory so every stock change is traceable in the product history.
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_qty' => ['required', 'integer', 'min:0', 'max:1000000'],
            'note' => ['required', 'string', 'min:3', 'max:1000'],
        ], [
            'note.required' => 'A note is required: explain why the stock number changed.',
            'note.min' => 'The note must be at least 3 characters so the change stays traceable.',
            'stock_qty.required' => 'Enter the new stock number.',
            'stock_qty.integer' => 'The stock number must be a whole number.',
            'stock_qty.min' => 'The stock number cannot be negative.',
        ]);

        $oldStock = (int) $product->stock_qty;
        $newStock = (int) $validated['stock_qty'];

        $product->update(['stock_qty' => $newStock]);

        ProductHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'action' => 'stock_updated',
            'old_stock_qty' => $oldStock,
            'new_stock_qty' => $newStock,
            'details' => "Admin changed stock for {$product->name}: {$oldStock} to {$newStock}",
            'note' => $validated['note'],
        ]);

        return back()->with('success', "Stock for {$product->name} updated: {$oldStock} → {$newStock}.");
    }

    /**
     * Reviews tab: the admin answers a customer review. The answer can be edited later,
     * so saving again simply replaces the previous response.
     */
    public function respondToReview(Request $request, Review $review)
    {
        $validated = $request->validate([
            'admin_response' => ['required', 'string', 'min:2', 'max:2000'],
        ], [
            'admin_response.required' => 'Write a response before saving.',
            'admin_response.min' => 'The response must be at least 2 characters.',
            'admin_response.max' => 'The response cannot be longer than 2000 characters.',
        ]);

        $review->update([
            'admin_response' => $validated['admin_response'],
            'admin_responded_at' => now(),
        ]);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'reviews'])
            ->with('success', 'Response saved for the review of '.($review->product?->name ?? 'the customer').'.');
    }

    /**
     * Reviews tab: remove a customer review (and with it any admin response).
     */
    public function destroyReview(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'reviews'])
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Eager-load constraint for a product's history: newest first, with the admin who changed it.
     */
    private function stockHistory(): Closure
    {
        return fn ($query) => $query->with('user')->latest('id');
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

    private function normalizeDiscountPrice(mixed $discountPrice): ?float
    {
        if ($discountPrice === null || $discountPrice === '') {
            return null;
        }

        $normalized = (float) $discountPrice;

        return $normalized <= 0 ? null : $normalized;
    }
}
