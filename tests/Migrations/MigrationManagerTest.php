<?php

declare(strict_types=1);

namespace Tests\Migrations;

use Danilocgsilva\ProjectsManager\Migrations\MigrationManager;
use PHPUnit\Framework\TestCase;
use Tests\Utils;
use Danilocgsilva\ProjectsManager\Migrations\Migrations\M01_CreateTable;

class MigrationManagerTest extends TestCase
{
    public function getFirstMigration()
    {
        $migrationManager = new MigrationManager(Utils::getPdoWithoutDatabase());
        $firstMigration = $migrationManager->getNextMigrationClass();
        $this->assertSame(M01_CreateTable::class, $firstMigration);
    }
}
