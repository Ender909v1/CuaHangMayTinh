<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold">Users Management</h2>
        <p class="mt-1 text-sm text-gray-500">Search by name, email, or phone. Filter by role or status.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700">Full Users Page</a>
</div>
<form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2 xl:grid-cols-4">
    <input type="hidden" name="tab" value="users">
    <div class="xl:col-span-2">
        <label for="dashboard-user-search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Search</label>
        <input id="dashboard-user-search" type="text" name="u_search" value="{{ $userFilters['search'] ?? '' }}" placeholder="Name, email, phone…" class="w-full rounded border px-3 py-2 text-sm" oninput="clearTimeout(window._adminFilterT);window._adminFilterT=setTimeout(()=>this.form.submit(),600)">
    </div>
    <div>
        <label for="dashboard-user-role" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Role</label>
        <select id="dashboard-user-role" name="u_role" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All roles</option>
            @foreach (['customer' => 'Customer', 'staff' => 'Staff', 'admin' => 'Admin'] as $roleValue => $roleLabel)
                <option value="{{ $roleValue }}" {{ ($userFilters['role'] ?? '') === $roleValue ? 'selected' : '' }}>{{ $roleLabel }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="dashboard-user-active" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
        <select id="dashboard-user-active" name="u_active" class="w-full rounded border px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <option value="yes" {{ ($userFilters['active'] ?? '') === 'yes' ? 'selected' : '' }}>Active</option>
            <option value="no" {{ ($userFilters['active'] ?? '') === 'no' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="flex gap-2 md:col-span-2 xl:col-span-4">
        <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="rounded border px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Reset</a>
    </div>
</form>
@if ($users->isEmpty())
    <p class="text-gray-500">No users match these filters.</p>
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
