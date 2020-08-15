<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constant\ErrorConstant;

class StructureException extends CustomException
{
    public function __construct()
    {
        $error['errors'][] = ErrorConstant::getErrorByCode(1);

        parent::__construct(\json_encode($error));
    }
}
