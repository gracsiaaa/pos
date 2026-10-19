<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use App\Enums\RoleEnum;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $adminRole = Role::create(['name' => RoleEnum::ADMIN->value, 'display_name' => 'Admin']);
        $cashierRole = Role::create(['name' => RoleEnum::CASHIER->value, 'display_name' => 'Cashier']);
        
        $this->admin = User::factory()->create(['role_id' => $adminRole->id, 'is_active' => true]);
        $this->cashier = User::factory()->create(['role_id' => $cashierRole->id, 'is_active' => true]);
        
        $this->category = Category::create(['name' => 'Snack', 'slug' => 'snack']);
    }

    public function test_admin_can_view_product_list()
    {
        $response = $this->actingAs($this->admin)->get('/admin/products');
        $response->assertStatus(200);
    }

    public function test_cashier_cannot_view_product_list()
    {
        $response = $this->actingAs($this->cashier)->get('/admin/products');
        $response->assertStatus(403);
    }

    public function test_admin_can_create_product()
    {
        $productData = [
            'category_id' => $this->category->id,
            'sku' => 'SNK-001',
            'name' => 'Chitato',
            'purchase_price' => 4000,
            'selling_price' => 5000,
            'stock' => 10,
            'min_stock' => 5,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/products', $productData);
        
        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['sku' => 'SNK-001']);
    }

    public function test_duplicate_sku_is_rejected()
    {
        Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SNK-001',
            'name' => 'Chitato',
            'purchase_price' => 4000,
            'selling_price' => 5000,
        ]);

        $productData = [
            'category_id' => $this->category->id,
            'sku' => 'SNK-001', // duplicate
            'name' => 'Lays',
            'purchase_price' => 4000,
            'selling_price' => 5000,
            'stock' => 10,
            'min_stock' => 5,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/products', $productData);
        $response->assertSessionHasErrors('sku');
    }
}
