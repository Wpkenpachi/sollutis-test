<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Store;

class ProductRepository {
    private $product;

    public function __construct()
    {
        $this->product = app(Product::class);
    }

    public function all ()
    {
        return $this->product->all();
    }

    public function getProductObject ($data)
    {
        return new Product($data);
    }

    // Is not in use
    public function list ()
    {
        return $this->product->where('ativo', true)->get();
    }

    public function show (int $id)
    {
        return $this->product->where('id', $id)->where('ativo', true)->first();
    }

    public function create ($data)
    {
        try {
            DB::beginTransaction();
            $created = $this->product->create($data);
            DB::commit();
            return $created->toArray();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update ($data, int $id)
    {
        try {
            DB::beginTransaction();
            $updated = $this->product->where('id', $id)->update($data);
            DB::commit();
            return $this->product->find($id);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function inactivate (int $id)
    {
        try {
            DB::beginTransaction();
            $this->product->where('id', $id)->update(['ativo' => false]);
            DB::commit();
            return (boolean) $this->product->where('ativo', false)->where('id', $id)->count();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function activate (int $id)
    {
        try {
            DB::beginTransaction();
            $this->product->where('id', $id)->update(['ativo' => true]);
            DB::commit();
            return (boolean) $this->product->where('ativo', true)->where('id', $id)->count();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete (int $id)
    {
        try {
            DB::beginTransaction();
            $deleted = $this->product->where('id', $id)->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}