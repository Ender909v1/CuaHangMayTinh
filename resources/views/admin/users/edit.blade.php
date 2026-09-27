<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div><h1 class="text-xl font-bold">Edit User #{{ $user->id }}</h1></div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.users.index') }}" class="hover:text-red-400">Back</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button></form>
                </div>
            </div>
        </nav>
        <main class="mx-auto max-w-2xl px-4 py-10">
            <div class="rounded-xl bg-white p-8 shadow">
                @if ($errors->any())
                    <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">@csrf @method('PUT')
                    <div><label class="mb-1 block text-sm font-medium">Full name</label><input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full rounded-lg border px-3 py-2" required></div>
                    <div><label class="mb-1 block text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border px-3 py-2" required></div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div><label class="mb-1 block text-sm font-medium">Phone</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border px-3 py-2"></div>
                        <div><label class="mb-1 block text-sm font-medium">Role</label><select name="role" class="w-full rounded-lg border px-3 py-2"><option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>customer</option><option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>staff</option><option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>admin</option></select></div>
                    </div>
                    <div><label class="mb-1 block text-sm font-medium">Address</label><input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full rounded-lg border px-3 py-2"></div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div><label class="mb-1 block text-sm font-medium">New password (leave blank to keep)</label><input type="password" name="password" class="w-full rounded-lg border px-3 py-2"></div>
                        <div><label class="mb-1 block text-sm font-medium">Confirm password</label><input type="password" name="password_confirmation" class="w-full rounded-lg border px-3 py-2"></div>
                    </div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-4 w-4"><label>Active account</label></div>
                    <div class="flex gap-3"><button type="submit" class="rounded bg-red-600 px-5 py-2 font-semibold text-white hover:bg-red-500">Save</button><a href="{{ route('admin.users.index') }}" class="rounded border px-5 py-2 font-semibold hover:bg-gray-100">Cancel</a></div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
