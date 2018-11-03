<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 03.11.2018
 * Time: 19:17
 */

namespace App\Repositories;


use App\Models\User;
use Hash;

class UserRepository
{
    public function create($email, $password)
    {
        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();
    }

    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}