<?php

namespace Model\DataMapper;

use Dal\Connection;
use \PDO;

class UserMapper implements DataMapperInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist($user)
    {
        $request = 'INSERT INTO USER(LOGIN, HASH) VALUES (:login, :hash)';
        $this->con->executeQuery($request, ['login' => $user->getLogin(), 'hash' => $user->getHash()]);
    }

    public function remove($id)
    {
        $request = 'DELETE FROM USER WHERE ID = :id';
        $this->con->executeQuery($request, ['id', $id]);
    }
}
