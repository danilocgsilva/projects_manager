<?php

declare(strict_types=1);

namespace Tests\Repositories;

use Danilocgsilva\ProjectsManager\Models\ExecutionEnvironment;
use PHPUnit\Framework\TestCase;
use PDO;
use Danilocgsilva\ProjectsManager\Repositories\ExecutionEnvitonmentRepository;
use Tests\Utils;

class ExecutionEnvironmentRepositoryTest extends TestCase
{
    private PDO $pdo;

    private ExecutionEnvitonmentRepository $executionEnvitonmentRepository;

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
        $this->utils->migrate04();

        $this->executionEnvitonmentRepository = new ExecutionEnvitonmentRepository($this->pdo);
    }

    public function testGet(): void
    {
        $this->assertSame(0, $this->utils->getTableCount('execution_environments'));
        $this->utils->fillTable('execution_environments', [
            [
                "name" => "Local from home"
            ]
        ]);
        $this->assertSame(1, $this->utils->getTableCount('execution_environments'));

        $retrievedExecutionEnvironment = $this->executionEnvitonmentRepository->get(1);
        $this->assertSame("Local from home", $retrievedExecutionEnvironment->name);
        $this->assertSame(1, $retrievedExecutionEnvironment->id);
    }

    public function testSave(): void
    {
        $this->assertSame(0, $this->utils->getTableCount('execution_environments'));
        $executionEnvironment = new ExecutionEnvironment();
        $executionEnvironment->setName("Local Machine");
        $this->executionEnvitonmentRepository->save($executionEnvironment);
        $this->assertSame(1, $this->utils->getTableCount('execution_environments'));
    }

    public function testReplace(): void
    {
        $this->utils->fillTable('execution_environments', [
            [
                "name" => "Work machine"
            ]
        ]);

        $toReplaceEnvironment = (new ExecutionEnvironment())->setName("Coffee shop machine");
        $this->executionEnvitonmentRepository->replace(1, $toReplaceEnvironment);

        $recoveredAfterReplace = $this->executionEnvitonmentRepository->get(1);

        $this->assertSame("Coffee shop machine", $recoveredAfterReplace->name);
    }

    public function testDelete(): void
    {
        $this->utils->fillTable('execution_environments', [
            [
                "name" => "Local machine"
            ]
        ]);
        $this->assertSame(1, $this->utils->getTableCount('execution_environments'));
        $this->executionEnvitonmentRepository->delete(1);
        $this->assertSame(0, $this->utils->getTableCount('execution_environments'));
    }

    public function testList(): void
    {
        $this->utils->fillTable('execution_environments', [
            ["name" => "Another project"],
            ["name" => "Project twenties"]
        ]);
        $this->assertSame(2, $this->utils->getTableCount('execution_environments'));
        $environmentMachines = $this->executionEnvitonmentRepository->list();
        $this->assertCount(2, $environmentMachines);
        $this->assertSame(1, $environmentMachines[0]->id);
        $this->assertSame(2, $environmentMachines[1]->id);
    }
}

