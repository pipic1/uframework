<?php

namespace Model\Finder;

use Exception\HttpException;
use Dal\Connection;
use Model\User;
use \PDO;

class UserFinder
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function findOneByLogin($login)
    {
        $request = 'SELECT * FROM USER WHERE LOGIN = :login';

        $stmt = $this->con->prepare($request);
        $stmt->execute(['login' => $login]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            return new User($result['LOGIN'], null, $result['HASH'], $result['ID']);
        }
        
        return null;
    }
}
