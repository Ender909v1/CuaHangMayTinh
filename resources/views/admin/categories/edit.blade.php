<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                <div>
                    <h1 class="text-xl font-bold">Edit Category</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard', ['tab' => 'categories']) }}" class="hover:text-red-400">Back</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-2xl px-4 py-10">
            <div class="rounded-xl bg-white p-8 shadow">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-1 block text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full rounded-lg border px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Parent Category</label>
                        <select name="parent_id" class="w-full rounded-lg border px-3 py-2">
                            <option value="">No parent</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}" {{ old('parent_id', $category->parent_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="rounded bg-red-600 px-5 py-2 font-semibold text-white hover:bg-red-500">Save</button>
                        <a href="{{ route('admin.dashboard', ['tab' => 'categories']) }}" class="rounded border border-gray-300 px-5 py-2 font-semibold hover:bg-gray-100">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
