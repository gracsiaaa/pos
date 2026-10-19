<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use App\Enums\RoleEnum;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $cashier;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['name' => RoleEnum::CASHIER->value, 'display_name' => 'Cashier']);
        $this->cashier = User::factory()->create(['role_id' => $role->id, 'is_active' => true]);
        
        $category = Category::create(['name' => 'Snack', 'slug' => 'snack']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'sku' => 'SNK-001',
            'name' => 'Chitato',
            'purchase_price' => 4000,
            'selling_price' => 5000,
            'stock' => 10,
        ]);
    }

    public function test_cashier_can_access_pos()
    {
        $response = $this->actingAs($this->cashier)->get('/pos');
        $response->assertStatus(200);
    }

    public function test_cashier_can_checkout_successfully()
    {
        $payload = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'discount' => 0
                ]
            ],
            'paid_amount' => 15000,
            'payment_method' => 'cash',
        ];

        $response = $this->actingAs($this->cashier)->postJson('/pos', $payload);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'change_amount' => 5000 // 15000 - (5000*2)
                 ]);

        // Assert database records
        $this->assertDatabaseHas('transactions', [
            'total' => 10000,
            'paid_amount' => 15000,
            'change_amount' => 5000
        ]);

        $this->assertDatabaseHas('transaction_details', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 5000,
            'subtotal' => 10000
        ]);

        // Assert stock reduced
        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock); // 10 - 2
        
        // Assert stock movement
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'type' => 'out'
        ]);

        // Assert cash recorded
        $this->assertDatabaseHas('cash_transactions', [
            'amount' => 10000,
            'type' => 'in'
        ]);
    }

    public function test_checkout_fails_if_stock_insufficient()
    {
        $payload = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 15, // Only 10 available
                    'discount' => 0
                ]
            ],
            'paid_amount' => 100000,
            'payment_method' => 'cash',
        ];

        $response = $this->actingAs($this->cashier)->postJson('/pos', $payload);
        
        $response->assertStatus(400)
                 ->assertJsonPath('success', false);

        // Ensure stock not reduced
        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);
        
        // Ensure no transaction created
        $this->assertDatabaseMissing('transactions', ['user_id' => $this->cashier->id]);
    }
}
