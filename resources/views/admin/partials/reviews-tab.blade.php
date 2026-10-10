<div class="mb-6">
    <h2 class="text-xl font-bold">Reviews Management</h2>
    <p class="mt-1 text-sm text-gray-500">Search by comment, customer, or product. Filter by rating or answer status.</p>
</div>
<form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2 xl:grid-cols-4">
    <input type="hidden" name="tab" value="reviews">
    <div class="xl:col-span-2">
        <label for="dashboard-review-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input id="dashboard-review-search" type="text" name="r_search" value="{{ $reviewFilters['search'] ?? '' }}" placeholder="Comment, customer, product…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
    </div>
    <div>
        <label for="dashboard-review-rating" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Rating</label>
        <select id="dashboard-review-rating" name="r_rating" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All ratings</option>
            @foreach (['5' => '5 stars', '4' => '4 stars', '3' => '3 stars', '2' => '2 stars', '1' => '1 star'] as $ratingValue => $ratingLabel)
                <option value="{{ $ratingValue }}" {{ ($reviewFilters['rating'] ?? '') === (string) $ratingValue ? 'selected' : '' }}>{{ $ratingLabel }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="dashboard-review-answered" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Admin response</label>
        <select id="dashboard-review-answered" name="r_answered" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">Answered + unanswered</option>
            <option value="yes" {{ ($reviewFilters['answered'] ?? '') === 'yes' ? 'selected' : '' }}>Answered</option>
            <option value="no" {{ ($reviewFilters['answered'] ?? '') === 'no' ? 'selected' : '' }}>Needs answer</option>
        </select>
    </div>
    <div class="flex gap-2 md:col-span-2 xl:col-span-4">
        <a href="{{ route('admin.dashboard', ['tab' => 'reviews']) }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
    </div>
</form>
<p class="mb-6 text-sm text-gray-500">Every review posted from the customer Reviews page shows up here. Answer a review or delete it.</p>
@if ($reviews->isEmpty())
    <p class="text-gray-500">No reviews match these filters.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Rating</th>
                    <th class="px-4 py-3">Review</th>
                    <th class="px-4 py-3">Admin response</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                    <tr class="border-b align-top">
                        <td class="px-4 py-3">#{{ $review->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $review->user?->full_name ?? 'Deleted account' }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at?->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $review->product?->name ?? 'Deleted product' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-yellow-500">
                            {{ str_repeat('★', $review->rating).str_repeat('☆', 5 - $review->rating) }}
                            <span class="ml-1 text-xs text-gray-500">{{ $review->rating }}/5</span>
                        </td>
                        <td class="max-w-xs px-4 py-3">{{ $review->comment ?: 'No comment written.' }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.reviews.respond', $review) }}" class="space-y-2">
                                @csrf @method('PUT')
                                <textarea name="admin_response" rows="2" required maxlength="2000" class="w-full rounded border px-2 py-1 text-sm"
                                    placeholder="Answer this review...">{{ $review->admin_response }}</textarea>
                                <button type="submit" class="rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-500">{{ $review->hasAdminResponse() ? 'Update response' : 'Save response' }}</button>
                                @if ($review->admin_responded_at)
                                    <p class="text-xs text-gray-500">Answered {{ $review->admin_responded_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded bg-red-600 px-3 py-1.5 text-white hover:bg-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
