<?php declare(strict_types=1);

namespace DavidLienhard;

require_once dirname(__DIR__)."/src/Parameter.php";
require_once dirname(__DIR__)."/src/ParameterInterface.php";

use DavidLienhard\Database\ResultType;
use DavidLienhard\Database\Row;
use DavidLienhard\Database\RowInterface;
use PHPUnit\Framework\TestCase;

class DatabaseRowTest extends TestCase
{
    /**
     * @covers \DavidLienhard\Database\Row
     * @test
    */
    public function testCanBeCreated(): void
    {
        $resultType = ResultType::assoc;

        $row = new Row([
            "key" => "value"
        ], $resultType);

        $this->assertInstanceOf(Row::class, $row);
        $this->assertInstanceOf(RowInterface::class, $row);
    }
}
