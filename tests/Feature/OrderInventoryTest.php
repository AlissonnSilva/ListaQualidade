<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_order_saves_the_price_and_decreases_stock(): void
    {
        $customer = Customer::create(['name' => 'Cliente do pedido']);
        $product = Product::create([
            'name' => 'Bolo para pedido',
            'category' => 'Bolos',
            'price' => 12.50,
            'stock' => 10,
            'minimum_stock' => 2,
            'active' => true,
        ]);

        $this->postWithCsrf('/pedidos', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'status' => 'pending',
        ])->assertRedirect('/pedidos')->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 7]);
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'unit_price' => '12.50',
        ]);
    }

    public function test_order_larger_than_stock_is_rejected_without_creating_an_order(): void
    {
        $customer = Customer::create(['name' => 'Cliente sem estoque']);
        $product = Product::create([
            'name' => 'Produto com pouco estoque',
            'category' => 'Doces',
            'price' => 5,
            'stock' => 2,
            'minimum_stock' => 1,
            'active' => true,
        ]);

        $this->from('/pedidos/create')
            ->postWithCsrf('/pedidos', [
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'quantity' => 3,
                'status' => 'pending',
            ])
            ->assertRedirect('/pedidos/create')
            ->assertSessionHasErrors('quantity');

        $this->assertSame(0, Order::count());
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);
    }
}
