<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;
use Exception;

class M01_CreateDatabase implements MigrationInterface
{
    private string $databaseName;

    private CONST FIRST_MIGRATION = true;

    private CONST DESCRIPTION = "Create database. This migrations requires to receive a database name " . 
        "with the method setDatabaseName. So it is possible to determine the database name for creation.";

    public function setDatabaseName(string $databaseName): self
    {
        $this->databaseName = $databaseName;
        return $this;
    }

    public function getScript(): string
    {
        if (!isset($this->databaseName)) {
            throw new Exception("You first need to set a database name for the first migration.");
        }
        return (new DatabaseScriptSpitter($this->databaseName))
            ->setUseSelf()
            ->getScript();
    }

    public function getRollbackScript(): string
    {
        return sprintf("DROP DATABASE %s;", $this->databaseName);
    }

    public function isFirstMigration(): bool
    {
        return self::FIRST_MIGRATION;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }
}
