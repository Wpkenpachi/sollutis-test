<?php
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use phpDocumentor\Reflection\Types\Boolean;

class StoreRepository {

    private $store;

    public function __construct()
    {
        $this->store = app(Store::class);
    }

    public function all ()
    {
        return $this->store->all();
    }

    public function show (int $id)
    {
        return $this->store->with(['products' => function ($model) use ($id) {
            $model->where('loja_id', $id)->where('ativo', true);
        }])->where('id', $id)->first();
    }

    public function getStoreEmail($id)
    {
        return $this->store->where('id', $id)->value('email');
    }

    public function create ($data)
    {
        try {
            DB::beginTransaction();
            $created = $this->store->create($data);
            DB::commit();
            return $created;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update ($data, int $id)
    {
        try {
            DB::beginTransaction();
            $updated = $this->store->where('id', $id)->update($data);
            DB::commit();
            return $this->store->find($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete (int $id)
    {
        try {
            DB::beginTransaction();
            $deleted = $this->store->where('id', $id)->delete();
            DB::commit();
            return (boolean) $deleted;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}