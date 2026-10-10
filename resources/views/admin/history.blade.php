<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        @include('admin.partials.navigation', ['pageTitle' => 'Admin History', 'activeTab' => 'history'])

        <main class="mx-auto max-w-6xl px-4 pt-4 pb-10">
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="mb-6">
                    <h2 class="text-xl font-bold">History list</h2>
                </div>

                <div class="space-y-3">
                    @foreach ($history as $entry)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4">
                            <div>
                                <p class="font-semibold">{{ $entry->product?->name ?? $entry->details }}</p>
                                @if ($entry->product)
                                    <p class="text-sm text-gray-500">{{ $entry->details }}</p>
                                @endif
                                @if ($entry->note)
                                    <p class="text-sm text-gray-700">Note: {{ $entry->note }}</p>
                                @endif
                                <p class="text-sm text-gray-500">By {{ $entry->user?->full_name ?? 'Admin account unavailable' }}</p>
                            </div>
                            <div class="text-right text-sm text-gray-500">
                                <p>{{ ucwords(str_replace('_', ' ', $entry->action)) }}</p>
                                <p>{{ $entry->created_at ? $entry->created_at->format('d/m/Y H:i') : '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $history->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
