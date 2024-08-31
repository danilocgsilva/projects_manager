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

        if ($this->onlyOneMigration()) {
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
        return $this->getPdoDatabase() === "" ? true : false;
    }

    private function onlyOneMigration(): bool
    {
        $preResults = $this->pdo->prepare(
            sprintf("SHOW TABLES;", $this->getPdoDatabase())
        );
        $preResults->execute();
        $tables = [];
        while ($row = $preResults->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return count($tables) === 1;
    }

    private function getPdoDatabase(): string
    {
        return $this->pdo->query("SELECT DATABASE();")->fetchColumn() ?? "";
    }
}
