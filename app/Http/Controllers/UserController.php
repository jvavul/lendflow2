<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    function create(Request $request) {
        $user = new User();

        // todo add validation for existing email
        $user->email = $request['email'];
        $user->name = $request['name'];
        $user->password = Str::random(10);
        $user->save();

        $user->token = env('JWT_SECRET');

        return Response::json($user,200);
    }

    function update(Request $request, $id) {
        $user = User::find($id);

        // todo add consistent error handling
        if (!$user) {
            return Response::json(['error' => 'user not found'],400);
        }

        // todo add validation for existing email
        $user->email = $request['email'];
        $user->name = $request['name'];
        $user->save();

        $user->token = env('JWT_SECRET');

        return Response::json($user,200);
    }

    function getAll(Request $request) {
        $users = User::all();
        return Response::json($users,200);
    }

    function getById(Request $request, $id) {
        $user = User::find($id);

        // todo add consistent error handling
        if (!$user) {
            return Response::json(['error' => 'user not found'],400);
        }

        return Response::json($user,200);
    }
}
