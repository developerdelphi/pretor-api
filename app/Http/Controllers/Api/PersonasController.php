<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonaRequest;
use App\Http\Resources\PersonaResource;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PersonasController extends Controller
{
    private $persona;

    public function __construct(Persona $persona)
    {
        $this->persona = $persona;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $personas = new Persona;
            $search = [];
            $search = Arr::add($search, 'name', $request->has('name') ? $request->get('name') : null);
            $search = Arr::add($search, 'order', $request->has('order') ? $request->get('order') : 'name');
            $search = Arr::add($search, 'order_type', $request->has('order_type') ? $request->get('order_type') : 'ASC');
            $search = Arr::add($search, 'per_page', $request->has('per_page') ? $request->get('per_page') : 50);

            if ($search['name'] != null) {
                $personas = $personas->where('name', 'like', '%' . $search['name'] . '%');
            }
            if ($search['order_type'] === 'DESC')
                $personas = $personas->orderByDesc($search['order']);
            else
                $personas = $personas->orderBy($search['order']);

            $personas = $personas->minSelect()->paginate($search['per_page'])->appends($request->query());

            return new PersonaResource($personas);
        } catch (\Exception $e) {
            $messages = new ApiMessages($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonaRequest $request)
    {
        try {
            $this->persona->create($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Cadastro realizado.'
                ]
            ], 201);
        } catch (\Exception $e) {
            $messages = new ApiMessages($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        try {
            //$persona = $this->persona->minSelect()->get();
            return new PersonaResource($persona);
        } catch (\Exception $e) {
            $messages = new ApiMessages($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonaRequest $request, Persona $persona)
    {
        try {
            $persona->update($request->all());
            return response()->json([
                'data' => [
                    'message' => 'Registro atualizado.'
                ]
            ], 200);
        } catch (\Exception $e) {
            $messages = new ApiMessages($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        try {
            $persona->delete();

            return response()->json([
                'data' => [
                    'message' => 'Registro removido do sistema.'
                ]
            ], 200);
        } catch (\Exception $e) {
            $messages = new ApiMessages($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }
}
