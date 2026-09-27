<div class="mb-6">
    <h2 class="text-xl font-bold">Users Management</h2>
</div>
@if ($users->isEmpty())
    <p class="text-gray-500">No users yet.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-4 py-3">#{{ $user->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $user->full_name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->role }}</td>
                        <td class="px-4 py-3">
                            @if ($user->isAdmin())
                                <span class="text-xs text-gray-400">Admin — view only</span>
                            @else
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded bg-yellow-500 px-3 py-1.5 text-white hover:bg-yellow-400">Edit</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this account?');">@csrf @method('DELETE')<button type="submit" class="rounded bg-red-600 px-3 py-1.5 text-white hover:bg-red-500">Delete</button></form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
