<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations;

use PDO;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateTable;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_ProjectsTable;

class MigrationManager
{
    public function __construct(private PDO $pdo) {}

    public function getNextMigrationClass(): string
    {
        if ($this->noTable()) {
            return M01_CreateTable::class;
        }

        if ($this->onlyMigration()) {
            return M02_ProjectsTable::class;
        }
        
        return "";
    }

    public function getPreviousMigrationClass(): string
    {
        if ($this->noTable()) {
            throw new NoMigrationsLeft();
        }
        return "";
    }

    private function noTable(): bool
    {
        return (bool) $this->pdo->query("SELECT DATABASE();")->fetchColumn();
    }

    private function onlyMigration(): bool
    {
        $preResults = $this->pdo->prepare();
    }
}
