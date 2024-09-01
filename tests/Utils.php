<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\ProjectsManager\Migrations\Migrations\M03_ProjectsTable;
use PDO;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateTable;
use Danilocgsilva\ProjectsManager\Migrations\MigrationManager;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_MigrationsTable;

class Utils
{
    private MigrationManager $migrationManager;
    
    public function __construct(private string $databaseTestName, private PDO $pdo) {
        $this->migrationManager = new MigrationManager(self::getPdoWithoutDatabase());
    }
    
    public static function getPdoWithoutDatabase(): PDO
    {
        return new PDO(
            sprintf("mysql:host=%s", getenv("PROJECTS_MANAGER_DB_TEST_HOST")),
            getenv('PROJECTS_MANAGER_DB_TEST_USER'),
            getenv('PROJECTS_MANAGER_DB_TEST_PASSWORD')
        );
    }

    public static function dropDatabase(string $databaseName): void
    {
        self::getPdoWithoutDatabase()->prepare(
            sprintf("DROP DATABASE IF EXISTS %s;", $databaseName)
        )->execute();
    }

    public static function useDatabase(string $databaseName, PDO $pdo): void
    {
        $pdo->prepare(
            sprintf("USE %s;", $databaseName)
        )->execute();
    }

    public function migrate01(): void
    {
        /** @var M01_CreateTable */
        $firstMigration = new M01_CreateTable();
        $firstMigration->setDatabaseName($this->databaseTestName);
        $this->pdo->prepare($firstMigration->getScript())->execute();
    }

    public function migrate02(): void
    {
        /** @var M02_MigrationsTable */
        $migration = new M02_MigrationsTable();
        $this->pdo->prepare($migration->getScript())->execute();
    }

    public function migrate03(): void
    {
        /** @var M03_ProjectsTable */
        $migration = new M03_ProjectsTable();
        $this->pdo->prepare($migration->getScript())->execute();
    }
}
