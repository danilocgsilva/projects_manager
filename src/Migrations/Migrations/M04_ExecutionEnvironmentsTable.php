<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ProjectsManager\Models\ExecutionEnvironment;

class M04_ExecutionEnvironmentsTable implements MigrationInterface
{
    private CONST DESCRIPTION = "Creates the table for execution environments. Means for instance, a development" .
        " machine for a project. One project may ocasionally have several development machines";

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function isFirstMigration(): bool
    {
        return false;
    }

    public function getScript(): string
    {
        return (new TableScriptSpitter(ExecutionEnvironment::getTableName()))
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
            ->getScript();
    }

    public function getRollbackScript(): string
    {
        return sprintf("DROP TABLE %s;", ExecutionEnvironment::getTableName());
    }
}
