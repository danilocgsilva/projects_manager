<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Models;


class ExecutionEnvironment implements ModelInterface
{
    private const TABLE_NAME = "execution_environment";
    
    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}
