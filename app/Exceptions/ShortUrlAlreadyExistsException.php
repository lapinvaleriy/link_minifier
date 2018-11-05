<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 15:28
 */

namespace App\Exceptions;


use Exception;

class ShortUrlAlreadyExistsException extends UrlMinifierException
{
    protected $message = "Такая короткая ссылка уже сущствует. Выберите другую";
}