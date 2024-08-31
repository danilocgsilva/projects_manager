<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;

use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ProjectsManager\Models\Project;

class M02_ProjectsTable implements MigrationInterface
{
    public CONST FIRST_MIGRATION = false;
    
    public function getScript(): string
    {
        return (new TableScriptSpitter(Project::getTableName()))
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
        return sprintf("DROP TABLE %s;", Project::getTableName());
    }

    public function isFirstMigration(): bool
    {
        return self::FIRST_MIGRATION;
    }
}

