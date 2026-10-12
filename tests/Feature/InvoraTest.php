<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoraTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['username' => 'admin', 'password' => 'admin123']);
    }

    private function staff(): User
    {
        return User::factory()->create(['username' => 'staff', 'password' => 'staff123']);
    }

    private function validProduct(array $override = []): array
    {
        return array_merge([
            'category_id'   => Category::factory()->create()->id,
            'sku'           => 'INV-NEW-001',
            'name'          => 'Produk Baru',
            'price'         => 10000,
            'stock'         => 5,
            'minimum_stock' => 2,
        ], $override);
    }

    // ---------- Autentikasi dan hak akses ----------

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/products')->assertRedirect(route('login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_login_success_and_failure(): void
    {
        $this->admin();

        $this->post('/login', ['username' => 'admin', 'password' => 'salah'])
            ->assertSessionHasErrors('username');

        $this->post('/login', ['username' => 'admin', 'password' => 'admin123'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_staff_gets_403_on_admin_pages(): void
    {
        $this->actingAs($this->staff());

        $this->get('/users')->assertForbidden();
        $this->get('/products/create')->assertForbidden();
        $this->get('/categories')->assertForbidden();
        $this->get('/suppliers')->assertForbidden();
    }

    public function test_staff_can_view_products_and_record_stock(): void
    {
        $this->actingAs($this->staff());

        $this->get('/products')->assertOk()->assertDontSee('+ Add Product');
        $this->get('/stock/out')->assertOk();
        $this->get('/reports')->assertOk();
    }

    // ---------- Relationship Eloquent ----------

    public function test_eloquent_relationships(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $supplier = Supplier::factory()->create();
        $product->suppliers()->attach($supplier->id, ['supplier_price' => 5000]);

        $this->assertTrue($category->products->contains($product));
        $this->assertTrue($product->category->is($category));
        $this->assertEquals(5000, $product->suppliers->first()->pivot->supplier_price);
        $this->assertTrue($supplier->products->contains($product));
    }

    // ---------- Produk: CRUD, validasi, search, pagination ----------

    public function test_admin_can_create_product(): void
    {
        $this->actingAs($this->admin());

        $this->post('/products', $this->validProduct())->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['sku' => 'INV-NEW-001', 'stock' => 5]);
    }

    public function test_product_validation_rules(): void
    {
        $this->actingAs($this->admin());
        Product::factory()->create(['sku' => 'INV-DUP-001']);

        $this->post('/products', $this->validProduct(['sku' => 'INV-DUP-001']))
            ->assertSessionHasErrors(['sku' => 'SKU must be unique.']);
        $this->post('/products', $this->validProduct(['stock' => 0]))
            ->assertSessionHasErrors(['stock' => 'Initial stock must be greater than zero.']);
        $this->post('/products', $this->validProduct(['stock' => -3]))
            ->assertSessionHasErrors('stock');
        $this->post('/products', $this->validProduct(['price' => -1]))
            ->assertSessionHasErrors('price');
        $this->post('/products', $this->validProduct(['name' => '']))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('products', ['sku' => 'INV-NEW-001']);
    }

    public function test_update_does_not_change_stock(): void
    {
        $this->actingAs($this->admin());
        $product = Product::factory()->create(['stock' => 10, 'sku' => 'INV-OLD-001']);

        $this->put("/products/{$product->id}", [
            'category_id' => $product->category_id, 'sku' => 'INV-OLD-001', 'name' => 'Nama Baru',
            'price' => 2000, 'minimum_stock' => 3, 'stock' => 999,
        ])->assertRedirect(route('products.index'));

        $product->refresh();
        $this->assertSame('Nama Baru', $product->name);
        $this->assertSame(10, $product->stock);
    }

    public function test_product_with_history_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin());
        $product = Product::factory()->create();
        StockMovement::factory()->create(['product_id' => $product->id]);

        $this->delete("/products/{$product->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_product_without_history_can_be_deleted(): void
    {
        $this->actingAs($this->admin());
        $product = Product::factory()->create();

        $this->delete("/products/{$product->id}")->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_listing_search_filter_sort_and_pagination(): void
    {
        $this->actingAs($this->admin());
        $category = Category::factory()->create();
        Product::factory()->count(12)->create(['category_id' => $category->id, 'name' => 'Kabel Data']);
        Product::factory()->create(['name' => 'Monitor Khusus', 'stock' => 0]);

        $this->get('/products?q=Monitor')->assertOk()->assertSee('Monitor Khusus')->assertDontSee('Kabel Data');
        $this->get('/products?status=out')->assertOk()->assertSee('Monitor Khusus');
        $this->get('/products?sort=price&dir=desc')->assertOk();
        $this->get('/products?sort=DROP_TABLE')->assertOk(); // kolom sort tidak valid diabaikan

        // 13 produk, 10 per halaman: ada halaman 2 dan query string tetap terbawa
        $this->get('/products?category_id='.$category->id)
            ->assertOk()
            ->assertSee('category_id='.$category->id.'&amp;page=2', false);
    }

    // ---------- Kategori ----------

    public function test_category_rules(): void
    {
        $this->actingAs($this->admin());
        $category = Category::factory()->create(['name' => 'Elektronik']);

        $this->post('/categories', ['name' => 'Elektronik'])->assertSessionHasErrors('name');
        $this->post('/categories', ['name' => 'Baru'])->assertRedirect(route('categories.index'));

        Product::factory()->create(['category_id' => $category->id]);
        $this->delete("/categories/{$category->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    // ---------- Supplier: arsip dan pulihkan ----------

    public function test_supplier_archive_and_restore(): void
    {
        $this->actingAs($this->admin());
        $supplier = Supplier::factory()->create(['supplier_name' => 'PT Arsip Tes']);

        $this->patch("/suppliers/{$supplier->id}/archive")->assertRedirect(route('suppliers.index'));
        $this->assertFalse($supplier->fresh()->is_active);

        $this->get('/suppliers')->assertDontSee('PT Arsip Tes');
        $this->get('/suppliers?view=archived')->assertSee('PT Arsip Tes');
        $this->get('/stock/in')->assertDontSee('PT Arsip Tes'); // tidak muncul di dropdown Stock In

        $this->patch("/suppliers/{$supplier->id}/restore");
        $this->assertTrue($supplier->fresh()->is_active);
        $this->get('/stock/in')->assertSee('PT Arsip Tes');
    }

    // ---------- Transaksi stok ----------

    public function test_stock_in_increases_stock_and_records_history(): void
    {
        $user = $this->admin();
        $this->actingAs($user);
        $product = Product::factory()->create(['stock' => 4]);

        $this->post('/stock/in', ['product_id' => $product->id, 'quantity' => 6, 'date' => '2026-10-10', 'notes' => 'masuk'])
            ->assertRedirect(route('stock.history'));

        $this->assertSame(10, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id, 'user_id' => $user->id, 'type' => 'IN', 'quantity' => 6,
        ]);
    }

    public function test_stock_out_reduces_stock(): void
    {
        $this->actingAs($this->staff());
        $product = Product::factory()->create(['stock' => 10]);

        $this->post('/stock/out', ['product_id' => $product->id, 'quantity' => 3, 'date' => '2026-10-10'])
            ->assertRedirect(route('stock.history'));

        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_stock_out_cannot_exceed_stock(): void
    {
        $this->actingAs($this->admin());
        $product = Product::factory()->create(['stock' => 7]);

        $this->post('/stock/out', ['product_id' => $product->id, 'quantity' => 999, 'date' => '2026-10-10'])
            ->assertSessionHasErrors(['quantity' => 'Insufficient stock. Available stock: 7 units.']);

        $this->assertSame(7, $product->fresh()->stock);   // stok tidak berubah
        $this->assertDatabaseCount('stock_movements', 0); // dan tidak ada histori yang tercatat
    }

    public function test_stock_quantity_must_be_positive(): void
    {
        $this->actingAs($this->admin());
        $product = Product::factory()->create(['stock' => 7]);

        $this->post('/stock/in', ['product_id' => $product->id, 'quantity' => 0, 'date' => '2026-10-10'])
            ->assertSessionHasErrors(['quantity' => 'Quantity must be greater than zero.']);
        $this->post('/stock/out', ['product_id' => $product->id, 'quantity' => -2, 'date' => '2026-10-10'])
            ->assertSessionHasErrors('quantity');
    }

    // ---------- User ----------

    public function test_user_delete_rules(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->delete("/users/{$admin->id}")->assertSessionHas('error'); // tidak bisa hapus diri sendiri

        $withHistory = User::factory()->create();
        StockMovement::factory()->create(['user_id' => $withHistory->id]);
        $this->delete("/users/{$withHistory->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $withHistory->id]);

        $plain = User::factory()->create();
        $this->delete("/users/{$plain->id}")->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $plain->id]);
    }

    // ---------- Halaman render tanpa error ----------

    public function test_all_main_pages_render_for_admin(): void
    {
        $this->actingAs($this->admin());
        $product = Product::factory()->create();
        $supplier = Supplier::factory()->create();
        StockMovement::factory()->create(['product_id' => $product->id]);

        foreach ([
            '/dashboard', '/products', '/products/create', "/products/{$product->id}", "/products/{$product->id}/edit",
            '/categories', '/suppliers', "/suppliers/{$supplier->id}/edit", '/stock/in', '/stock/out',
            '/stock/history', '/reports', '/users',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
