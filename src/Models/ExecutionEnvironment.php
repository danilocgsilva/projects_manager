<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Models;


class ExecutionEnvironment implements ModelInterface
{
    public const TABLE_NAME = "execution_environments";
    
    public readonly int $id;

    public readonly string $name;

    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
