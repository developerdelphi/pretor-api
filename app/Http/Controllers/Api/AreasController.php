<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AreasController extends Controller
{

    private $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $areas = new Area;
            $search = [];
            $search = Arr::add($search, 'name', $request->has('name') ? $request->get('name') : null);
            $search = Arr::add($search, 'order', $request->has('order') ? $request->get('order') : 'name');
            $search = Arr::add($search, 'order_type', $request->has('order_type') ? $request->get('order_type') : 'ASC');
            $search = Arr::add($search, 'per_page', $request->has('per_page') ? $request->get('per_page') : 50);

            if ($search['name'] != null) {
                $areas = $areas->where('name', 'like', '%' . $search['name'] . '%');
            }
            if ($search['order_type'] === 'DESC')
                $areas = $areas->orderByDesc($search['order']);
            else
                $areas = $areas->orderBy($search['order']);

            $areas = $areas->select('id', 'name', 'origin')->paginate($search['per_page'])->appends($request->query());

            return new AreaResource($areas);
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
    public function store(AreaRequest $request)
    {
        try {
            $this->area->create($request->all());

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
    public function show($id)
    {
        try {
            $area = $this->area->find($id);
            if ($area) return new AreaResource($area);
            else return response()->json([
                'error' => 'Não foi possível localizar o registro.'
            ], 400);
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
    public function update(AreaRequest $request, Area $area)
    {
        try {
            //$area = $this->area->findOrFail($id);
            $area->update($request->all());
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
    public function destroy(Area $area)
    {
        try {
            // $area = $this->area->findOrFail($id);
            $area->delete();

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
