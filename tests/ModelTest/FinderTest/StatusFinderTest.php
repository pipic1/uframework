<?php

use Model\Finder\StatusFinder;
use Dal\Connection;
use Model\Status;
use Model\User;

class StatusFinderTest extends \PHPUnit\Framework\TestCase
{
    private $con;

    private $finder;
    
    private $filter;
    
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
        CREATE TABLE IF NOT EXISTS STATUS (
            ID INT(6) NOT NULL AUTO_INCREMENT,
            DESCRIPTION VARCHAR(250) NOT NULL,
            CREATED_AT DATETIME NOT NULL,
            USER_ID INT(6)
        );
        
        CREATE TABLE IF NOT EXISTS USER (
            ID INT(6) NOT NULL AUTO_INCREMENT,
            LOGIN VARCHAR(250) NOT NULL,
            HASH VARCHAR(250) NOT NULL
        );

        INSERT INTO STATUS(ID, DESCRIPTION, CREATED_AT, USER_ID) VALUES(1, 'Status de test unitaire', '2017-02-12 13:00:39', 1);
        INSERT INTO USER(ID, LOGIN, HASH) VALUES (1, 'UnitTestUser', 'foiednbfidnbsiufedkjnh');
SQL
        );
        
        $this->finder = new StatusFinder($this->con);
        $this->filter['order'] = "";
        $this->filter['orderBy'] = "";
        $this->filter['limit'] = "";
    }

    public function testFindAll()
    {
        $expected = new Status(new User('UnitTestUser'), 'Status de test unitaire', new \DateTime('2017-02-12 13:00:39'), 1);
        $statuses = $this->finder->findAll($this->filter);
        $this->assertEquals($expected, $statuses[0]);
    }

    public function testCountFindAll()
    {
        $statuses = $this->finder->findAll($this->filter);
        $this->assertEquals(1, count($statuses));
    }

    public function testFindOneById()
    {
        $expected = new Status(new User('UnitTestUser'), 'Status de test unitaire', new \DateTime('2017-02-12 13:00:39'),1);
        $status = $this->finder->findOneById(1);
        $this->assertEquals($expected, $status);
    }

    public function testCountFindOneById()
    {
        $status = $this->finder->findOneById(1);
        $this->assertEquals(1, count($status));
    }
}