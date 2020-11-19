<?php

declare(strict_types=1);

namespace DavidLienhard;

use \PHPUnit\Framework\TestCase;
use \DavidLienhard\Database\Parameter as DBParam;
use \DavidLienhard\Database\DatabaseInterface;
use \DavidLienhard\Database\Exception as DatabaseException;
use \DavidLienhard\Database\Mysqli;

class MysqliTest extends TestCase
{
    private $host = "localhost";
    private $user = "test";
    private $pass = "password";
    private $port = "32574";
    private $database = "test";

    private $db;

    /**
     * @covers \DavidLienhard\Database\Mysqli
     * @test
    */
    public function testCanBeCreated(): void
    {
        $db = new Mysqli;

        $this->assertInstanceOf(
            Mysqli::class,
            $db
        );

        $this->assertInstanceOf(
            DatabaseInterface::class,
            $db
        );

        $this->db = $db;
    }

    /**
     * @covers \DavidLienhard\Database\Mysqli
     * @test
    */
    public function testCanConnect(): void
    {
        $db = new Mysqli;

        $result = $db->connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->database,
            32574
        );

        $this->assertTrue($result);
    }

    /**
     * @covers \DavidLienhard\Database\Mysqli
     * @test
    */
    public function testGetExceptionWithWrongLogin(): void
    {
        $db = new Mysqli;

        $this->expectException(DatabaseException::class);

        $result = $db->connect(
            "wrong",
            "wrong",
            "wrong",
            "wrong"
        );

        $this->assertTrue($result);
    }

    /**
     * @covers \DavidLienhard\Database\Mysqli
     * @test
    */
    public function testCanCreateTable(): void
    {
        $db = new Mysqli;

        $db->connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->database,
            32574
        );

        $result = $db->query(
            "CREATE TABLE IF NOT EXISTS `test`(
                `testID` int NOT NULL AUTO_INCREMENT,
                `testTitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                `testValue` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `testDate` int NOT NULL DEFAULT '0',
                PRIMARY KEY (`testID`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
        );

        $this->assertIsBool($result);
    }

    /**
     * @covers \DavidLienhard\Database\Mysqli
     * @test
    */
    public function testCanInsertIntoTable(): void
    {
        $db = new Mysqli;

        $db->connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->database,
            32574
        );

        $result = $db->query(
            "INSERT INTO
                `test`
            SET
                `testTitle` = ?,
                `testValue` = ?,
                `testDate` = ?",
            new DBParam("s", "test title"),
            new DBParam("s", "test value"),
            new DBParam("i", 123456)
        );

        $this->assertIsBool($result);
    }

    /**
     * @covers \DavidLienhard\Database\Mysqli
     * @test
    */
    public function testCanSelectFromTable(): void
    {
        $db = new Mysqli;

        $db->connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->database,
            32574
        );

        $result = $db->query(
            "SELECT
                `testTitle`,
                `testValue`,
                `testDate`
            FROM
                `test`"
        );

        $this->assertEquals(1, $db->num_rows($result));

        $data = $db->fetch_assoc($result);

        $this->assertIsArray($data);

        $expected = [
            "testTitle" => "test title",
            "testValue" => "test value",
            "testDate" => 123456
        ];

        $this->assertEquals($expected, $data);
    }
}
