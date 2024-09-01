<?php

declare(strict_types=1);

namespace Tests;

use PDO;

class Utils
{
    public static function getPdoWithoutDatabase(): PDO
    {
        return new PDO(
            sprintf("mysql:host=%s", getenv("PROJECTS_MANAGER_DB_TEST_HOST")),
            getenv('PROJECTS_MANAGER_DB_TEST_USER'),
            getenv('PROJECTS_MANAGER_DB_TEST_PASSWORD')
        );
    }
}
