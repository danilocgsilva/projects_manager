<?php

declare(strict_types=1);

namespace Tests\Migrations;

use Danilocgsilva\ProjectsManager\Migrations\MigrationManager;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M03_ProjectsTable;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateDatabase;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_MigrationsTable;

class MigrationManagerTest extends TestCase
{
    public function testGetFirstMigration(): void
    {
        $migrationManager = new MigrationManager(Utils::getPdoWithoutDatabase());
        $firstMigration = $migrationManager->getNextMigrationClass();
        $this->assertSame(M01_CreateDatabase::class, $firstMigration);
    }

    public function testSecondMigration(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);
        $pdo = Utils::getPdoWithoutDatabase();

        $migrationManager = new MigrationManager($pdo);

        $utils = new Utils($testDatabaseName, $pdo);
        $utils->migrate01();

        $secondMigrationString = $migrationManager->getNextMigrationClass();
        $this->assertSame(M02_MigrationsTable::class, $secondMigrationString);
    }

    public function testThirdMigration(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);
        $pdo = Utils::getPdoWithoutDatabase();

        $utils = new Utils($testDatabaseName, $pdo);
        $utils->migrate01();
        $utils->migrate02();
        
        $migrationManager = new MigrationManager($pdo);
        $thirdMigrationString = $migrationManager->getNextMigrationClass();

        $this->assertSame(M03_ProjectsTable::class, $thirdMigrationString);
    }

    public function testGetPreviousMigrationAfterThreeMigrations(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);
        $pdo = Utils::getPdoWithoutDatabase();

        $utils = new Utils($testDatabaseName, $pdo);
        $utils->migrate01();
        $utils->migrate02();
        $utils->migrate03();

        $migrationManager = new MigrationManager($pdo);
        $previousMigrationString = $migrationManager->getPreviousMigrationClass();
        $this->assertSame(M02_MigrationsTable::class, $previousMigrationString);
    }

    public function testGetPreviousMigrationAfterTwoOnes(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);
        $pdo = Utils::getPdoWithoutDatabase();

        $utils = new Utils($testDatabaseName, $pdo);
        $utils->migrate01();
        $utils->migrate02();

        $migrationManager = new MigrationManager($pdo);
        $previousMigrationString = $migrationManager->getPreviousMigrationClass();
        $this->assertSame(M01_CreateDatabase::class, $previousMigrationString);
    }
}
