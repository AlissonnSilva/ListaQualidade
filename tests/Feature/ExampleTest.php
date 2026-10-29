<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_success_and_displays_the_application_name(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Doce Gestão');
    }

    public function test_product_customer_order_and_delivery_lists_return_success(): void
    {
        foreach (['/produtos', '/clientes', '/pedidos', '/entregas'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_unknown_route_returns_not_found(): void
    {
        $this->get('/rota-inexistente')->assertNotFound();
    }
}
