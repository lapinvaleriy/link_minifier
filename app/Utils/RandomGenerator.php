<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 03.11.2018
 * Time: 11:15
 */

namespace App\Utils;


class RandomGenerator
{
    private const ALLOWED_CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CODE_LENGHT = 6;

    public static function generateSmsCode()
    {
        $charactersLength = strlen(self::ALLOWED_CHARACTERS);
        $generatedCode = '';

        for ($i = 0; $i < self::CODE_LENGHT; $i++)
            $generatedCode .= self::ALLOWED_CHARACTERS[rand(0, $charactersLength - 1)];

        return $generatedCode;
    }
}