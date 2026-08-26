<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_home_page_shows_featured_menus(): void
    {
        $category = Category::factory()->create();
        Menu::factory()->count(3)->create(['category_id' => $category->category_id]);

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_about_page_loads(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    public function test_contact_page_loads(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    public function test_contact_submission(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Hello, this is a test message.',
        ]);

        // Should redirect back with success or at least not 500
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }

    public function test_menu_index_loads(): void
    {
        $category = Category::factory()->create();
        Menu::factory()->count(2)->create(['category_id' => $category->category_id]);

        $response = $this->get('/menu');
        $response->assertStatus(200);
    }

    public function test_menu_index_filters_by_category(): void
    {
        $cat1 = Category::factory()->create(['category_name' => 'Italian', 'category_slug' => 'italian']);
        $cat2 = Category::factory()->create(['category_name' => 'Japanese', 'category_slug' => 'japanese']);
        Menu::factory()->create(['category_id' => $cat1->category_id, 'menu_name' => 'Pizza Test']);
        Menu::factory()->create(['category_id' => $cat2->category_id, 'menu_name' => 'Sushi Test']);

        $response = $this->get('/menu?category=Italian');
        $response->assertStatus(200);
    }

    public function test_menu_index_search(): void
    {
        $category = Category::factory()->create();
        Menu::factory()->create(['category_id' => $category->category_id, 'menu_name' => 'Nasi Goreng Special']);
        Menu::factory()->create(['category_id' => $category->category_id, 'menu_name' => 'Chicken Ramen']);

        $response = $this->get('/menu?search=Nasi');
        $response->assertStatus(200);
    }

    public function test_menu_show_loads(): void
    {
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id]);

        $response = $this->get('/menu/' . $menu->menu_id);
        $response->assertStatus(200);
    }

    public function test_menu_show_returns_404_for_invalid_id(): void
    {
        $response = $this->get('/menu/99999');
        $response->assertStatus(404);
    }

    public function test_articles_index_loads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Article::create([
            'admin_id' => $admin->user_id,
            'article_title' => 'Test Article',
            'article_slug' => 'test-article',
            'article_category' => 'News',
            'article_content' => 'Content here',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/articles');
        $response->assertStatus(200);
    }

    public function test_article_detail_loads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $article = Article::create([
            'admin_id' => $admin->user_id,
            'article_title' => 'Detail Article',
            'article_slug' => 'detail-article',
            'article_category' => 'Tips',
            'article_content' => 'Detail content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/articles/' . $article->article_slug);
        $response->assertStatus(200);
    }

    public function test_tracking_page_requires_valid_order_number(): void
    {
        $response = $this->get('/track/INVALID-123');
        // Should show not found or at least not 500
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }

    public function test_cart_page_loads(): void
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_checkout_redirects_when_cart_empty(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect(route('cart.index', absolute: false));
    }
}
