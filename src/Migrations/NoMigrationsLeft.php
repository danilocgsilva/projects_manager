<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations;

use Exception;

class NoMigrationsLeft extends Exception
{
    public function __construct(
        string $message = "No migrations left;", 
        int $code = 0, 
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
