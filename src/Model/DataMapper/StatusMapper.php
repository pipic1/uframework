<?php

namespace Model\DataMapper;

use Dal\Connection;
use Model\Status;
use \PDO;

class StatusMapper
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist(Status $status)
    {
        $query = 'INSERT INTO STATUS (USER_ID, DESCRIPTION, CREATED_AT) VALUES(:user_id, :description, :created_at)';

        if ($status->getUser() !== null) {
            return $this->con->executeQuery($query, [
                'user_id' => $status->getUser()->getId(),
                'description' => $status->getContent(),
                'created_at' => $status->getDate()->format('Y-m-d H:i:s'),
                ]);
        }

        return $this->con->executeQuery($query, [
                'user_id' => $status->getUser(),
                'description' => $status->getContent(),
                'created_at' => $status->getDate()->format('Y-m-d H:i:s'),
                ]);
    }

    public function remove($id)
    {
        $query = 'DELETE FROM STATUS WHERE ID = :id';
        return $this->con->executeQuery($query, ['id' => $id]);
    }
}
