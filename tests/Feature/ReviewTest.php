<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_page_lists_customer_reviews_with_the_admin_response(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();
        $this->makeReview($customer, $product, [
            'comment' => 'Great laptop, very fast.',
            'admin_response' => 'Thanks for the feedback!',
            'admin_responded_at' => now(),
        ]);

        $response = $this->get('/reviews');

        $response->assertOk();
        $response->assertSee('Test Laptop');
        $response->assertSee('Great laptop, very fast.');
        $response->assertSee('Response from Computer Store');
        $response->assertSee('Thanks for the feedback!');
    }

    public function test_guest_reads_reviews_but_gets_the_login_prompt_instead_of_the_review_box(): void
    {
        $this->makeReview($this->makeCustomer(), $this->makeProduct(), ['comment' => 'A review from another customer.']);

        $response = $this->get('/reviews');

        $response->assertOk();
        $response->assertSee('to write your own review', false);
        $response->assertDontSee('name="comment"', false);
        // the reviews list is public, only the write box is behind the login
        $response->assertSee('What customers say');
        $response->assertSee('A review from another customer.');
    }

    public function test_logged_in_customer_gets_the_review_box_and_the_reviews_list(): void
    {
        $customer = $this->makeCustomer();
        $this->makeReview($customer, $this->makeProduct(), ['comment' => 'My own earlier review.']);

        $response = $this->actingAs($customer)->get('/reviews');

        $response->assertOk();
        $response->assertSee('Write a review');
        $response->assertSee('name="comment"', false);
        $response->assertSee('What customers say');
        $response->assertSee('My own earlier review.');
        $response->assertSee('(you)');
    }

    public function test_guest_cannot_post_a_review(): void
    {
        $product = $this->makeProduct();

        $response = $this->post('/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Anonymous review.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_review_shows_up_in_the_admin_reviews_tab(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();

        $response = $this->actingAs($customer)->post('/reviews', [
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Solid machine for the price.',
        ]);

        $response->assertRedirect(route('reviews'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Solid machine for the price.',
        ]);

        $dashboard = $this->actingAs($this->makeAdmin())->get('/admin?tab=reviews');

        $dashboard->assertOk();
        $dashboard->assertSee('Reviews Management');
        $dashboard->assertSee('Solid machine for the price.');
        $dashboard->assertSee($customer->full_name);
    }

    public function test_a_review_needs_a_product_a_rating_and_a_comment(): void
    {
        $this->actingAs($this->makeCustomer())
            ->post('/reviews', [])
            ->assertSessionHasErrors(['product_id', 'rating', 'comment']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_admin_can_answer_a_review(): void
    {
        $admin = $this->makeAdmin();
        $review = $this->makeReview($this->makeCustomer(), $this->makeProduct());

        $response = $this->actingAs($admin)->put("/admin/reviews/{$review->id}/response", [
            'admin_response' => 'Sorry about that, we have contacted you by email.',
        ]);

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'reviews']));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'admin_response' => 'Sorry about that, we have contacted you by email.',
        ]);
        $this->assertNotNull($review->fresh()->admin_responded_at);
    }

    public function test_an_empty_admin_response_is_rejected(): void
    {
        $admin = $this->makeAdmin();
        $review = $this->makeReview($this->makeCustomer(), $this->makeProduct());

        $this->actingAs($admin)
            ->put("/admin/reviews/{$review->id}/response", [])
            ->assertSessionHasErrors('admin_response');

        $this->assertNull($review->fresh()->admin_response);
    }

    public function test_admin_can_delete_a_review(): void
    {
        $admin = $this->makeAdmin();
        $review = $this->makeReview($this->makeCustomer(), $this->makeProduct());

        $response = $this->actingAs($admin)->delete("/admin/reviews/{$review->id}");

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'reviews']));
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_cannot_answer_or_delete_a_review(): void
    {
        $customer = $this->makeCustomer();
        $review = $this->makeReview($customer, $this->makeProduct());

        $this->actingAs($customer)
            ->put("/admin/reviews/{$review->id}/response", ['admin_response' => 'Not allowed.'])
            ->assertForbidden();
        $this->actingAs($customer)
            ->delete("/admin/reviews/{$review->id}")
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 1);
        $this->assertNull($review->fresh()->admin_response);
    }

    private function makeCustomer(): User
    {
        return User::create([
            'full_name' => 'Minh Customer',
            'email' => 'minh.customer@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'customer',
        ]);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'full_name' => 'Admin User',
            'email' => 'Admin@pc.lap',
            'password_hash' => Hash::make('Admin_123'),
            'role' => 'admin',
        ]);
    }

    private function makeProduct(): Product
    {
        $brand = Brand::create(['name' => 'TestBrand']);
        $category = Category::create(['name' => 'Laptop']);

        return Product::create([
            'name' => 'Test Laptop',
            'sku' => 'SKU-'.uniqid(),
            'price' => 999.99,
            'stock_qty' => 3,
            'type' => 'laptop',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function makeReview(User $user, Product $product, array $attributes = []): Review
    {
        return Review::create(array_merge([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'It does the job.',
            'created_at' => now(),
        ], $attributes));
    }
}
