<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\Equipment;
use App\Models\RegisteredEquipment;

class SiteService
{
    public function getSiteData(Request $request) {
        $user_id = $request->user_id;

        $query = Site::with('user')->where('user_id', $user_id);

        return $request->has('page') 
            ? $query->paginate(10) 
            : $query->get();
    }

    public function createSiteData(Request $request) {
        Site::create([
            'active' => $request->active,
            'user_id' => $request->user_id,
            'description' => $request->description,
        ]);
    }

    public function updateSiteData(Request $request, string $id) {        
        $site = Site::find($id);
        
        $site->user_id = $request->user_id;
        $site->description = $request->description;
        $site->active = $request->active;

        $site->save();   
    }

    public function deleteSiteData(string $id) {
        $equipment_ids = RegisteredEquipment::where('site_id', $id)
            ->pluck('equipment_id')
            ->toArray();

        Equipment::whereIn('equipment_id', $equipment_ids)->delete();

        Site::destroy($id);
    }
}
