<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\StockService;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Enums\RoleEnum;
use App\Enums\StockMovementType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StockService $stockService;
    protected Product $product;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stockService = new StockService();

        $role = Role::create(['name' => RoleEnum::ADMIN->value, 'display_name' => 'Admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        
        $category = Category::create(['name' => 'Cat1', 'slug' => 'cat1']);
        
        $this->product = Product::create([
            'category_id' => $category->id,
            'sku' => 'TEST-001',
            'name' => 'Test Product',
            'stock' => 10,
        ]);
    }

    public function test_can_decrement_stock_successfully()
    {
        $movement = $this->stockService->decrementStock(
            $this->product->id, 
            3, 
            $this->user->id,
            'manual'
        );

        $this->product->refresh();
        
        $this->assertEquals(7, $this->product->stock);
        $this->assertEquals(3, $movement->quantity);
        $this->assertEquals(10, $movement->stock_before);
        $this->assertEquals(7, $movement->stock_after);
        $this->assertEquals(StockMovementType::OUT, $movement->type);
    }

    public function test_cannot_decrement_below_zero()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Insufficient stock for product: Test Product");

        $this->stockService->decrementStock(
            $this->product->id, 
            15, 
            $this->user->id
        );
    }

    public function test_can_increment_stock_successfully()
    {
        $movement = $this->stockService->incrementStock(
            $this->product->id, 
            5, 
            $this->user->id,
            StockMovementType::IN
        );

        $this->product->refresh();
        
        $this->assertEquals(15, $this->product->stock);
        $this->assertEquals(5, $movement->quantity);
    }
}
