<?php

namespace App\helpers;

class ArrayHelper
{
    public static function arrayMapByField(array $array, string $field = 'id'): array
    {
        $result = [];

        foreach ($array as $item)
        {
            $result[$item[$field]] = $item;
        }

        return $result;
    }
}