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

        $filtered_add_items = [];

        foreach ($check_status as $key => $value) {
            if($value) {
                array_push($add_items, $equipment_ids[$key]);
            } else {
                array_push($delete_items, $equipment_ids[$key]);
            }
        } 

        
        $existingRecords = RegisteredEquipment::where('site_id', $site_id)->whereIn('equipment_id', $add_items)
        ->get();

        foreach ($existingRecords as $key => $value) {
            $equipment_id_exists = array_search($value['equipment_id'], $add_items);

            if($equipment_id_exists !== false) {
                unset($add_items[$equipment_id_exists]);
            }
        } 

        foreach($add_items as $key => $value) {
            array_push($filtered_add_items, ['equipment_id' => $value, 'site_id' => $site_id]);
        }

        if(count($filtered_add_items) > 0) {
            RegisteredEquipment::insert($filtered_add_items);
        }

        RegisteredEquipment::where('site_id', $site_id)
        ->whereIn('equipment_id', $delete_items)
        ->delete();
    }   
}
