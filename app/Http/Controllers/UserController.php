<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function registrationPage(Request $request)
    {
        $request->session()->forget('token');
        return view('register');
    }

    public function loginPage(Request $request)
    {
        $request->session()->forget('token');
        return view('login');
    }

    public function newUser(Request $request, Users $user)
    {
        $user->createNewUser($request->username, $request->password);

        return response(null, 200);
    }

    public function login(Request $request, Users $user)
    {
        if ($user->getUserToken($request->username, $request->password)) {
            $request->session()->put('token', $user->getUserToken($request->username, $request->password));
            return redirect('/');
        } else {
            return response('Incorrect username of password', 400);
        }
    }

    public function generateNewToken(Request $request, Users $user)
    {
        $newToken = $user->generateNewToken($request->token);
        $request->session()->put('token', $newToken);

        return $newToken;
    }
}
