@php
    /** @var \App\Models\Product $product */
@endphp
{{-- Per-product inventory controls. The open/closed state lives on the table's <tbody> (Alpine). --}}
<div class="flex flex-wrap gap-2">
    <button type="button" @click="editingId = editingId === {{ $product->id }} ? null : {{ $product->id }}"
        class="rounded bg-yellow-500 px-3 py-1.5 text-white hover:bg-yellow-400">Edit</button>
    <button type="button" @click="historyId = historyId === {{ $product->id }} ? null : {{ $product->id }}"
        class="rounded bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-500">History</button>
</div>
