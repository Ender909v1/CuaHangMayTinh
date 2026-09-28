<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Customer review tab: everyone can read the reviews and the admin answers,
     * logged-in customers get the "write a review" box on top of the list.
     */
    public function index(): View
    {
        $reviews = Review::with(['user', 'product'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('reviews.index', compact('reviews', 'products'));
    }

    /**
     * Store the review written by the logged-in customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'product_id.required' => 'Choose the product you are reviewing.',
            'product_id.exists' => 'That product is not available any more.',
            'rating.required' => 'Pick a rating between 1 and 5 stars.',
            'rating.between' => 'The rating must be between 1 and 5 stars.',
            'comment.required' => 'Write a few words about the product.',
            'comment.min' => 'Your review needs at least 3 characters.',
            'comment.max' => 'Your review cannot be longer than 2000 characters.',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'created_at' => now(),
        ]);

        return redirect()->route('reviews')->with('status', 'Thanks! Your review has been posted.');
    }
}
