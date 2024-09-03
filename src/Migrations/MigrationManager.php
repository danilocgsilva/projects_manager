<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations;

use PDO;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateDatabase;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_MigrationsTable;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M03_ProjectsTable;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M04_ExecutionEnvironmentsTable;

class MigrationManager
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return string
     */
    public function getNextMigrationClass(): string
    {
        if ($this->noDatabase()) {
            return M01_CreateDatabase::class;
        }

        if ($this->noTables()) {
            return M02_MigrationsTable::class;
        }

        if (count($this->getTablesName()) === 1) {
            return M03_ProjectsTable::class;
        }

        if (count($this->getTablesName()) === 2) {
            return M04_ExecutionEnvironmentsTable::class;
        }
        
        return "";
    }

    /**
     * @throws \Danilocgsilva\ProjectsManager\Migrations\NoMigrationsLeft
     * @return string
     */
    public function getPreviousMigrationClass(): string
    {
        if (count($this->getTablesName()) === 2) {
            return M02_MigrationsTable::class;
        }
        if (count($this->getTablesName()) === 1) {
            return M01_CreateDatabase::class;
        }
        return "";
    }

    /**
     * @return bool
     */
    private function noDatabase(): bool
    {
        return $this->getPdoDatabase() === "" ? true : false;
    }

    private function getTablesName(): array
    {
        $preResults = $this->pdo->prepare(
            sprintf("SHOW TABLES;", $this->getPdoDatabase())
        );
        $preResults->execute();
        $tables = [];
        while ($row = $preResults->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $tables;
    }

    private function noTables(): bool
    {
        return count($this->getTablesName()) === 0;
    }

    /**
     * @return string
     */
    private function getPdoDatabase(): string
    {
        return $this->pdo->query("SELECT DATABASE();")->fetchColumn() ?? "";
    }

    /*
    private function haveMigrationTable(): bool
    {
        "SHOW TABLES;"
    }
        */
}
