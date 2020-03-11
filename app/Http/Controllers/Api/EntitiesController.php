<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Arr;

class EntitiesController extends Controller
{
    private $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $entities = new Entity;
            $search = [];
            $search = Arr::add($search, 'name', $request->has('name') ? $request->get('name') : null);
            $search = Arr::add($search, 'order', $request->has('order') ? $request->get('order') : 'name');
            $search = Arr::add($search, 'order_type', $request->has('order_type') ? $request->get('order_type') : 'ASC');
            $search = Arr::add($search, 'per_page', $request->has('per_page') ? $request->get('per_page') : 50);

            if ($search['name'] != null) {
                $entities = $entities->where('name', 'like', '%' . $search['name'] . '%');
            }
            if ($search['order_type'] === 'DESC')
                $entities = $entities->orderByDesc($search['order']);
            else
                $entities = $entities->orderBy($search['order']);

            $entities = $entities->minSelect()->paginate($search['per_page'])->appends($request->query());

            return new EntityResource($entities);
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
    public function store(EntityRequest $request)
    {
        try {
            $this->entity->create($request->all());

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
    public function show(Entity $entity)
    {
        try {
            return new EntityResource($entity);
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
    public function update(EntityRequest $request, Entity $entity)
    {
        try {
            $entity->update($request->all());
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
    public function destroy(Entity $entity)
    {
        try {
            $entity->delete();

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
