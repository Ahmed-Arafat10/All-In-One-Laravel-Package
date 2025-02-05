<?php

namespace AhmedArafat\AllInOne\Helpers;

class GenericHelper
{
    /**
     * @param $path
     * @param null $img
     * @param null $default
     * @return string
     */
    public static function getImage($path, $img = null, $default = null): string
    {
        return $img === null
            ? asset($default ?? '/uploads/NA.jpg')
            : asset($path . $img);
    }
}
