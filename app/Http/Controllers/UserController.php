<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;

use Illuminate\Http\Request;

use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(UserRequest $request)
    {
        $request->validated();

        $result = $this->userService->getUserData($request);

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
    public function store(Request $request)
    {
        //
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
    public function update(UserRequest $request, string $id)
    {
        $request->validated();

        $this->userService->updateUserData($request, $id);

        return response()->json([
            'message' => 'Users details is successfully updated.',
            'code' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->deleteUserData($id);

        return response()->json([
            'message' => 'Users details is successfully deleted.',
            'code' => 200
        ]);
    }
}
