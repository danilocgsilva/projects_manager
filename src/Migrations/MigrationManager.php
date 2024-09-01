<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations;

use PDO;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateTable;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_MigrationsTable;

class MigrationManager
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return string
     */
    public function getNextMigrationClass(): string
    {
        if ($this->noDatabase()) {
            return M01_CreateTable::class;
        }

        if ($this->noTables()) {
            return M02_MigrationsTable::class;
        }

        /*
        if ($this->haveMigrationTable()) {

        }
        */
        
        return "";
    }

    /**
     * @throws \Danilocgsilva\ProjectsManager\Migrations\NoMigrationsLeft
     * @return string
     */
    public function getPreviousMigrationClass(): string
    {
        if ($this->noDatabase()) {
            throw new NoMigrationsLeft();
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
     * @return bool
     */
    private function onlyOneMigration(): bool
    {
        return count($this->getTablesName()) === 1;
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
