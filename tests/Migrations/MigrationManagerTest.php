<?php

declare(strict_types=1);

namespace Tests\Migrations;

use Danilocgsilva\ProjectsManager\Migrations\MigrationManager;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\{
    M01_CreateDatabase, 
    M02_MigrationsTable, 
    M03_ProjectsTable,
    M04_ExecutionEnvironmentsTable,
    M05_DatabaseTable
};

class MigrationManagerTest extends TestCase
{
    /**
     * @var MigrationManager
     */
    private MigrationManager $migrationManager;

    /**
     * @var Utils
     */
    private Utils $utils;

    public function setUp(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);
        $pdo = Utils::getPdoWithoutDatabase();
        $this->utils = new Utils($testDatabaseName, $pdo);
        $this->migrationManager = new MigrationManager($pdo);
    }
    
    public function testGetFirstMigration(): void
    {
        $migrationManager = new MigrationManager(Utils::getPdoWithoutDatabase());
        $firstMigration = $migrationManager->getNextMigrationClass();
        $this->assertSame(M01_CreateDatabase::class, $firstMigration);
    }

    public function testSecondMigration(): void
    {
        $this->utils->migrate01();

        $secondMigrationString = $this->migrationManager->getNextMigrationClass();
        $this->assertSame(M02_MigrationsTable::class, $secondMigrationString);
    }

    public function testThirdMigration(): void
    {
        $this->utils->migrate01();
        $this->utils->migrate02();
        
        $thirdMigrationString = $this->migrationManager->getNextMigrationClass();

        $this->assertSame(M03_ProjectsTable::class, $thirdMigrationString);
    }

    public function testGetPreviousMigrationAfterThreeMigrations(): void
    {
        $this->utils->migrate01();
        $this->utils->migrate02();
        $this->utils->migrate03();

        $previousMigrationString = $this->migrationManager->getPreviousMigrationClass();
        $this->assertSame(M02_MigrationsTable::class, $previousMigrationString);
    }

    public function testGetPreviousMigrationAfterTwoOnes(): void
    {
        $this->utils->migrate01();
        $this->utils->migrate02();

        $previousMigrationString = $this->migrationManager->getPreviousMigrationClass();
        $this->assertSame(M01_CreateDatabase::class, $previousMigrationString);
    }

    public function testMigration4(): void
    {
        $this->utils->migrate01();
        $this->utils->migrate02();
        $this->utils->migrate03();

        $nextMigrationString = $this->migrationManager->getNextMigrationClass();
        $this->assertSame(M04_ExecutionEnvironmentsTable::class, $nextMigrationString);
    }

    public function testMigration5(): void
    {
        $this->utils->migrate01();
        $this->utils->migrate02();
        $this->utils->migrate03();
        $this->utils->migrate04();

        $nextMigrationString = $this->migrationManager->getNextMigrationClass();
        $this->assertSame(M05_DatabaseTable::class, $nextMigrationString);
    }
}
