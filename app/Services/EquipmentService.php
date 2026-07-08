<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\Equipment;

class EquipmentService
{
    public function getEquipmentData(Request $request) {
        $user_id = $request->user_id;

        $query = Equipment::with(['registeredEquipment', 'user'])->where('user_id',  $user_id);

        return $request->has('page')
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
    }

    public function updateEquipmentData(Request $request, string $id) {
        $equipment = Equipment::find($id);
        
        $equipment->serial_number = $request->serial_number;
        $equipment->description = $request->description;
        $equipment->condition = $request->condition;
        $equipment->save();
    
    }

    public function deleteEquipmentData(string $id) {
        Equipment::destroy($id);
    }
}
