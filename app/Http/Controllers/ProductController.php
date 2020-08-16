<?php

namespace App\Http\Controllers;

use App\Mail\ProductSuccessfullyCreated;
use App\Mail\ProductSuccessfullyUpdated;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Repositories\StoreRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    private $product;
    private $store;

    public function __construct()
    {
        $this->product  = new ProductRepository;
        $this->store    = new StoreRepository;
    }

    public function index()
    {
        return $this->product->all();
    }

    public function show(int $id)
    {
        return $this->product->show($id);
    }

    public function store(Request $request)
    {
        $messages = [
            'required'  => 'Campo :attribute é obrigatório.',
            'integer'   => 'Campo :attribute deve ser do tipo integer',
            'string'    => 'Campo :attribute deve ser do tipo string.',
            'boolean'   => 'Campo :attribute deve ser do tipo boolean',
            'max'       => 'Campo :attribute deve ter no máximo :max caracteres.',
            'min'       => 'Campo :attribute deve ter no mínimo :min caracteres.',
            'exists'    => 'Esta loja não inexistente.'
        ];

        $validation = Validator::make($request->all(), [
            'loja_id'   => 'required|numeric|exists:stores,id',
            'nome'      => 'required|string|max:60|min:3',
            'valor'     => 'required|numeric|between:0,999999',
            'ativo'     => 'required|boolean'
        ], $messages);

        if ($validation->fails()) {
            return $validation->errors();
        }

        $created = $this->product->create($request->all());
        $email = $this->store->getStoreEmail($request->loja_id);
        try {
            Mail::to($email)->send(new ProductSuccessfullyCreated($created));
        } catch (\Throwable $th) {}
        return response()->json($created);
    }

    public function update(Request $request, int $id)
    {
        $messages = [
            'required'  => 'Campo :attribute é obrigatório.',
            'integer'   => 'Campo :attribute deve ser do tipo integer',
            'string'    => 'Campo :attribute deve ser do tipo string.',
            'boolean'   => 'Campo :attribute deve ser do tipo boolean',
            'max'       => 'Campo :attribute deve ter no máximo :max caracteres.',
            'min'       => 'Campo :attribute deve ter no mínimo :min caracteres.',
            'exists'    => 'Esta loja não inexistente.'
        ];

        $validation = Validator::make($request->all(), [
            'loja_id'   => 'numeric|exists:stores,id',
            'nome'      => 'string|max:60|min:3',
            'valor'     => 'numeric|between:0,999999',
            'ativo'     => 'boolean'
        ], $messages);

        if ($validation->fails()) {
            return $validation->errors();
        }
        $updated = $this->product->update($request->all(), $id);
        $email = $this->store->getStoreEmail($request->loja_id);
        try {
            Mail::to($email)->send(new ProductSuccessfullyUpdated($updated));
        } catch (\Throwable $th) {}
        return response()->json($updated);
    }

    public function activate(int $id)
    {
        $activated =  $this->product->activate($id);
        return response()->json(['activated' => $activated]);
    }

    public function inactivate(int $id)
    {
        $inactivated = $this->product->inactivate($id);
        return response()->json(['inactivated' => $inactivated]);
    }

    public function delete(int $id)
    {
        $deleted = $this->product->delete($id);
        return response()->json(['deleted' => $deleted]);
    }
}
