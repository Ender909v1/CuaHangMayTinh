<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('tailstore4-main/logo/logo.jpg') }}" />
    <title>Customer reviews</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('tailstore4-main/assets/css/custom.css') }}">
</head>
<body>
    @include('partials.store-header')

    <section id="reviews-page" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-semibold mb-1">Customer reviews</h1>
            <p class="text-gray-500 mb-6">Read what other customers think about our products and how our shop answered them.</p>

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

            {{-- Write a review: the box on top of the list. The textarea grows down as the review gets longer. --}}
            @auth
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Write a review</h2>
                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="md:w-1/2">
                                <label for="product_id" class="block mb-1 font-semibold">Product</label>
                                <select name="product_id" id="product_id" required
                                    class="w-full px-3 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Choose the product you are reviewing</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:w-1/2">
                                <label for="rating" class="block mb-1 font-semibold">Rating</label>
                                <select name="rating" id="rating" required
                                    class="w-full px-3 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                    @for ($star = 5; $star >= 1; $star--)
                                        <option value="{{ $star }}" @selected(old('rating') == $star)>{{ str_repeat('★', $star).str_repeat('☆', 5 - $star) }} ({{ $star }}/5)</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="comment" class="block mb-1 font-semibold">Your review</label>
                            <textarea name="comment" id="comment" rows="2" required maxlength="2000" data-review-box
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none overflow-y-auto"
                                placeholder="Tell other customers what you think about this product...">{{ old('comment') }}</textarea>
                        </div>
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="bg-primary text-white border border-primary hover:bg-transparent hover:text-primary py-2 px-5 rounded-full font-semibold">Post review</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mb-6 rounded-lg border border-gray-line bg-gray-50 px-4 py-3 text-sm">
                    <a href="{{ route('login') }}" class="text-primary font-semibold">Log in</a> or
                    <a href="{{ route('register') }}" class="text-primary font-semibold">register</a> to write your own review.
                </div>
            @endauth

            {{-- Reviews from every customer, with the answer written by our shop --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">What customers say ({{ $reviews->count() }})</h2>

                @forelse ($reviews as $review)
                    <article class="rounded-lg border border-gray-line p-4{{ $loop->last ? '' : ' mb-4' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="font-semibold">
                                    {{ $review->user?->full_name ?? 'Computer Store customer' }}
                                    @if (Auth::id() === $review->user_id)
                                        <span class="text-xs text-gray-400">(you)</span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $review->product?->name ?? 'Deleted product' }}
                                    @if ($review->created_at)
                                        · {{ $review->created_at->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                            <p class="text-primary text-lg tracking-wide" title="{{ $review->rating }}/5">
                                {{ str_repeat('★', $review->rating).str_repeat('☆', 5 - $review->rating) }}
                            </p>
                        </div>

                        @if ($review->comment)
                            <p class="mt-3 text-gray-700">{!! nl2br(e($review->comment)) !!}</p>
                        @endif

                        @if ($review->hasAdminResponse())
                            <div class="mt-4 rounded-lg border border-gray-line bg-gray-50 px-4 py-3">
                                <p class="text-sm font-semibold text-primary">Response from Computer Store</p>
                                <p class="text-sm mt-1 text-gray-700">{!! nl2br(e($review->admin_response)) !!}</p>
                                @if ($review->admin_responded_at)
                                    <p class="text-xs mt-1 text-gray-400">{{ $review->admin_responded_at->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="text-gray-500">No reviews yet. Be the first customer to review a product.</p>
                @endforelse
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('tailstore4-main/assets/js/script.js') }}"></script>
    <script>
        /* The review box grows down with the text instead of scrolling, up to 24rem. */
        document.querySelectorAll('[data-review-box]').forEach(function (box) {
            const maxHeight = 384;

            const grow = () => {
                box.style.height = 'auto';
                box.style.height = Math.min(box.scrollHeight, maxHeight) + 'px';
                box.style.overflowY = box.scrollHeight > maxHeight ? 'auto' : 'hidden';
            };

            box.addEventListener('input', grow);
            grow();
        });
    </script>
</body>
</html>

