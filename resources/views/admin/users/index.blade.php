<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
                <div><h1 class="text-xl font-bold">Manage Users</h1></div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="hover:text-red-400">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-500">Logout</button></form>
                </div>
            </div>
        </nav>
        <main class="mx-auto max-w-7xl px-4 py-10">
            @if (session('success'))
                <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="mb-6 text-xl font-bold">All accounts</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Password</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Phone</th>
                                <th class="px-4 py-3">Active</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-b">
                                    <td class="px-4 py-3">#{{ $user->id }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $user->full_name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-gray-500">•••••••• (hashed)</td>
                                    <td class="px-4 py-3"><span class="rounded-full {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-gray-200 text-gray-700' }} px-2 py-1 text-xs font-semibold">{{ $user->role }}</span></td>
                                    <td class="px-4 py-3">{{ $user->phone ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $user->is_active ? 'Yes' : 'No' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($user->isAdmin())
                                            <span class="text-xs text-gray-400">Admin — view only</span>
                                        @else
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="rounded bg-yellow-500 px-3 py-2 text-white hover:bg-yellow-400">Edit</a>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this account?');">@csrf @method('DELETE')<button type="submit" class="rounded bg-red-600 px-3 py-2 text-white hover:bg-red-500">Delete</button></form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $users->links() }}</div>
            </div>
        </main>
    </div>
</body>
</html>
