<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\ProjectsManager\Migrations\Migrations\M03_ProjectsTable;
use PDO;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateDatabase;
use Danilocgsilva\ProjectsManager\Migrations\MigrationManager;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_MigrationsTable;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M04_ExecutionEnvironmentsTable;

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
        /** @var M01_CreateDatabase */
        $firstMigration = new M01_CreateDatabase();
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
        /** @var M03_ProjectsTable $migration */
        $migration = new M03_ProjectsTable();
        $this->pdo->prepare($migration->getScript())->execute();
    }

    public function migrate04(): void
    {
        /** @var M04_ExecutionEnvironmentsTable $migration */
        $migration = new M04_ExecutionEnvironmentsTable();
        $this->pdo->prepare($migration->getScript())->execute();
    }

    public function getTableCount(string $tableName): int
    {
        $query = sprintf("SELECT COUNT(*) FROM %s;", $tableName);
        $preResults = $this->pdo->prepare($query);
        $preResults->execute();
        $preResults->setFetchMode(PDO::FETCH_NUM);
        return (int) $preResults->fetch()[0];
    }

    public function fillTable(string $tableName, array $data): void
    {
        foreach ($data as $entriesRecords) {
            $fields = array_keys($entriesRecords);
            $placeholders = array_map(fn($field) => ":$field", $fields);
    
            $query = sprintf(
                "INSERT INTO %s (%s) VALUES (%s);",
                $tableName,
                implode(", ", $fields),
                implode(", ", $placeholders)
            );
    
            $statement = $this->pdo->prepare($query);
            $statement->execute(array_combine($placeholders, $entriesRecords));
        }
    }
}
