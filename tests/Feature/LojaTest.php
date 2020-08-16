<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Store;
use Tests\TestCase;

class LojaTest extends TestCase
{
    public function testClear()
    {
        Store::where('email', 'loja@teste.com')->delete();
        $this->assertTrue(true);
    }

    public function testCreateStore()
    {
        $response = $this->postJson('/api/store/create', ['nome' => 'Loja Teste', 'email' => 'loja@teste.com']);
        $response->assertStatus(201)
                ->assertJson(['nome' => 'Loja Teste', 'email' => 'loja@teste.com']);
    }

    public function testUpdateStore()
    {
        $store = Store::where('email', 'loja@teste.com')->first();
        $response = $this->postJson("/api/store/update/{$store->id}", ['nome' => 'Loja Teste Updated']);
        $response->assertStatus(200)
                ->assertJson(['nome' => 'Loja Teste Updated']);
    }

    public function testDeleteStore()
    {
        $store = Store::where('email', 'loja@teste.com')->first();
        $response = $this->postJson("/api/store/delete/{$store->id}", []);
        $response->assertStatus(200)
                ->assertJson(['deleted' => true]);
    }
}
