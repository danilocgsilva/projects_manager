<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Migrations;

interface MigrationInterface
{
    public function getScript(): string;

    public function getRollbackScript(): string;
}
