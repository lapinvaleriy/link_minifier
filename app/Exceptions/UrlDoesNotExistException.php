<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 15:20
 */

namespace App\Exceptions;


use Exception;

class UrlDoesNotExistException extends UrlMinifierException
{
    protected $message = "Такого адреса не существует";
}