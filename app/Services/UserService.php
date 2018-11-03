<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 03.11.2018
 * Time: 19:14
 */

namespace App\Services;


use App\Repositories\LinkRepository;
use App\Repositories\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
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