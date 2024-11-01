<?php

namespace AhmedArafat\AllInOne\Traits;

use Exception;

trait GenericHelperTrait
{
    public function convertBytes($bytes)
    {
        $megabytes = $bytes / 1048576;
        return number_format($megabytes, 2) . ' MB';
    }
}
