<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\CashTransaction;
use App\Enums\RoleEnum;
use App\Enums\StockMovementType;
use App\Enums\CashTransactionType;
use App\Services\TransactionService;
use App\Services\StockService;
use App\Services\CashService;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::create([
            'name' => RoleEnum::ADMIN->value,
            'display_name' => 'Administrator',
        ]);

        $cashierRole = Role::create([
            'name' => RoleEnum::CASHIER->value,
            'display_name' => 'Kasir',
        ]);

        $ownerRole = Role::create([
            'name' => RoleEnum::OWNER->value,
            'display_name' => 'Owner / Manager',
        ]);

        // 2. Users
        $admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin POS',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $cashier = User::create([
            'role_id' => $cashierRole->id,
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $owner = User::create([
            'role_id' => $ownerRole->id,
            'name' => 'Owner Toko',
            'email' => 'owner@pos.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // 3. Categories
        $catMkn = Category::create(['name' => 'Makanan & Snack', 'slug' => 'makanan-snack', 'description' => 'Berbagai makanan ringan dan biskuit']);
        $catMnm = Category::create(['name' => 'Minuman Dingin', 'slug' => 'minuman-dingin', 'description' => 'Minuman segar kemasan']);
        $catKbt = Category::create(['name' => 'Kebutuhan Rumah', 'slug' => 'kebutuhan-rumah', 'description' => 'Sabun, sampo, dan deterjen']);
        $catRkk = Category::create(['name' => 'Rokok & Tembakau', 'slug' => 'rokok-tembakau', 'description' => 'Produk rokok']);

        // 4. Suppliers
        $sup1 = Supplier::create([
            'name' => 'PT Indofood Sukses Makmur',
            'phone' => '021-57958822',
            'email' => 'sales@indofood.co.id',
            'address' => 'Sudirman Plaza, Indofood Tower, Jakarta',
            'contact_person' => 'Budi Santoso',
        ]);

        $sup2 = Supplier::create([
            'name' => 'PT Unilever Indonesia Tbk',
            'phone' => '021-80827000',
            'email' => 'supply@unilever.com',
            'address' => 'BSD City, Tangerang',
            'contact_person' => 'Dewi Lestari',
        ]);

        $sup3 = Supplier::create([
            'name' => 'PT Mayora Indah Tbk',
            'phone' => '021-80657000',
            'email' => 'info@mayora.co.id',
            'address' => 'Tomang, Jakarta Barat',
            'contact_person' => 'Andi Wijaya',
        ]);

        // 5. Products
        $productsData = [
            [
                'category_id' => $catMkn->id,
                'supplier_id' => $sup1->id,
                'sku' => 'MKN-001',
                'barcode' => '8998866200111',
                'name' => 'Indomie Goreng Original 85g',
                'purchase_price' => 2800,
                'selling_price' => 3500,
                'stock' => 120,
                'min_stock' => 24,
                'image' => 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&q=80',
            ],
            [
                'category_id' => $catMkn->id,
                'supplier_id' => $sup1->id,
                'sku' => 'MKN-002',
                'barcode' => '8998866200222',
                'name' => 'Indomie Kuah Ayam Bawang 75g',
                'purchase_price' => 2700,
                'selling_price' => 3400,
                'stock' => 80,
                'min_stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8dd?w=400&q=80',
            ],
            [
                'category_id' => $catMnm->id,
                'supplier_id' => $sup3->id,
                'sku' => 'MNM-001',
                'barcode' => '8991001100333',
                'name' => 'Air Mineral Le Minerale 600ml',
                'purchase_price' => 2500,
                'selling_price' => 3500,
                'stock' => 150,
                'min_stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1546171309-804cc2bd3133?w=400&q=80',
            ],
            [
                'category_id' => $catMnm->id,
                'supplier_id' => $sup3->id,
                'sku' => 'MNM-002',
                'barcode' => '8991001100444',
                'name' => 'Teh Manis Pucuk Harum 350ml',
                'purchase_price' => 3000,
                'selling_price' => 4000,
                'stock' => 95,
                'min_stock' => 24,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&q=80',
            ],
            [
                'category_id' => $catMkn->id,
                'supplier_id' => $sup3->id,
                'sku' => 'MKN-003',
                'barcode' => '8991001100555',
                'name' => 'Beng Beng Chocolate Bar 25g',
                'purchase_price' => 1800,
                'selling_price' => 2500,
                'stock' => 60,
                'min_stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1548681528-6a5c45b66b42?w=400&q=80',
            ],
            [
                'category_id' => $catKbt->id,
                'supplier_id' => $sup2->id,
                'sku' => 'KBT-001',
                'barcode' => '8999999100666',
                'name' => 'Lifebuoy Sabun Mandi Total 10 85g',
                'purchase_price' => 3800,
                'selling_price' => 5000,
                'stock' => 45,
                'min_stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1600857062241-98e5dba7f214?w=400&q=80',
            ],
            [
                'category_id' => $catKbt->id,
                'supplier_id' => $sup2->id,
                'sku' => 'KBT-002',
                'barcode' => '8999999100777',
                'name' => 'Pepsodent Pencegah Gigi Berlubang 120g',
                'purchase_price' => 7500,
                'selling_price' => 9500,
                'stock' => 8, // Low stock test!
                'min_stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1559598467-f8b76c8155d0?w=400&q=80',
            ],
        ];

        foreach ($productsData as $pData) {
            $prod = Product::create($pData);

            // Record initial stock movement
            StockMovement::create([
                'product_id' => $prod->id,
                'user_id' => $admin->id,
                'type' => StockMovementType::IN,
                'quantity' => $prod->stock,
                'stock_before' => 0,
                'stock_after' => $prod->stock,
                'reference_type' => 'initial_stock',
                'reference_id' => null,
                'note' => 'Stok awal setup sistem',
            ]);
        }

        // 6. Initial Cash
        CashTransaction::create([
            'user_id' => $admin->id,
            'type' => CashTransactionType::IN,
            'amount' => 500000.00,
            'description' => 'Modal kas awal kasir',
            'reference_type' => 'opening_balance',
            'date' => now()->toDateString(),
        ]);

        // 7. Seed Transactions
        $this->seedTransactions($cashier->id);
    }

    private function seedTransactions($userId)
    {
        $transactionService = new TransactionService(new StockService(), new CashService());
        $products = Product::all();
        $methods = ['cash', 'cash', 'cash', 'cash', 'debit', 'qris']; // weighted random
        
        for ($i = 6; $i >= 0; $i--) { // Last 7 days
            $date = Carbon::today()->subDays($i);
            
            // 3-6 transactions per day
            $trxCount = rand(3, 6);
            
            for ($j = 0; $j < $trxCount; $j++) {
                $items = [];
                $total = 0;
                
                // 1-3 items per transaction
                $itemCount = rand(1, 3);
                for ($k = 0; $k < $itemCount; $k++) {
                    $prod = $products->random();
                    $qty = rand(1, 2);
                
                // Perlu refresh data base sebelum mengambil stok terkini
                $prod = Product::find($prod->id);
                if ($prod->stock < $qty) {
                    if ($prod->stock < 1) continue;
                    $qty = $prod->stock; 
                }
                
                $items[] = [
                        'product_id' => $prod->id,
                        'quantity' => $qty,
                        'discount' => 0
                    ];
                    $total += $prod->selling_price * $qty;
                }
                
                if (empty($items)) continue;

                $method = $methods[array_rand($methods)];
                $paid = $method === 'cash' ? $total + (rand(0, 2) * 5000) : $total; // exact or more if cash
                
                // Simulate time within that day
                Carbon::setTestNow($date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)));
                
                $trx = $transactionService->processTransaction($userId, $items, $paid, $method);

                // Void one random transaction
                if ($i == 2 && $j == 1) {
                    $transactionService->voidTransaction($trx, $userId);
                }
            }
        }
        
        Carbon::setTestNow(); // reset time
    }
}

