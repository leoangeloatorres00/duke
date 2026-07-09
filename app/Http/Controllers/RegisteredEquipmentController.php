<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisteredEquipmentRequest;

use App\Services\RegisteredEquipmentService;

class RegisteredEquipmentController extends Controller
{
    public function __construct(protected RegisteredEquipmentService $registeredEquipmentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(RegisteredEquipmentRequest $request)
    {
        $request->validated();

        $result = $this->registeredEquipmentService->getRegisteredEquipmentData($request);

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     */
    public function store(RegisteredEquipmentRequest $request)
    {
        $request->validated();

        $this->registeredEquipmentService->updateRegisteredEquipmentData($request);

        return response()->json([
            'message' => 'Registered equipment details is successfully updated.',
            'code' => 200
        ]); 
    }
}
