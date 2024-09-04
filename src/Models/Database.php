<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Models;

class Database implements ModelInterface
{
    public const TABLE_NAME = "databases";

    public readonly int $id;

    public readonly string $name;

    public readonly string $host;

    public readonly string $user;

    public readonly string $passwordHash;

    public readonly string $description;

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

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }
}
