<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        
    }

    public function save(Request $request)
    {
        try {
            $id = $request->id;
            $user = User::firstOrNew(["id" => $id]);       
            $user->fill($request->all());
            $user->password = bcrypt($user->password);
            $user->save();
            return response()->json(new Collection($user), 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
        
    }
}
