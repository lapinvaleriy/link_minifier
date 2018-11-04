<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 15:28
 */

namespace App\Exceptions;


use Exception;

class ShortUrlAlreadyExistsException extends Exception
{
    protected $message = "Уже есть";
}