<?php

namespace ModelTest\DataMapperTest;

use Dal\Connection;
use Model\DataMapper\StatusMapper;
use Model\Status;
use TestCase;
use PDO;

class StatusMapperTest extends TestCase
{
    private $con;

    public function setUp()
    {
      $dsn = 'mysql:host=127.0.0.1;dbname=uframework;port=32768' ;
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
        	ID           INT(6)          NOT NULL AUTO_INCREMENT,
        	DESCRIPTION  VARCHAR(250)	 NOT NULL,
        	CREATED_AT   DATETIME		 NOT NULL,
        	USER_ID      INT(6),
        	PRIMARY KEY (ID)
        ) ENGINE=InnoDB;
          SQL
        );
    }

    public function testPersist()
    {
        $mapper = new StatusMapper($this->con);
        $rows = $this->con->query('SELECT COUNT(*) FROM STATUS')->fetch(PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);
        $status = new Status("admin", 'This is a test message', date('Y-m-d H:i:s'));
        $mapper->persist($status);
        $rows = $this->con->query('SELECT COUNT(*) FROM STATUS')->fetch(PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }

    public function testRemove()
    {
        $mapper = new statusMapper($this->con);
        $status = new Status("admin", 'This is a test message', date('Y-m-d H:i:s'));
        $this->assertEquals(1, $mapper->persist($status));
        $this->assertEquals(1,$mapper->remove($status->getId()));
    }
}
