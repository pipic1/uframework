<?php
namespace ModelTest\DataMapperTest;

use Dal\Connection;
use Model\DataMapper\UserMapper;
use Model\User;
use PDO;

class UserMapperTest extends \PHPUnit\Framework\TestCase
{
    private $connection;

    private $mapper;

    public function setUp()
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=uframework;port=32769' ;
        $user = 'uframework' ;
        $password = 'p4ssw0rd';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $this->con = new Connection($dsn, $user, $password, $options);
        $this->con->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS USER (
          ID INT(6) NOT NULL,
        	LOGIN	VARCHAR(250) NOT NULL,
        	HASH VARCHAR(250) NOT NULL
        );
SQL
        );
        $this->mapper = new UserMapper($this->connection);
    }

    public function testPersist()
    {
        $rows = $this->connection->query('SELECT COUNT(*) FROM USER')->fetch(PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);
        $user = new User('admin', 'admin');
        $this->mapper->persist($user);
        $rows = $this->connection->query('SELECT COUNT(*) FROM USER')->fetch(PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }
}