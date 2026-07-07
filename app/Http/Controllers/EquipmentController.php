<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;

use App\Services\EquipmentService;

class EquipmentController extends Controller
{
    public function __construct(protected EquipmentService $equipmentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(EquipmentRequest $request)
    {
        $request->validated();

        $result = $this->equipmentService->getEquipmentData($request);

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        $request->validated();

        $this->equipmentService->createEquipmentData($request);

        return response()->json([
            'message' => 'Equipment details is successfully created.',
            'code' => 200
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentRequest $request, string $id)
    {
        $request->validated();

        $this->equipmentService->updateEquipmentData($request, $id);

        return response()->json([
            'message' => 'Equipment details is successfully updated.',
            'code' => 200
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->equipmentService->deleteEquipmentData($id);

        return response()->json([
            'message' => 'Equipment details is successfully deleted.',
            'code' => 200
        ]); 
    }
}
