@php
    /** @var \App\Models\Product $product */
    /** @var int $colspan */
@endphp
{{-- Opens under a product row: change the stock number (note required) or show the change history. --}}
<tr x-show="editingId === {{ $product->id }} || historyId === {{ $product->id }}" style="display: none">
    <td colspan="{{ $colspan }}" class="bg-gray-50 px-4 py-4">
        {{-- Edit stock: the note is mandatory (also enforced server-side). --}}
        <div x-show="editingId === {{ $product->id }}" style="display: none"
            class="rounded-lg border border-gray-200 bg-white p-4">
            <form method="POST" action="{{ route('admin.inventory.update-stock', $product) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <p class="font-semibold">Change stock — {{ $product->name }} <span class="text-sm font-normal text-gray-500">(currently {{ $product->stock_qty }})</span></p>
                <div>
                    <label for="stock_qty_{{ $product->id }}" class="block text-sm font-medium">New stock number</label>
                    <input id="stock_qty_{{ $product->id }}" type="number" name="stock_qty" min="0" step="1" required
                        value="{{ old('stock_qty', $product->stock_qty) }}"
                        class="mt-1 w-40 rounded border px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="note_{{ $product->id }}" class="block text-sm font-medium">Note <span class="text-red-600">*</span></label>
                    <textarea id="note_{{ $product->id }}" name="note" rows="2" required maxlength="1000"
                        placeholder="Why is the stock number changing? e.g. Received 10 units from the supplier."
                        class="mt-1 w-full rounded border px-3 py-2 text-sm">{{ old('note') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Required — the note is saved in this product's history.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Save stock</button>
                    <button type="button" @click="editingId = null" class="rounded-full border border-gray-300 px-4 py-2 text-sm font-semibold">Cancel</button>
                </div>
            </form>
        </div>

        {{-- History: every change (created/updated/stock) with the note the admin wrote. --}}
        <div x-show="historyId === {{ $product->id }}" style="display: none"
            class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="font-semibold">History — {{ $product->name }}</p>
            @forelse ($product->histories as $entry)
                <div class="mt-3 rounded border border-gray-200 p-3 text-sm">
                    <p class="font-semibold">
                        @if ($entry->old_stock_qty !== null || $entry->new_stock_qty !== null)
                            Stock: {{ $entry->old_stock_qty ?? '—' }} → {{ $entry->new_stock_qty ?? '—' }}
                        @else
                            {{ ucfirst(str_replace('_', ' ', $entry->action)) }}
                        @endif
                    </p>
                    @if ($entry->note)
                        <p class="text-gray-700">Note: {{ $entry->note }}</p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $entry->user?->full_name ?? 'System' }}
                        · {{ $entry->created_at ? $entry->created_at->format('d/m/Y H:i') : 'unknown time' }}
                        @if ($entry->details)
                            · {{ $entry->details }}
                        @endif
                    </p>
                </div>
            @empty
                <p class="mt-2 text-sm text-gray-500">Nothing recorded for this product yet.</p>
            @endforelse
        </div>
    </td>
</tr>
