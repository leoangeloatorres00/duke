<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\RegisteredEquipment;

class RegisteredEquipmentService
{
    public function getRegisteredEquipmentData(Request $request) {
        $site_id = $request->site_id;

        return RegisteredEquipment::select(['equipment_id', 'site_id'])
            ->selectRaw("(site_id = ?) as is_own", [$site_id])
            ->selectRaw("(site_id IS NOT NULL) as is_taken")
            ->get();
    }

    public function updateRegisteredEquipmentData(Request $request) {        
        $site_id = $request->site_id;
        $check_status = $request->check_status;
        $equipment_ids = $request->equipment_ids;

        $add_items = [];
        $delete_items = [];

        foreach ($check_status as $key => $value) {
            if($value) {
                array_push($add_items, ['equipment_id' => $equipment_ids[$key], 'site_id' => $site_id]);
            } else {
                array_push($delete_items, $equipment_ids[$key]);
            }
        }

        RegisteredEquipment::insert($add_items);

        RegisteredEquipment::whereIn('equipment_id', $delete_items)
            ->where('site_id', $site_id)
            ->delete();
    }   
}
