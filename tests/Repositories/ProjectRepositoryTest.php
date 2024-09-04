<?php

declare(strict_types=1);

namespace Tests\Repositories;

use Danilocgsilva\ProjectsManager\Models\Project;
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
        $this->assertSame(0, $this->utils->getTableCount('projects'));
        $this->utils->fillTable('projects', [
            [
                "name" => "My Wonderfull Project"
            ]
        ]);
        $this->assertSame(1, $this->utils->getTableCount('projects'));

        $retrievedProject = $this->projectRepository->get(1);
        $this->assertSame("My Wonderfull Project", $retrievedProject->name);
        $this->assertSame(1, $retrievedProject->id);
    }

    public function testSave(): void
    {
        $this->assertSame(0, $this->utils->getTableCount('projects'));
        $project = new Project();
        $project->setName("Project Zero");
        $this->projectRepository->save($project);
        $this->assertSame(1, $this->utils->getTableCount('projects'));
    }

    public function testReplace(): void
    {
        $this->utils->fillTable('projects', [
            [
                "name" => "Project2"
            ]
        ]);

        $toReplaceProject = (new Project())->setName("Project9");
        $this->projectRepository->replace(1, $toReplaceProject);

        $recoveredAfterReplace = $this->projectRepository->get(1);

        $this->assertSame("Project9", $recoveredAfterReplace->name);
    }

    public function testDelete(): void
    {
        $this->utils->fillTable('projects', [
            [
                "name" => "Project3"
            ]
        ]);
        $this->assertSame(1, $this->utils->getTableCount('projects'));
        $this->projectRepository->delete(1);
        $this->assertSame(0, $this->utils->getTableCount('projects'));
    }

    public function testList(): void
    {
        $this->utils->fillTable('projects', [
            ["name" => "Another project"],
            ["name" => "Project twenties"]
        ]);
        $this->assertSame(2, $this->utils->getTableCount('projects'));
        $listOfProjects = $this->projectRepository->list();
        $this->assertCount(2, $listOfProjects);
        $this->assertSame(1, $listOfProjects[0]->id);
        $this->assertSame(2, $listOfProjects[1]->id);
    }
}

