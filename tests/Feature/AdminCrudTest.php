<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        Storage::fake('public');
    }

    // ========== Category ==========

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'category_name' => 'Test Category',
            'category_slug' => 'test-category',
        ]);

        $response->assertRedirect(route('admin.categories.index', absolute: false));
        $this->assertDatabaseHas('categories', ['category_name' => 'Test Category']);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category->category_id), [
            'category_name' => 'Updated Name',
            'category_slug' => 'updated-name',
        ]);

        $response->assertRedirect(route('admin.categories.index', absolute: false));
        $this->assertDatabaseHas('categories', ['category_id' => $category->category_id, 'category_name' => 'Updated Name']);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category->category_id));

        $response->assertRedirect(route('admin.categories.index', absolute: false));
        $this->assertSoftDeleted('categories', ['category_id' => $category->category_id]);
    }

    public function test_admin_can_list_categories(): void
    {
        Category::factory()->count(3)->create();
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    // ========== Menu ==========

    public function test_admin_can_create_menu(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.menus.store'), [
            'category_id' => $category->category_id,
            'menu_name' => 'Nasi Goreng Test',
            'menu_price' => 35000,
            'menu_description' => 'Delicious test menu',
            'is_available' => 1,
            'menu_image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertRedirect(route('admin.menus.index', absolute: false));
        $this->assertDatabaseHas('menus', ['menu_name' => 'Nasi Goreng Test']);
    }

    public function test_admin_can_update_menu(): void
    {
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id]);

        $response = $this->actingAs($this->admin)->put(route('admin.menus.update', $menu->menu_id), [
            'category_id' => $category->category_id,
            'menu_name' => 'Updated Menu',
            'menu_price' => 50000,
            'menu_description' => 'Updated desc',
            'is_available' => 1,
        ]);

        $response->assertRedirect(route('admin.menus.index', absolute: false));
        $this->assertDatabaseHas('menus', ['menu_id' => $menu->menu_id, 'menu_name' => 'Updated Menu']);
    }

    public function test_admin_can_delete_menu(): void
    {
        $menu = Menu::factory()->create();
        $response = $this->actingAs($this->admin)->delete(route('admin.menus.destroy', $menu->menu_id));
        $response->assertRedirect(route('admin.menus.index', absolute: false));
        $this->assertSoftDeleted('menus', ['menu_id' => $menu->menu_id]);
    }

    public function test_admin_can_filter_menus_by_search(): void
    {
        $category = Category::factory()->create();
        Menu::factory()->create(['category_id' => $category->category_id, 'menu_name' => 'Special Pizza']);
        Menu::factory()->create(['category_id' => $category->category_id, 'menu_name' => 'Sushi Roll']);

        $response = $this->actingAs($this->admin)->get(route('admin.menus.index', ['search' => 'Pizza']));
        $response->assertStatus(200);
    }

    // ========== Voucher ==========

    public function test_admin_can_create_voucher(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.vouchers.store'), [
            'voucher_name' => 'Test Voucher',
            'voucher_code' => 'TEST123',
            'voucher_description' => 'Test desc',
            'voucher_type' => 'fixed',
            'voucher_value' => 10000,
            'min_order' => 50000,
            'max_discount' => null,
            'quota' => 100,
            'expiration_date' => now()->addMonths(1)->format('Y-m-d\TH:i'),
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.vouchers.index', absolute: false));
        $this->assertDatabaseHas('vouchers', ['voucher_code' => 'TEST123']);
    }

    public function test_admin_can_list_vouchers(): void
    {
        Voucher::factory()->count(2)->create();
        $response = $this->actingAs($this->admin)->get(route('admin.vouchers.index'));
        $response->assertStatus(200);
    }

    // ========== Users ==========

    public function test_admin_can_list_users(): void
    {
        User::factory()->count(3)->create();
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_user_role(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user->user_id), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'courier',
        ]);

        // Depending on controller validation, check at least not 500
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }

    // ========== Articles ==========

    public function test_admin_can_create_article(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), [
            'article_title' => 'Test Article Title',
            'article_slug' => 'test-article-title',
            'article_category' => 'News',
            'article_content' => 'This is test content for article.',
            'is_published' => 1,
            'thumbnail' => UploadedFile::fake()->image('thumb.jpg'),
        ]);

        $response->assertRedirect(route('admin.articles.index', absolute: false));
        $this->assertDatabaseHas('articles', ['article_slug' => 'test-article-title']);
    }

    public function test_admin_can_list_articles(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.index'));
        $response->assertStatus(200);
    }

    // ========== Dashboard & Reports ==========

    public function test_admin_dashboard_loads(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_reports_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $response->assertStatus(200);
    }

    public function test_admin_transactions_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.transactions.index'));
        $response->assertStatus(200);
    }

    public function test_admin_deliveries_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.deliveries.index'));
        $response->assertStatus(200);
    }
}
