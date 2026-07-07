<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;

use App\Services\SiteService;

class SiteController extends Controller
{
    public function __construct(protected SiteService $siteService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(SiteRequest $request)
    {
        $request->validated();

        $result = $this->siteService->getSiteData($request);

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
    public function store(SiteRequest $request)
    {
        $request->validated();

        $this->siteService->createSiteData($request);

        return response()->json([
            'message' => 'Site details is successfully created.',
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
    public function update(SiteRequest $request, string $id)
    {
        $request->validated();

        $this->siteService->updateSiteData($request, $id);

        return response()->json([
            'message' => 'Site details is successfully updated.',
            'code' => 200
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->siteService->deleteSiteData($id);

        return response()->json([
            'message' => 'Site details is successfully deleted.',
            'code' => 200
        ]); 
    }
}
