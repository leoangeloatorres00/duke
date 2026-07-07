<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\Equipment;
use App\Models\RegisteredEquipment;

class EquipmentService
{
    public const string EQUIPMENT_INDEX_ROUTE = 'equipment.index';

    public function getEquipmentData(Request $request) {
        $user_id = $request->user_id;

        $query = Equipment::with(['registeredEquipment', 'user'])->where('user_id',  $user_id);

        return $request->route()->getName() === EquipmentService::EQUIPMENT_INDEX_ROUTE
            ? $query->paginate(10) 
            : $query->get();
    }

    public function createEquipmentData(Request $request) {
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

    public function updateEquipmentData(Request $request, string $id) {
        $equipment = Equipment::find($id);
        
        $equipment->serial_number = $request->serial_number;
        $equipment->description = $request->description;
        $equipment->condition = $request->condition;
        $equipment->save();

        RegisteredEquipment::where('equipment_id', $id)
            ->update(['site_id' => $request->site_id]);    
    }

    public function deleteEquipmentData(string $id) {
        Equipment::destroy($id);
    }
}
