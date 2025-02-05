<?php

namespace AhmedArafat\AllInOne\Traits;

use Exception;

trait GenericHelperTrait
{
    /**
     * @param $bytes
     * @return string
     */
    public function convertBytes($bytes): string
    {
        $megabytes = $bytes / 1048576;
        return number_format($megabytes, 2) . ' MB';
    }

    /**
     * @param $path
     * @param $img
     * @return string|null
     */
    public function appendImgPath($path, $img): ?string
    {
        if (!$img) return null;
        return asset($path . $img);
    }
}
