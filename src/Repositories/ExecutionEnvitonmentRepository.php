<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Repositories;

use Danilocgsilva\ProjectsManager\Models\ExecutionEnvironment;
use PDO;

class ExecutionEnvitonmentRepository extends AbstractRepository
{
    public const MODEL = ExecutionEnvironment::class;

    /**
     * @param ExecutionEnvironment $executionEnvironment
     * @return void
     */
    public function save($executionEnvironment): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (`name`) VALUES (:name)", self::MODEL::TABLE_NAME)
        )->execute([
            ':name' => $executionEnvironment->name
        ]);
    }

    /**
     * @param ExecutionEnvironment $executionEnvironment
     * @return void
     */
    public function saveAndAssingId($executionEnvironment): void
    {
        $this->save($executionEnvironment);
        $executionEnvironment->setId((int) $this->pdo->lastInsertId());
    }

    /**
     * @param int $id
     * @return ExecutionEnvironment
     */
    public function get(int $id): ExecutionEnvironment
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT `id`, `name` FROM %s WHERE id = :id;", self::MODEL::TABLE_NAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new ExecutionEnvironment())
            ->setId($fetchedData[0])
            ->setName($fetchedData[1]);
    }

    /**
     * @param int $id
     * @param ExecutionEnvironment $model
     * @return void
     */
    public function replace(int $id, $model): void
    {
        $query = sprintf(
            "UPDATE %s SET name = :name WHERE id = :id;",
            self::MODEL::TABLE_NAME
        );

        $this->pdo->prepare($query)->execute([
            ':name' => $model->name,
            ':id' => $id
        ]);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->pdo->prepare(
            sprintf("DELETE FROM %s WHERE id = :id", self::MODEL::TABLE_NAME)
        )->execute([':id' => $id]);
    }

    /**
     * @return array<ExecutionEnvironment>
     */
    public function list(): array
    {
        $query = sprintf(
            "SELECT id, %s FROM %s;",
            "name",
            self::MODEL::TABLE_NAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        /** @var array<ExecutionEnvironment> $projectRepositoryList */
        $projectRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $projectRepositoryList[] = (new ExecutionEnvironment())->setId($row[0])->setName($row[1]);
        }
        return $projectRepositoryList;
    }
}
