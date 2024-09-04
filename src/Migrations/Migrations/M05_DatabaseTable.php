<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ProjectsManager\Models\Database;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class M05_DatabaseTable implements MigrationInterface
{
    private const DESCRIPTION = "Holds data for database access. Also allows the system make a database backup.";

    public function getScript(): string
    {
        $databaseScript = $this->getDatabaseScript();
        $foreigKeyScript = $this->getForeignKeyScript();

        return $databaseScript . PHP_EOL . $foreigKeyScript;
    }

    public function getRollbackScript(): string
    {
        return sprintf("DROP TABLE %s;", Database::getTableName());
    }

    public function isFirstMigration(): bool
    {
        return false;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    private function getDatabaseScript(): string
    {
        return (new TableScriptSpitter(Database::TABLE_NAME))
            ->addField(
                (new FieldScriptSpitter("id"))
                    ->setAutoIncrement()
                    ->setPrimaryKey()
                    ->setNotNull()
                    ->setUnsigned()
                    ->setType("INT")
            )
            ->addField(
                (new FieldScriptSpitter("name"))->setType("VARCHAR(192)")
            )
            ->addField(
                (new FieldScriptSpitter("host"))->setType("VARCHAR(192)")
            )
            ->addField(
                (new FieldScriptSpitter("user"))->setType("VARCHAR(192)")
            )
            ->addField(
                (new FieldScriptSpitter("passwordHash"))->setType("TEXT")
            )
            ->addField(
                (new FieldScriptSpitter("description"))->setType("VARCHAR(192)")
            )
            ->addField(
                (new FieldScriptSpitter("project_id"))
                    ->setType("INT")
                    ->setUnsigned()
            )
            ->getScript();
    }

    private function getForeignKeyScript(): string
    {
        return "";
    }
}
