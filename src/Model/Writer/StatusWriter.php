<?php

namespace Model\Writer;

use Exception\HttpException;
use Dal\Connection;
use \PDO;

class StatusWriter implements WriterInterface
{
    private $connection;

    private function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert($status)
    {
        $query = 'INSERT INTO STATUS(name, description, created_at) values(:name, :description, :created_at)';

        $stmt = $this->connection->prepare($query);
        $stmt->execute(['name' => $status['name'], 'description' => $status['description'], 'created_at']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        var_dump($result);
        //if(!empty($results)) {
            //todo
        //}

        return $result;
    }

    public function remove($id)
    {
        $query = 'DELETE FROM Statuses WHERE id = :id';

        $stmt = $this->connection->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        var_dump($result);

        //if(!empty($results)) {
            //todo
        //}

        return $result;
    }
}
