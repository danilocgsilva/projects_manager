<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations;

use PDO;

class MigrationManager
{
    public function __construct(private PDO $pdo) {}
    
    public function getNextMigrationClass(): string
    {
        if ($this->noTable()) {
            return "M01_MigrationTable";
        }
        
        return "";
    }

    public function getPreviousMigrationClass(): string
    {
        if ($this->noTable()) {
            return "";
        }
        return "";
    }

    private function noTable(): bool
    {
        return true;
    }
}
