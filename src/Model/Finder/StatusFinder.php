<?php

namespace Model\Finder;

use Exception\HttpException;
use Dal\Connection;
use Model\Status;
use Model\User;
use \PDO;

class StatusFinder implements FinderInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll($filter = null)
    {
        $query = '
            SELECT s.ID, s.DESCRIPTION, s.CREATED_AT, u.LOGIN 
            FROM STATUS s 
            LEFT JOIN USER u 
            ON s.USER_ID = u.ID
        ';

        if (isset($filter['orderBy']) && $filter['orderBy'] === 'createdAt') {
            $query .= ' ORDER BY CREATED_AT';
        }

        if (isset($filter['limit'])) {
            $query .= ' LIMIT 0, ' . $filter['limit'];
        }

        if (isset($filter['order'])) {
            switch ($filter['order']) {
                case 'ASC':
                    $query .= ' ASC';
                    break;
                case 'DESC':
                    $query .= ' DESC';
                    break;
            }
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $statuses = [];
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['LOGIN']) {
                $statuses[] = new Status(new User($result[$i]['LOGIN']), $result[$i]['DESCRIPTION'], new \DateTime($result[$i]['CREATED_AT']), $result[$i]['ID']);
            } else {
                $statuses[] = new Status(new User('Anonymous'), $result[$i]['DESCRIPTION'], new \DateTime($result[$i]['CREATED_AT']), $result[$i]['ID']);
            }
        }

        return $statuses;
    }

    public function findOneById($id)
    {
        $query = '
            SELECT s.ID, s.DESCRIPTION, s.CREATED_AT, u.LOGIN 
            FROM STATUS s 
            LEFT JOIN USER u 
            ON s.USER_ID = u.ID
            WHERE s.id = :id
        ';

        $stmt = $this->connection->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        if ($result['LOGIN']) {
            return $status = new Status(new User($result['LOGIN']), $result['DESCRIPTION'], new \DateTime($result['CREATED_AT']), $result['ID']);
        }

        return $status = new Status(new User('Anonymous'), $result['DESCRIPTION'], new \DateTime($result['CREATED_AT']), $result['ID']);
    }
}
