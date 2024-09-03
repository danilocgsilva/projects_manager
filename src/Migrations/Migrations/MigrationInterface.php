<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations\Migrations;

interface MigrationInterface
{
    public function getScript(): string;

    public function getRollbackScript(): string;

    public function isFirstMigration(): bool;

    public function getDescription(): string;
}
