<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;

class M02_MigrationsTable implements MigrationInterface
{
    private CONST FIRST_MIGRATION = true;

    public function getScript(): string
    {
        return (new TableScriptSpitter("migrations"))
            ->addField(
                (new FieldScriptSpitter("id"))
                ->setType("INT")
                ->setPrimaryKey()
                ->setNotNull()
                ->setUnsigned()
            )
            ->addField((new FieldScriptSpitter("migration"))->setType("CHAR(192)"))
            ->getScript();
    }

    public function getRollbackScript(): string
    {
        return sprintf("DROP TABLE %s;", "migrations");
    }

    public function isFirstMigration(): bool
    {
        return self::FIRST_MIGRATION;
    }
}
