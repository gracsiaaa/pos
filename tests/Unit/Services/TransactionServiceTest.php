<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TransactionService;
use App\Services\StockService;
use App\Services\CashService;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\Transaction;
use App\Enums\RoleEnum;
use App\Enums\TransactionStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TransactionService $transactionService;
    protected Product $product;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionService = new TransactionService(new StockService(), new CashService());

        $role = Role::create(['name' => RoleEnum::CASHIER->value, 'display_name' => 'Cashier']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        
        $category = Category::create(['name' => 'Cat1', 'slug' => 'cat1']);
        
        $this->product = Product::create([
            'category_id' => $category->id,
            'sku' => 'TEST-001',
            'name' => 'Test Product',
            'selling_price' => 10000,
            'purchase_price' => 5000,
            'stock' => 50,
        ]);
    }

    public function test_can_process_transaction_successfully()
    {
        $items = [
            [
                'product_id' => $this->product->id,
                'quantity' => 2,
                'discount' => 0
            ]
        ];

        // Total should be 20000. Paid 25000. Change 5000.
        $transaction = $this->transactionService->processTransaction(
            $this->user->id,
            $items,
            25000,
            'cash',
            0,
            0
        );

        $this->assertEquals(20000, $transaction->total);
        $this->assertEquals(5000, $transaction->change_amount);
        $this->assertEquals(TransactionStatus::COMPLETED, $transaction->status);

        $this->product->refresh();
        $this->assertEquals(48, $this->product->stock); // 50 - 2

        $this->assertDatabaseHas('cash_transactions', [
            'amount' => 20000, // Only the exact revenue goes to cash drawer
            'type' => 'in',
            'reference_type' => 'sale',
            'reference_id' => $transaction->id
        ]);
    }
}
