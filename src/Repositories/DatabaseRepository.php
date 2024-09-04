<?php

declare(strict_types=1);

namespace Danilocgsilva\ProjectsManager\Repositories;

use Danilocgsilva\ProjectsManager\Models\Database;
use PDO;

class DatabaseRepository extends AbstractRepository
{
    public const MODEL = Database::class;

    /**
     * @param Database $databaseModel
     * @return void
     */
    public function save($databaseModel): void
    {
        $this->pdo->prepare(
            sprintf(
                "INSERT INTO %s " .
                    "(`name`, `user`, `passwordHash`, `description`) VALUES " . 
                    "(:name, :user, :passwordHash, :description)"
                , 
                self::MODEL::TABLE_NAME
            )
        )->execute([
            ':name' => $databaseModel->name,
            ':user' => $databaseModel->user,
            ':passwordHash' => $databaseModel->passwordHash,
            ':description' => $databaseModel->description
        ]);
    }

    /**
     * @param Database $databaseModel
     * @return void
     */
    public function saveAndAssingId($databaseModel): void
    {
        $this->save($databaseModel);
        $databaseModel->setId((int) $this->pdo->lastInsertId());
    }

    /**
     * @param int $id
     * @return Database
     */
    public function get(int $id): Database
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT `id`, `name`, `user`, `passwordHash`, `description` FROM %s WHERE id = :id;",
            self::MODEL::TABLE_NAME)
        );
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new Database())
            ->setId($fetchedData['id'])
            ->setName($fetchedData['name'])
            ->setUser($fetchedData['user'])
            ->setPasswordHash($fetchedData['passwordHash'])
            ->setDescription($fetchedData['description'])
        ;
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
     * @return array
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
        $projectRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $projectRepositoryList[] = (new Project())->setId($row[0])->setName($row[1]);
        }
        return $projectRepositoryList;
    }
}
