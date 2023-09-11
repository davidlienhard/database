<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\ResultInterface;
use DavidLienhard\Database\ResultType;
use DavidLienhard\Database\Row;

class StubResult implements ResultInterface
{
    /**
     * payloads
     * @var     array<int, (int|float|string|bool|null)[]>
     */
    private array $payload;

    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result|array<int, (int|float|string|bool|null)[]>   $payload      payload to use
     */
    public function __construct(\mysqli_result|array $payload)
    {
        if (!is_array($payload)) {
            throw new \TypeError("parameter \$payload must be type of array. is '".gettype($payload)."'");
        }

        $this->payload = $payload;
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          (int|float|string|bool|null)[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_assoc() : array|null
    {
        if (!array_key_exists(0, $this->payload)) {
            throw new DatabaseException("no data on key 0");
        }

        $data = $this->payload[0];

        if (!is_array($data)) {
            throw new DatabaseException("payload must be array");
        }

        return $data;
    }

    /**
     * creates an object out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_object(ResultTypeInterface $resultType = ResultType::assoc) : RowInterface|null
    {
        if (!array_key_exists(0, $this->payload)) {
            throw new DatabaseException("no data on key 0");
        }

        $data = $this->payload[0];

        if (!is_array($data)) {
            throw new DatabaseException("payload must be array");
        }

        return new Row($data, $resultType);
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          (int|float|string|bool|null)[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_assoc() : array
    {
        if (!array_key_exists(0, $this->payload)) {
            throw new DatabaseException("no data on key 0");
        }

        $data = $this->payload[0];

        if (!is_array($data)) {
            throw new DatabaseException("payload must be array");
        }

        return $data;
    }

    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          (int|float|string|bool|null)[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_array(ResultTypeInterface $resultType = ResultType::assoc) : array|null
    {
        if (!array_key_exists(0, $this->payload)) {
            throw new DatabaseException("no data on key 0");
        }

        $data = $this->payload[0];

        if (!is_array($data)) {
            throw new DatabaseException("payload must be array");
        }

        return $data;
    }

    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function num_rows() : int
    {
        return count($this->payload);
    }

    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          (int|float|string|bool|null)[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row() : array|null
    {
        if (!array_key_exists(0, $this->payload)) {
            throw new DatabaseException("no data on key 0");
        }

        $data = $this->payload[0];

        if (!is_array($data)) {
            throw new DatabaseException("payload must be array");
        }

        return $data;
    }

    /**
     * creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_object() : RowInterface|null
    {
        if (!array_key_exists(0, $this->payload)) {
            throw new DatabaseException("no data on key 0");
        }

        $data = $this->payload[0];

        if (!is_array($data)) {
            throw new DatabaseException("payload must be array");
        }

        return new Row($data, ResultType::both);
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<int, (int|float|string|bool|null)[]>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        return $this->payload;
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface         $resultType     the type of the result
     * @return          array<int, (Row)[]>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all_object(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        $data = [];
        foreach ($this->payload as $row) {
            $data[] = new Row($row, $resultType);
        }

        return $data;
    }

    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $offset      the row to jump
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function data_seek(int $offset) : bool
    {
        return true;
    }

    /**
     * Frees the memory
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function free() : void
    {
        return;
    }

    /**
     * Gets a field out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function result(int $row, string $field) : string|int|float|null
    {
        return "result";
    }
}
