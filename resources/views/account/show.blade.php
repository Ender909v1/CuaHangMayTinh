<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Account management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/custom.css') }}">
</head>
<body>
    @include('partials.store-header')
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-center">
                <div class="w-full md:w-2/3 lg:w-1/2 bg-white rounded-lg shadow-md p-4 md:p-10">
                    <h2 class="text-2xl font-semibold mb-1">Account management</h2>
                    @if (session('status'))
                        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-4 rounded-lg bg-gray-50 px-4 py-3 text-sm">
                        <p class="font-semibold">{{ $user->full_name }}</p>
                        <p class="text-gray-500">{{ $user->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="full_name" class="block">Full name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                class="w-full px-3 py-1 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="block">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-1 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="block">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-3 py-1 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="block">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                                class="w-full px-3 py-1 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <hr class="my-6">
                        <h3 class="text-lg font-semibold mb-3">Change password <span class="text-sm font-normal text-gray-500">(optional)</span></h3>
                        <div class="mb-3">
                            <label for="password" class="block">New password (min 6 characters)</label>
                            <input type="password" name="password" id="password" autocomplete="new-password"
                                class="w-full px-3 py-1 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block">Confirm new password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                class="w-full px-3 py-1 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <button type="submit" class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary py-2 px-3 rounded-full w-full">Save changes</button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full py-2 px-3 rounded-full border border-gray-300 hover:border-primary hover:text-primary font-semibold">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>
</body>
</html>
