<?php

declare(strict_types=1);

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use PDO;
use Danilocgsilva\ProjectsManager\Repositories\ProjectRepository;
use Tests\Utils;

class ProjectRepositoryTest extends TestCase
{
    private PDO $pdo;

    private ProjectRepository $projectRepository;

    /**
     * @var Utils
     */
    private Utils $utils;

    protected function setUp(): void
    {
        $testDatabaseName = "projects_manager_tests";
        Utils::dropDatabase($testDatabaseName);
        $this->pdo = Utils::getPdoWithoutDatabase();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->utils = new Utils($testDatabaseName, $this->pdo);

        $this->utils->migrate01();
        $this->utils->migrate02();
        $this->utils->migrate03();

        $this->projectRepository = new ProjectRepository($this->pdo);
    }

    public function testGet(): void
    {
        $this->assertSame(0, $this->utils->getTableCount('project'));
        $this->utils->fillTable('project', [
            [
                "name" => "My Wonderfull Project"
            ]
        ]);
        $this->assertSame(1, $this->utils->getTableCount('project'));

        $retrievedProject = $this->projectRepository->get(1);
        $this->assertSame("My Wonderfull Project", $retrievedProject->name);
        $this->assertSame(1, $retrievedProject->id);
    }
}

