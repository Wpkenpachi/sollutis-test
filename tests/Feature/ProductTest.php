<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Store;

class ProductTest extends TestCase
{
    protected $store;

    public function testClear()
    {
        $store_id = Store::where('email', 'loja_x@teste.com')->value('id');
        Product::where('loja_id', $store_id)->delete();
        Store::where(['email' => 'loja_x@teste.com'])->delete();
        $this->assertTrue(true);
    }

    public function testCreateProduct()
    {
        $store = Store::create([
            'nome'  => 'Loja Teste X',
            'email' => 'loja_x@teste.com'
        ]);
        $response = $this->postJson('/api/product/create', ['nome' => 'Produto X', 'loja_id' => $store->id, 'valor' => 13000, 'ativo' => true]);
        $response->assertStatus(200)
                ->assertJson(['nome' => 'Produto X', 'loja_id' => $store->id, 'valor' => "R$ " . number_format(130, 2, ',', '.'), 'ativo' => true]);
    }

    public function testUpdateProduct()
    {
        $store      = Store::where('email', 'loja_x@teste.com')->first();
        $product    = Product::where('nome', 'Produto X')->first();
        $response = $this->postJson("/api/product/update/{$product->id}", ['nome' => 'Produto X2', 'loja_id' => $store->id, 'valor' => 13500, 'ativo' => true]);
        $response->assertStatus(200)
                ->assertJson(['nome' => 'Produto X2', 'loja_id' => $store->id, 'valor' => "R$ " . number_format(135, 2, ',', '.'), 'ativo' => true]);

    }

    public function testInactivateProduct()
    {
        $product = Product::where('loja_id', Store::where('email', 'loja_x@teste.com')->value('id'))->first();
        $response = $this->postJson("/api/product/inactivate/{$product->id}");
        $response->assertStatus(200)
                ->assertJson(['inactivated' => true]);
    }

    public function testActivateProduct()
    {
        $product = Product::where('loja_id', Store::where('email', 'loja_x@teste.com')->value('id'))->first();
        $response = $this->postJson("/api/product/activate/{$product->id}");
        $response->assertStatus(200)
                ->assertJson(['activated' => true]);
    }

    public function testDeleteProduct()
    {
        $product = Product::where('loja_id', Store::where('email', 'loja_x@teste.com')->value('id'))->first();
        $response = $this->postJson("/api/product/delete/{$product->id}", ['name' => 'Loja Teste', 'email' => 'loja@teste.com']);
        $response->assertStatus(200)
                ->assertJson(['deleted' => true]);
    }
}
