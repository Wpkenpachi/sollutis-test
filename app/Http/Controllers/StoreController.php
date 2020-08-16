<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StoreRepository;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    private $store;
    private $product;

    public function __construct()
    {
        $this->store    = new StoreRepository;
    }

    public function index(StoreRepository $store)
    {
        return $store->all();
    }

    public function show(int $id)
    {
        return $this->store->show($id);
    }

    public function store(Request $request)
    {
        $messages = [
            'required'  => 'Campo :attribute é obrigatório.',
            'string'    => 'Campo :attribute tem de ser do tipo string.',
            'email'     => 'Campo :attribute inválido',
            'unique'    => 'Campo :attribute tem de ser único.',
            'max'       => 'Campo :attribute pode ter no máximo :max caracteres.',
            'min'       => 'Campo :attribute tem de ter no mínimo :min caracteres.'
        ];

        $validation = Validator::make($request->all(), [
            'nome' => 'required|string|max:40|min:3',
            'email' => 'required|email|unique:stores'
        ], $messages);

        if ($validation->fails()) {
            return $validation->errors();
        }

        $new_store = $this->store->create($request->all());
        return $new_store;
    }

    public function update(Request $request, int $id)
    {
        $messages = [
            'string'    => 'Campo :attribute tem de ser do tipo string.',
            'email'     => 'Campo :attribute inválido',
            'unique'    => 'Campo :attribute tem de ser único.',
            'max'       => 'Campo :attribute pode ter no máximo :max caracteres.',
            'min'       => 'Campo :attribute tem de ter no mínimo :min caracteres.'
        ];

        $validation = Validator::make($request->all(), [
            'nome' => 'string|max:40|min:3',
            'email' => 'email'
        ], $messages);

        if ($validation->fails()) {
            return $validation->errors();
        }

        $updated = $this->store->update($request->all(), $id);
        return $updated;
    }

    public function delete(int $id)
    {
        $deleted = $this->store->delete($id);
        return response()->json(['deleted' => $deleted]);
    }
}
