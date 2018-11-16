<?php
/**
 * Created by PhpStorm.
 * User: lapin.v
 * Date: 16/11/2018
 * Time: 12:08
 */

namespace App\Services;


use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($email, $password)
    {
        $user = $this->userRepository->findUserByEmail($email);

        if ($user !== null) {
            return [
                'status' => 'failed',
                'msg' => 'Пользователь с таким email уже существует'
            ];
        }

        $this->userRepository->create($email, $password);

        return ['status' => 'success'];
    }
}