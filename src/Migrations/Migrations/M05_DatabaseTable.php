<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ProjectsManager\Models\Database;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\ProjectsManager\Models\Project;

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
        $removeForeignKeyScript = sprintf("DROP CONSTRAINT %s", "project_id_id_constraint");
        $removeTableScript = sprintf("DROP TABLE %s;", Database::getTableName());

        return $removeForeignKeyScript . PHP_EOL . $removeTableScript;
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
                (new FieldScriptSpitter("name"))
                    ->setType("VARCHAR(192)")
                    ->setNotNull()
            )
            ->addField(
                (new FieldScriptSpitter("user"))
                    ->setType("VARCHAR(192)")
            )
            ->addField(
                (new FieldScriptSpitter("passwordHash"))
                    ->setType("TEXT")
            )
            ->addField(
                (new FieldScriptSpitter("description"))
                    ->setType("VARCHAR(192)")
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
        return (new ForeignKeyScriptSpitter())
            ->setTable(Database::TABLE_NAME)
            ->setConstraintName("project_id_id_constraint")
            ->setForeignKey("project_id")
            ->setForeignTable(Project::TABLE_NAME)
            ->setTableForeignkey("id")
            ->getScript();
    }
}
