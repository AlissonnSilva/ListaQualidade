<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_list_contains_the_expected_heading_and_saved_product(): void
    {
        Product::create($this->productData(['name' => 'Bolo de teste']));

        $this->get('/produtos')
            ->assertOk()
            ->assertSee('Produtos e estoque')
            ->assertSee('Bolo de teste');
    }

    public function test_product_can_be_created_and_stored_in_the_database(): void
    {
        $this->postWithCsrf('/produtos', $this->productData())
            ->assertRedirect('/produtos')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', ['name' => 'Produto de teste', 'category' => 'Bolos']);
    }

    public function test_product_name_and_price_are_validated(): void
    {
        $this->from('/produtos/create')
            ->postWithCsrf('/produtos', $this->productData(['name' => '', 'price' => -10]))
            ->assertRedirect('/produtos/create')
            ->assertSessionHasErrors(['name', 'price']);
    }

    public function test_product_can_be_updated_and_deleted(): void
    {
        $product = Product::create($this->productData());

        $this->putWithCsrf("/produtos/{$product->id}", $this->productData(['price' => 49.90]))
            ->assertRedirect('/produtos')
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'price' => '49.90']);

        $this->deleteWithCsrf("/produtos/{$product->id}")->assertRedirect('/produtos');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_customer_can_be_created_and_deleted(): void
    {
        $customerData = [
            'name' => 'Cliente de teste',
            'email' => 'cliente@example.com',
            'phone' => '11999990000',
            'address' => 'Rua dos Testes, 10',
            'city' => 'São Paulo',
        ];

        $this->postWithCsrf('/clientes', $customerData)->assertRedirect('/clientes');
        $this->assertDatabaseHas('customers', ['email' => 'cliente@example.com']);

        $customer = Customer::where('email', 'cliente@example.com')->firstOrFail();
        $this->deleteWithCsrf("/clientes/{$customer->id}")->assertRedirect('/clientes');
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    /** @return array<string, mixed> */
    private function productData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Produto de teste',
            'description' => 'Descrição de teste',
            'category' => 'Bolos',
            'price' => 25.50,
            'stock' => 10,
            'minimum_stock' => 2,
            'active' => 1,
        ], $overrides);
    }
}
