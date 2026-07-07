<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\User;

class UserService
{
    public const string USER_INDEX_ROUTE = 'user.index';

    public function getUserData(Request $request) {
        $user_id = $request->user_id;

        $query = User::where('user_id', '!=', $user_id);

        return $request->route()->getName() === UserService::USER_INDEX_ROUTE 
            ? $query->paginate(10) 
            : $query->get();
    }

    public function updateUserData(Request $request, string $id) {
        $query = User::find($id);

        $query->user_type = $request->user_type;
        $query->last_name = $request->last_name;
        $query->first_name = $request->first_name;
        
        $query->save();
    }

    public function deleteUserData(string $id) {
        User::destroy($id);
    }
}
