<?php

declare(strict_types=1);

namespace Tests\Migrations;

use Danilocgsilva\ProjectsManager\Migrations\MigrationManager;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateTable;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M02_MigrationsTable;

class MigrationManagerTest extends TestCase
{
    public function testGetFirstMigration(): void
    {
        $migrationManager = new MigrationManager(Utils::getPdoWithoutDatabase());
        $firstMigration = $migrationManager->getNextMigrationClass();
        $this->assertSame(M01_CreateTable::class, $firstMigration);
    }

    public function testSecondMigration(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);

        $pdo = Utils::getPdoWithoutDatabase();

        $migrationManager = new MigrationManager($pdo);
        $firstMigrationString = $migrationManager->getNextMigrationClass();
        /** @var M01_CreateTable */
        $firstMigration = new $firstMigrationString;
        $firstMigration->setDatabaseName($testDatabaseName);
        $pdo->prepare($firstMigration->getScript())->execute();
        Utils::useDatabase($testDatabaseName);

        $secondMigrationString = $migrationManager->getNextMigrationClass();
        $this->assertSame(M02_MigrationsTable::class, $secondMigrationString);
    }
}
