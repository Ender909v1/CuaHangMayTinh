<div class="mb-6">
    <h2 class="text-xl font-bold">Reviews Management</h2>
    <p class="mt-1 text-gray-500">Every review posted from the customer Reviews page shows up here. Answer a review or delete it.</p>
</div>
@if ($reviews->isEmpty())
    <p class="text-gray-500">No customer reviews yet. Reviews posted from the Reviews page will appear here.</p>
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
