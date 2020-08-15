<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class CustomException extends Exception
{
    public function __construct($errors)
    {
        parent::__construct($errors);
    }
}
