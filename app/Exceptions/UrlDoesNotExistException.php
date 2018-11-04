<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 15:20
 */

namespace App\Exceptions;


use Exception;

class UrlDoesNotExistException extends Exception
{
    protected $message = "Такого url не существует";
}