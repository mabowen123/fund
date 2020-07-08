<?php


namespace App\Cache;


class Name
{
    const FUND = 'fund';

    public static function fund(string $fundId)
    {
        return self::FUND . ":$fundId";
    }
}
