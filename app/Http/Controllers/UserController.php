<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'failed',
            'msg' => 'Пользователь с таким email или паролем не найден'
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|digits_between:6,14'
        ]);

        $email = $request->email;
        $password = $request->password;

        $result = $this->userService->create($email, $password);

        return $result;
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
