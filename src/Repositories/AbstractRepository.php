<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Repositories;

use PDO;

abstract class AbstractRepository
{
    public function __construct(protected PDO $pdo) 
    {
    }
}
