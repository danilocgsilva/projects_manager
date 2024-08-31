<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Models;

interface ModelInterface
{
    public static function getTableName(): string;
}
