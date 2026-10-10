<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        @include('admin.partials.navigation', ['pageTitle' => 'Add Category', 'activeTab' => 'categories'])

        <main class="mx-auto max-w-2xl px-4 pt-4 pb-10">
            <div class="rounded-xl bg-white p-8 shadow">
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-1 block text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Parent Category</label>
                        <select name="parent_id" class="w-full rounded-lg border px-3 py-2">
                            <option value="">No parent</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="rounded bg-red-600 px-5 py-2 font-semibold text-white hover:bg-red-500">Save Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="rounded border border-gray-300 px-5 py-2 font-semibold hover:bg-gray-100">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
