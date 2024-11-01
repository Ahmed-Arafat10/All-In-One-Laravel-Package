<?php

namespace AhmedArafat\AllInOne\Exceptions;

use AhmedArafat\AllInOne\Traits\ApiResponser;
use Exception;

class ExceptionWithApiResponser extends Exception
{
    use ApiResponser;
}
