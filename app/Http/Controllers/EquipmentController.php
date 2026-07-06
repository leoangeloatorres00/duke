<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Equipment;
use App\Models\RegisteredEquipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authId = $request->user_id;
        return response()->json(Equipment::where('user_id',  $authId)->paginate(10));
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
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255|unique:equipment,description',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number',
            'condition' => 'required|string|in:Working,Not Working',
            'site_id' => 'required|exists:site,site_id'
        ], [
            'description.unique' => 'This equipment has already been taken.', 
            'serial_number.unique' => 'This serial number must be unique per equipment.', 
        ]);

        $equipment = Equipment::create([
            'serial_number' => $request->serial_number,
            'description' => $request->description,
            'condition' => $request->condition,
            'user_id' => $request->user_id   
        ]);

        RegisteredEquipment::create([
            'equipment_id' => $equipment->equipment_id,
            'site_id' => $request->site_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Equipment::with('registeredEquipment')->find($id));
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'description' => [
                'required',
                'string',
                Rule::unique('equipment', 'description')->ignore($id, 'equipment_id')
            ],
            'serial_number' => [
                'required',
                'string',
                Rule::unique('equipment', 'serial_number')->ignore($id, 'equipment_id')
            ],
            'condition' => 'required|string|in:Working,Not Working',
            'site_id' => 'required|exists:site,site_id'
        ], [
            'description.unique' => 'This equipment has already been taken.', 
            'serial_number.unique' => 'This serial number must be unique per equipment.', 
        ]);

        $equipment = Equipment::find($id);
        
        $equipment->serial_number = $request->serial_number;
        $equipment->description = $request->description;
        $equipment->condition = $request->condition;
        $equipment->save();

        RegisteredEquipment::where('equipment_id', $id)
            ->update(['site_id' => $request->site_id]);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Equipment::destroy($id);
    }
}
