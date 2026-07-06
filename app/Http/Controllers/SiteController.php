<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RegisteredEquipment;
use App\Models\Equipment;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authId = $request->user_id;
        return response()->json(Site::where('user_id', $authId)->paginate(10));
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
            'description' => 'required|string|max:255|unique:site,description',
            'active' => 'required|integer'
        ], [
            'description.unique' => 'This site name has already been taken.', 
        ]);

        Site::create([
            'user_id' => $request->user_id,
            'description' => $request->description,
            'active' => $request->active   
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Site::find($id));
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
                Rule::unique('site', 'description')->ignore($id, 'site_id')
            ],
            'active' => 'required|integer'
        ], [
            'description.unique' => 'This site name has already been taken.', 
        ]);

        $site = Site::find($id);

        $site->user_id = $request->user_id;
        $site->description = $request->description;
        $site->active = $request->active;
        $site->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipmentIds = RegisteredEquipment::where('site_id', $id)
            ->pluck('equipment_id')
            ->toArray();

        Equipment::whereIn('equipment_id', $equipmentIds)->delete();

        Site::destroy($id);
    }
}
