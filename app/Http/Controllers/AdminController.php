<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Review;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock_qty', '<=', 5)->count();
        $history = ProductHistory::with(['user', 'product'])->latest()->take(8)->get();
        $productFilters = $this->productFilters($request, 'p_');
        $products = $this->filteredProductsQuery($productFilters)
            ->with(['brand', 'category'])
            ->latest('id')
            ->paginate(20)
            ->appends(array_merge(
                ['tab' => 'products'],
                $this->prefixedQuery($productFilters, 'p_')
            ));
        $productFilterOptions = $this->productFilterOptions();
        $productsByMonth = Product::query()
            ->where('created_at', '>=', now()->startOfMonth()->subMonths(7))
            ->get(['created_at'])
            ->groupBy(
                fn (Product $product): string => $product->created_at?->format('Y-m') ?? ''
            );
        $monthlyProductStats = collect(range(7, 0))->map(function (int $monthsAgo) use ($productsByMonth): array {
            $month = now()->startOfMonth()->subMonths($monthsAgo);

            return [
                'label' => strtoupper($month->format('M')),
                'count' => $productsByMonth->get($month->format('Y-m'), collect())->count(),
            ];
        })->all();
        $maxMonthlyProductCount = max(array_column($monthlyProductStats, 'count'));
        $stockTotal = $totalProducts;
        $stockChartCircumference = 276.46;
        $stockChartOffset = 0.0;
        $stockStats = collect([
            ['label' => 'In stock', 'count' => Product::where('stock_qty', '>', 5)->count(), 'color' => '#16a34a'],
            ['label' => 'Low stock', 'count' => Product::where('stock_qty', '>', 0)->where('stock_qty', '<=', 5)->count(), 'color' => '#f59e0b'],
            ['label' => 'Out of stock', 'count' => Product::where('stock_qty', 0)->count(), 'color' => '#ef4444'],
        ])->map(function (array $stat) use ($stockTotal, $stockChartCircumference, &$stockChartOffset): array {
            $share = $stockTotal > 0 ? $stat['count'] / $stockTotal : 0;
            $stat['percent'] = (int) round($share * 100);
            $stat['dashLength'] = round($share * $stockChartCircumference, 2);
            $stat['offset'] = round($stockChartOffset, 2);
            $stockChartOffset += $stat['dashLength'];

            return $stat;
        })->all();
        $userFilters = $this->userFilters($request, 'u_');
        $users = $this->filteredUsersQuery($userFilters)->orderBy('id')->get();
        $orderFilters = $this->orderFilters($request);
        $orders = $this->filteredOrdersQuery($orderFilters)->latest('id')->get();
        $categoryFilters = $this->categoryFilters($request, 'c_');
        $categories = $this->filteredCategoriesQuery($categoryFilters)->with('parent')->orderByDesc('id')->get();
        $categoryParents = Category::orderBy('name')->get(['id', 'name']);
        $inventoryFilters = $this->inventoryFilters($request, 'i_');
        $inventory = $this->filteredInventoryQuery($inventoryFilters)
            ->with(['brand', 'category', 'histories' => $this->stockHistory()])
            ->orderBy('stock_qty')
            ->get();
        $reviewFilters = $this->reviewFilters($request, 'r_');
        $reviews = $this->filteredReviewsQuery($reviewFilters)->with(['user', 'product'])->latest('id')->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'activeProducts', 'lowStockProducts', 'history',
            'products', 'monthlyProductStats', 'maxMonthlyProductCount', 'stockTotal',
            'stockChartCircumference', 'stockStats', 'users', 'orders', 'inventory', 'reviews',
            'categories',
            'orderFilters', 'productFilters', 'productFilterOptions', 'userFilters', 'categoryFilters', 'categoryParents', 'inventoryFilters', 'reviewFilters'
        ));
    }

    public function products(Request $request)
    {
        $productFilters = $this->productFilters($request);
        $products = $this->filteredProductsQuery($productFilters)
            ->with(['brand', 'category'])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();
        $productFilterOptions = $this->productFilterOptions();

        return view('admin.products.index', compact('products', 'productFilters', 'productFilterOptions'));
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

        $productChanges = $this->changesFromInitial($product->only([
            'name', 'sku', 'description', 'price', 'discount_price', 'stock_qty',
            'type', 'brand_id', 'category_id', 'is_active',
        ]));
        if ($request->hasFile('image')) {
            $productChanges['image'] = ['from' => null, 'to' => $product->images()->value('image_url')];
        }
        $this->recordAdminChange('created', 'product', $product->id, $product->name, $productChanges, $product->id);

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

        $trackedFields = [
            'name', 'sku', 'description', 'price', 'discount_price',
            'type', 'brand_id', 'category_id', 'is_active',
        ];
        $before = $product->only($trackedFields);
        $previousImages = $product->images()->pluck('image_url')->all();

        $product->update($validated);

        if ($request->hasFile('image')) {
            $product->images()->delete();
            $this->storeProductImage($product, $request->file('image'));
        }

        $productChanges = $this->changesBetween($before, $product->only($trackedFields));
        if ($request->hasFile('image')) {
            $productChanges['image'] = [
                'from' => implode(', ', $previousImages) ?: null,
                'to' => $product->images()->pluck('image_url')->implode(', '),
            ];
        }
        $this->recordAdminChange('updated', 'product', $product->id, $product->name, $productChanges, $product->id);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        $productId = $product->id;
        $product->delete();

        $this->recordAdminChange('deleted', 'product', $productId, $productName, [
            'record' => ['from' => $productName, 'to' => 'deleted'],
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function categories(Request $request)
    {
        $categoryFilters = $this->categoryFilters($request);
        $categories = $this->filteredCategoriesQuery($categoryFilters)
            ->with('parent')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'categoryFilters'));
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

        $category = Category::create($validated);
        $this->recordAdminChange(
            'created',
            'category',
            $category->id,
            $category->name,
            $this->changesFromInitial($category->only(['name', 'parent_id']))
        );

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

        $before = $category->only(['name', 'parent_id']);
        $category->update($validated);
        $this->recordAdminChange(
            'updated',
            'category',
            $category->id,
            $category->name,
            $this->changesBetween($before, $category->only(['name', 'parent_id']))
        );

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category)
    {
        $categoryName = $category->name;
        $categoryId = $category->id;
        $category->delete();
        $this->recordAdminChange('deleted', 'category', $categoryId, $categoryName, [
            'record' => ['from' => $categoryName, 'to' => 'deleted'],
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function history()
    {
        $history = ProductHistory::with(['user', 'product'])->latest()->paginate(20);

        return view('admin.history', compact('history'));
    }

    public function orders(Request $request)
    {
        $orderFilters = $this->orderFilters($request);
        $orders = $this->filteredOrdersQuery($orderFilters)
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'orderFilters'));
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
        $before = ['status' => $order->status];

        // Touch updated_at so customers get the "!" alert on their next visit.
        // +1 second: DB datetimes have 1s precision, so without this an update
        // in the same second as the customer's last view would be invisible.
        $order->status = $validated['status'];
        $order->updated_at = now()->addSecond();
        $order->save();
        $this->recordAdminChange(
            'status_updated',
            'order',
            $order->id,
            $order->bill_code,
            $this->changesBetween($before, ['status' => $order->status])
        );

        return back()->with('success', "Order {$order->bill_code} marked as {$order->status}.");
    }

    /**
     * Bank/QR rescue: a transfer scan can stall mid-flight (app closed, network
     * drop) leaving payment_status stuck on pending/failed. The admin verifies
     * the money in the bank app, then fixes the bill here: payment method,
     * payment status, and the bank transaction reference.
     */
    public function updateOrderPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(Order::PAYMENT_METHODS)],
            'payment_status' => ['required', Rule::in(Order::PAYMENT_STATUSES)],
            'transaction_id' => ['nullable', 'string', 'max:255'],
        ]);
        $before = $order->only(['payment_method', 'payment_status', 'transaction_id']);

        $order->payment_method = $validated['payment_method'];
        $order->payment_status = $validated['payment_status'];
        $order->transaction_id = $validated['transaction_id'] ?: null;
        $order->updated_at = now()->addSecond();
        $order->save();
        $this->recordAdminChange(
            'payment_updated',
            'order',
            $order->id,
            $order->bill_code,
            $this->changesBetween($before, $order->only(['payment_method', 'payment_status', 'transaction_id']))
        );

        return back()->with(
            'success',
            "Payment for {$order->bill_code} updated: {$order->payment_method} / {$order->payment_status}."
        );
    }

    public function cancelOrder(Order $order)
    {
        $cancelled = DB::transaction(function () use ($order): bool {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            if (! $lockedOrder->canBeCancelledByCustomer()) {
                return false;
            }

            $lockedOrder->load('items.product');
            $previousStatus = $lockedOrder->status;
            $previousPaymentStatus = $lockedOrder->payment_status;

            foreach ($lockedOrder->items as $item) {
                $product = $item->product;
                if ($product === null) {
                    continue;
                }

                $oldStock = (int) $product->stock_qty;
                $product->increment('stock_qty', $item->quantity);
                ProductHistory::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'action' => 'order_cancelled',
                    'old_stock_qty' => $oldStock,
                    'new_stock_qty' => $oldStock + $item->quantity,
                    'details' => "Admin restocked {$product->name} after cancelling order {$lockedOrder->bill_code}",
                ]);
            }

            $lockedOrder->status = 'cancelled';
            if ($lockedOrder->payment_status === 'pending') {
                $lockedOrder->payment_status = 'failed';
            }
            $lockedOrder->updated_at = now()->addSecond();
            $lockedOrder->save();

            $this->recordAdminChange(
                'cancelled',
                'order',
                $lockedOrder->id,
                $lockedOrder->bill_code,
                $this->changesBetween(
                    ['status' => $previousStatus, 'payment_status' => $previousPaymentStatus],
                    ['status' => $lockedOrder->status, 'payment_status' => $lockedOrder->payment_status]
                )
            );

            return true;
        });

        if (! $cancelled) {
            return back()->withErrors([
                'order' => 'Only pending, unpaid orders can be cancelled and restocked.',
            ]);
        }

        return back()->with('success', "Order {$order->bill_code} cancelled and items returned to stock.");
    }

    public function removeOrderItem(Order $order, OrderItem $item)
    {
        if ($item->order_id !== $order->id) {
            abort(404);
        }

        $removed = DB::transaction(function () use ($order, $item): bool {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            if (! $lockedOrder->canRemoveItems()) {
                return false;
            }

            $lockedItem = $lockedOrder->items()->with('product')->lockForUpdate()->findOrFail($item->id);
            $product = $lockedItem->product;
            $previousTotal = (float) $lockedOrder->total_amount;
            $previousStatus = $lockedOrder->status;
            $previousPaymentStatus = $lockedOrder->payment_status;

            if ($product !== null) {
                $oldStock = (int) $product->stock_qty;
                $product->increment('stock_qty', $lockedItem->quantity);
                ProductHistory::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'action' => 'order_item_removed',
                    'old_stock_qty' => $oldStock,
                    'new_stock_qty' => $oldStock + $lockedItem->quantity,
                    'details' => "Admin restocked {$product->name} after removing it from order {$lockedOrder->bill_code}",
                ]);
            }

            $itemDescription = ($product?->name ?? 'Product #'.$lockedItem->product_id)
                .' × '.$lockedItem->quantity;
            $lockedItem->delete();

            $lockedOrder->load('items');
            $lockedOrder->total_amount = $lockedOrder->items->sum(
                fn (OrderItem $line): float => (float) $line->unit_price * $line->quantity
            );
            if ($lockedOrder->items->isEmpty()) {
                $lockedOrder->status = 'cancelled';
                if ($lockedOrder->payment_status === 'pending') {
                    $lockedOrder->payment_status = 'failed';
                }
            }
            $lockedOrder->updated_at = now()->addSecond();
            $lockedOrder->save();

            $changes = $this->changesBetween(
                [
                    'total_amount' => $previousTotal,
                    'status' => $previousStatus,
                    'payment_status' => $previousPaymentStatus,
                ],
                [
                    'total_amount' => (float) $lockedOrder->total_amount,
                    'status' => $lockedOrder->status,
                    'payment_status' => $lockedOrder->payment_status,
                ]
            );
            $changes['item'] = ['from' => $itemDescription, 'to' => 'removed'];
            $this->recordAdminChange(
                'item_removed',
                'order',
                $lockedOrder->id,
                $lockedOrder->bill_code,
                $changes
            );

            return true;
        });

        if (! $removed) {
            return back()->withErrors([
                'order' => 'Items can only be removed from pending, unpaid orders.',
            ]);
        }

        return back()->with('success', "Item removed from order {$order->bill_code} and returned to stock.");
    }

    public function users(Request $request)
    {
        $userFilters = $this->userFilters($request);
        $users = $this->filteredUsersQuery($userFilters)
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'userFilters'));
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

        $before = $user->only(['full_name', 'email', 'phone', 'address', 'role', 'is_active']);
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
        $userChanges = $this->changesBetween($before, $user->only([
            'full_name', 'email', 'phone', 'address', 'role', 'is_active',
        ]));
        if (! empty($validated['password'])) {
            $userChanges['password'] = ['from' => 'unchanged', 'to' => 'changed'];
        }
        $this->recordAdminChange('updated', 'user', $user->id, $user->full_name, $userChanges);

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

        $userName = $user->full_name;
        $userId = $user->id;
        $user->delete();
        $this->recordAdminChange('deleted', 'user', $userId, $userName, [
            'record' => ['from' => $userName, 'to' => 'deleted'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function inventory(Request $request)
    {
        $inventoryFilters = $this->inventoryFilters($request);
        $inventory = $this->filteredInventoryQuery($inventoryFilters)
            ->with(['brand', 'category', 'histories' => $this->stockHistory()])
            ->orderBy('stock_qty')
            ->paginate(15)
            ->withQueryString();

        return view('admin.inventory.index', compact('inventory', 'inventoryFilters'));
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

        $previousResponse = $review->admin_response;
        $review->update([
            'admin_response' => $validated['admin_response'],
            'admin_responded_at' => now(),
        ]);
        $this->recordAdminChange(
            'responded_to',
            'review',
            $review->id,
            $review->product?->name,
            $this->changesBetween(
                ['admin_response' => $previousResponse],
                ['admin_response' => $review->admin_response]
            )
        );

        return redirect()
            ->route('admin.dashboard', ['tab' => 'reviews'])
            ->with('success', 'Response saved for the review of '.($review->product?->name ?? 'the customer').'.');
    }

    /**
     * Reviews tab: remove a customer review (and with it any admin response).
     */
    public function destroyReview(Review $review)
    {
        $reviewId = $review->id;
        $productName = $review->product?->name;
        $review->delete();
        $this->recordAdminChange('deleted', 'review', $reviewId, $productName, [
            'record' => ['from' => 'review #'.$reviewId, 'to' => 'deleted'],
        ]);

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

    /**
     * @param  array<string, array{from: mixed, to: mixed}>  $changes
     */
    private function recordAdminChange(
        string $action,
        string $entity,
        int $entityId,
        ?string $entityName,
        array $changes,
        ?int $productId = null
    ): void {
        if ($changes === []) {
            return;
        }

        $changeDetails = collect($changes)
            ->map(fn (array $change, string $field): string => $field.': '
                .$this->formatHistoryValue($change['from']).' → '
                .$this->formatHistoryValue($change['to']))
            ->implode('; ');
        $entityLabel = $entity.' #'.$entityId.($entityName !== null ? ' ('.$entityName.')' : '');

        ProductHistory::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => 'Admin '.$action.' '.$entityLabel.': '.$changeDetails,
        ]);
    }

    /**
     * @param  array<string, mixed>  $after
     * @return array<string, array{from: mixed, to: mixed}>
     */
    private function changesFromInitial(array $after): array
    {
        return collect($after)
            ->map(fn (mixed $value): array => ['from' => null, 'to' => $value])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return array<string, array{from: mixed, to: mixed}>
     */
    private function changesBetween(array $before, array $after): array
    {
        $changes = [];

        foreach ($after as $field => $newValue) {
            $oldValue = $before[$field] ?? null;

            if ($oldValue != $newValue) {
                $changes[$field] = ['from' => $oldValue, 'to' => $newValue];
            }
        }

        return $changes;
    }

    private function formatHistoryValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return 'empty';
        }

        if (is_bool($value)) {
            return $value ? 'yes' : 'no';
        }

        if (is_array($value)) {
            return implode(', ', array_map($this->formatHistoryValue(...), $value));
        }

        return (string) $value;
    }

    /**
     * Shared Orders Management filters (search bar + status droplist + order-date range).
     * Read raw query values (no validate()->redirect) so dashboard GET filtering never 302-loops.
     *
     * @return array{search: string, status: string, date_from: string, date_to: string}
     */
    private function orderFilters(Request $request): array
    {
        $status = (string) $request->query('status', '');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo = (string) $request->query('date_to', '');

        return [
            'search' => trim((string) $request->query('search', '')),
            'status' => in_array($status, Order::STATUSES, true) ? $status : '',
            'date_from' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom) === 1 ? $dateFrom : '',
            'date_to' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo) === 1 ? $dateTo : '',
        ];
    }

    /**
     * Base orders query with the shared search/status/date filters applied.
     * Search matches bill code, customer name/email, or shipping address.
     * Dates filter on the order_date column (order creation date).
     */
    private function filteredOrdersQuery(array $filters): Builder
    {
        $query = Order::with(['user', 'items.product']);

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function (Builder $query) use ($search): void {
                $query->where('bill_code', 'like', "%{$search}%")
                    ->orWhere('shipping_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $query) use ($search): void {
                        $query->where('full_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('order_date', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('order_date', '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * Shared Users Management filters (search bar + role + active droplists).
     * Prefix keeps dashboard tab params (u_search/u_role/...) from clashing
     * with orders/inventory/review params on the same /admin?tab=... URL.
     *
     * @return array{search: string, role: string, active: string}
     */
    private function userFilters(Request $request, string $prefix = ''): array
    {
        $role = (string) $request->query($prefix.'role', '');
        $active = (string) $request->query($prefix.'active', '');

        return [
            'search' => trim((string) $request->query($prefix.'search', '')),
            'role' => in_array($role, ['customer', 'staff', 'admin'], true) ? $role : '',
            'active' => in_array($active, ['yes', 'no'], true) ? $active : '',
        ];
    }

    /**
     * Base users query with the shared search/role/active filters applied.
     * Search matches name, email, or phone.
     */
    private function filteredUsersQuery(array $filters): Builder
    {
        $query = User::query();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function (Builder $query) use ($search): void {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($filters['role'] !== '') {
            $query->where('role', $filters['role']);
        }

        if ($filters['active'] !== '') {
            $query->where('is_active', $filters['active'] === 'yes');
        }

        return $query;
    }

    /**
     * Shared Categories Management filters (search bar + parent droplist).
     * Prefix keeps dashboard tab params (c_search/c_parent/...) from clashing
     * with orders/users/inventory/review params on the same /admin?tab=... URL.
     *
     * @return array{search: string, parent: string}
     */
    private function categoryFilters(Request $request, string $prefix = ''): array
    {
        $parent = (string) $request->query($prefix.'parent', '');

        if ($parent !== '' && $parent !== 'main' && ! ctype_digit($parent)) {
            $parent = '';
        }

        return [
            'search' => trim((string) $request->query($prefix.'search', '')),
            'parent' => $parent,
        ];
    }

    /**
     * Base categories query with the shared search/parent filters applied.
     * Search matches category name; parent filters by Main vs a parent id.
     */
    private function filteredCategoriesQuery(array $filters): Builder
    {
        $query = Category::query();

        if ($filters['search'] !== '') {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if ($filters['parent'] !== '') {
            if ($filters['parent'] === 'main') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', (int) $filters['parent']);
            }
        }

        return $query;
    }

    /**
     * Shared Inventory Management filters (search bar + stock droplist).
     * Prefix keeps dashboard tab params (i_search/i_stock/...) from clashing
     * with orders/users/category/review params on the same /admin?tab=... URL.
     *
     * @return array{search: string, stock: string}
     */
    private function inventoryFilters(Request $request, string $prefix = ''): array
    {
        $stock = (string) $request->query($prefix.'stock', '');

        return [
            'search' => trim((string) $request->query($prefix.'search', '')),
            'stock' => in_array($stock, ['in', 'low', 'out'], true) ? $stock : '',
        ];
    }

    /**
     * Base inventory query with the shared search/stock filters applied.
     * Search matches product name or SKU.
     */
    private function filteredInventoryQuery(array $filters): Builder
    {
        $query = Product::query();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($filters['stock'] === 'in') {
            $query->where('stock_qty', '>', 5);
        } elseif ($filters['stock'] === 'low') {
            $query->where('stock_qty', '>', 0)->where('stock_qty', '<=', 5);
        } elseif ($filters['stock'] === 'out') {
            $query->where('stock_qty', '<=', 0);
        }

        return $query;
    }

    /**
     * Shared Reviews Management filters (search bar + rating + answered droplists).
     * Prefix keeps dashboard tab params (r_search/r_rating/...) from clashing
     * with orders/users/category/inventory params on the same /admin?tab=... URL.
     *
     * @return array{search: string, rating: string, answered: string}
     */
    private function reviewFilters(Request $request, string $prefix = ''): array
    {
        $rating = (string) $request->query($prefix.'rating', '');
        $answered = (string) $request->query($prefix.'answered', '');

        return [
            'search' => trim((string) $request->query($prefix.'search', '')),
            'rating' => in_array($rating, ['1', '2', '3', '4', '5'], true) ? $rating : '',
            'answered' => in_array($answered, ['yes', 'no'], true) ? $answered : '',
        ];
    }

    /**
     * Base reviews query with the shared search/rating/answered filters applied.
     * Search matches comment, customer name, or product name.
     */
    private function filteredReviewsQuery(array $filters): Builder
    {
        $query = Review::query();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function (Builder $query) use ($search): void {
                $query->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $query) use ($search): void {
                        $query->where('full_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function (Builder $query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($filters['rating'] !== '') {
            $query->where('rating', (int) $filters['rating']);
        }

        if ($filters['answered'] === 'yes') {
            $query->whereNotNull('admin_response')->where('admin_response', '!=', '');
        } elseif ($filters['answered'] === 'no') {
            $query->where(function (Builder $query): void {
                $query->whereNull('admin_response')->orWhere('admin_response', '');
            });
        }

        return $query;
    }

    /**
     * Shared Products Management filters (search bar + category/brand/status/stock droplists).
     * Prefix keeps dashboard tab params (p_search/p_category/...) from clashing
     * with orders/users/category/inventory/review params on the same /admin?tab=... URL.
     *
     * @return array{search: string, category: string, brand: string, status: string, stock: string}
     */
    private function productFilters(Request $request, string $prefix = ''): array
    {
        $category = (string) $request->query($prefix.'category', '');
        $brand = (string) $request->query($prefix.'brand', '');
        $status = (string) $request->query($prefix.'status', '');
        $stock = (string) $request->query($prefix.'stock', '');

        return [
            'search' => trim((string) $request->query($prefix.'search', '')),
            'category' => ctype_digit($category) ? $category : '',
            'brand' => ctype_digit($brand) ? $brand : '',
            'status' => in_array($status, ['active', 'inactive'], true) ? $status : '',
            'stock' => in_array($stock, ['in', 'low', 'out'], true) ? $stock : '',
        ];
    }

    /**
     * Base products query with the shared search/category/brand/status/stock
     * filters applied. Search matches product name or SKU.
     */
    private function filteredProductsQuery(array $filters): Builder
    {
        $query = Product::query();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($filters['category'] !== '') {
            $query->where('category_id', (int) $filters['category']);
        }

        if ($filters['brand'] !== '') {
            $query->where('brand_id', (int) $filters['brand']);
        }

        if ($filters['status'] === 'active') {
            $query->where('is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $query->where('is_active', false);
        }

        if ($filters['stock'] === 'in') {
            $query->where('stock_qty', '>', 5);
        } elseif ($filters['stock'] === 'low') {
            $query->where('stock_qty', '>', 0)->where('stock_qty', '<=', 5);
        } elseif ($filters['stock'] === 'out') {
            $query->where('stock_qty', '<=', 0);
        }

        return $query;
    }

    /**
     * Dropdown options for the products filter bars (shared by dashboard tab
     * and full products page so both stay in sync).
     *
     * @return array{categories: Collection, brands: Collection}
     */
    private function productFilterOptions(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
        ];
    }

    /**
     * Re-prefix normalized filter values for dashboard pagination links, e.g.
     * ['search' => 'x'] + 'p_' => ['p_search' => 'x'] (skips empty values).
     */
    private function prefixedQuery(array $filters, string $prefix): array
    {
        $query = [];

        foreach ($filters as $key => $value) {
            if ($value !== '' && $value !== null) {
                $query[$prefix.$key] = $value;
            }
        }

        return $query;
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
