<?php

namespace App\Http\Controllers;

use App\Http\Resources\EncomendaResource;
use App\Models\Encomenda;
use App\Http\Requests\StoreEncomendaRequest;
use App\Http\Requests\UpdateEncomendaRequest;
use Illuminate\Routing\Controllers\Middleware;

class EncomendaController extends Controller
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EncomendaResource::collection(Encomenda::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEncomendaRequest $request)
    {
        $newEncomenda = $request->user()->encomendas()->create($request->validated());

        return new EncomendaResource($newEncomenda);
    }

    /**
     * Display the specified resource.
     */
    public function show(Encomenda $encomenda)
    {
        return new EncomendaResource($encomenda);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEncomendaRequest $request, Encomenda $encomenda)
    {
        $encomenda->update($request->validated());
        return new EncomendaResource($encomenda);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encomenda $encomenda)
    {
        $encomenda->delete();

        return response()->json([
            'message' => 'Encomenda apagada com sucesso.'
        ]);
    }
}
