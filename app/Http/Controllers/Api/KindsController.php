<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\KindRequest;
use App\Http\Resources\KindResource;
use App\Models\Kind;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class KindsController extends Controller
{
    private $kind;

    public function __construct(Kind $kind)
    {
        $this->kind = $kind;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $kinds = new Kind;
            $search = [];
            $search = Arr::add($search, 'name', $request->has('name') ? $request->get('name') : null);
            $search = Arr::add($search, 'order', $request->has('order') ? $request->get('order') : 'name');
            $search = Arr::add($search, 'order_type', $request->has('order_type') ? $request->get('order_type') : 'ASC');
            $search = Arr::add($search, 'per_page', $request->has('per_page') ? $request->get('per_page') : 50);

            if ($search['name'] != null) {
                $kinds = $kinds->where('name', 'like', '%' . $search['name'] . '%');
            }
            if ($search['order_type'] === 'DESC')
                $kinds = $kinds->orderByDesc($search['order']);
            else
                $kinds = $kinds->orderBy($search['order']);

            $kinds = $kinds->select('id', 'name', 'area_id')
                ->with(['area' => function ($query) {
                    $query->select('id', 'name', 'origin');
                }])
                ->paginate($search['per_page'])
                ->appends($request->query());

            return new KindResource($kinds);
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
    public function store(KindRequest $request)
    {
        try {
            $this->kind->create($request->all());

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
    public function show(Kind $kind)
    {
        try {
            return new KindResource($kind);
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
    public function update(KindRequest $request, Kind $kind)
    {
        try {
            $kind->update($request->all());
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
    public function destroy(Kind $kind)
    {
        try {
            $kind->delete();

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
