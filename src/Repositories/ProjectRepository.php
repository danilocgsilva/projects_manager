<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Repositories;

use Danilocgsilva\ProjectsManager\Models\Project;
use PDO;

class ProjectRepository extends AbstractRepository
{
    public const MODEL = Project::class;

    /**
     * @param Project $projectModel
     * @return void
     */
    public function save($projectModel): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (`name`) VALUES (:name)", self::MODEL::TABLENAME)
        )->execute([
            ':name' => $projectModel->name
        ]);
    }

    /**
     * @param Project $projectModel
     * @return void
     */
    public function saveAndAssingId($projectModel): void
    {
        $this->save($projectModel);
        $projectModel->setId((int) $this->pdo->lastInsertId());
    }

    /**
     * @param int $id
     * @return Project
     */
    public function get(int $id): Project
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT `id`, `name` FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new Project())
            ->setId($fetchedData[0])
            ->setName($fetchedData[1]);
    }

    /**
     * @param int $id
     * @param Project $model
     * @return void
     */
    public function replace(int $id, $model): void
    {
        $query = sprintf(
            "UPDATE %s SET name = :name WHERE id = :id;",
            self::MODEL::TABLENAME
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
            sprintf("DELETE FROM %s WHERE id = :id", self::MODEL::TABLENAME)
        )->execute([':id' => $id]);
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $query = sprintf(
            "SELECT id, %s FROM %s;",
            "name",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $projectRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $projectRepositoryList[] = (new Project())->setId($row[0])->setName($row[1]);
        }
        return $projectRepositoryList;
    }
}
