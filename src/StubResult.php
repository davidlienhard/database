<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\ResultInterface;
use DavidLienhard\Database\ResultType;
use DavidLienhard\Database\ResultTypeInterface;
use DavidLienhard\Database\Row;
use DavidLienhard\Database\RowInterface;

class StubResult implements ResultInterface
{
    /**
     * payloads
     * @var     array<int, array<(int|string), (int|float|string|bool|null)>>
     */
    private array $payload;

    /** number of runs to fetch data */
    private int $runCount = 0;

    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result|array<int, array<(int|string), (int|float|string|bool|null)>>   $payload      payload to use
     */
    public function __construct(\mysqli_result|array $payload)
    {
        if (!\is_array($payload)) {
            throw new \TypeError("parameter \$payload must be type of array. is '".gettype($payload)."'");
        }

        $this->payload = $payload;
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>|null
     */
    public function fetch_array(ResultTypeInterface $resultType = ResultType::assoc) : array|null
    {
        if (!\array_key_exists($this->runCount, $this->payload)) {
            $this->runCount++;
            return null;
        }

        $data = $this->payload[$this->runCount];

        $this->runCount++;
        return $data;
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<string, (int|float|string|bool|null)>|null
     */
    public function fetch_array_assoc(ResultTypeInterface $resultType = ResultType::assoc) : array|null
    {
        $data = $this->fetch_array(ResultType::assoc);

        if ($data === null) {
            return null;
        }

        $keys = \array_keys($data);
        $keys = \array_map("strval", $keys);
        $data = \array_combine($keys, $data);

        return $data;
    }

    /**
     * creates an associative array out of a result resource
     * use fetch_array_assoc() when ever possible as this function will be deprecated
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<string, (int|float|string|bool|null)>
     * @deprecated      3.0.0 use fetch_array_assoc() whenever possible
     */
    public function fetch_assoc() : array|null
    {
        trigger_error(
            "method MysqliResult\\fetch_assoc() is deprecated as of version 3.0.0",
            E_USER_DEPRECATED
        );

        return $this->fetch_array_assoc();
    }

    /**
     * creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<int, (int|float|string|bool|null)>
     */
    public function fetch_array_num() : array|null
    {
        $data = $this->fetch_array(ResultType::num);

        if ($data === null) {
            return null;
        }

        $keys = \array_keys($data);
        $keys = \array_map("intval", $keys);
        $data = \array_combine($keys, $data);

        return $data;
    }

    /**
     * creates an enumerated array out of a result resource
     * use fetch_array_num() when ever possible as this function will be deprecated
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<int, (int|float|string|bool|null)>
     * @deprecated      3.0.0 use fetch_array_num() whenever possible
     */
    public function fetch_row() : array|null
    {
        trigger_error(
            "method MysqliResult\\fetch_row() is deprecated as of version 3.0.0",
            E_USER_DEPRECATED
        );

        return $this->fetch_array_num();
    }

    /**
     * creates an object out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     */
    public function fetch_object(ResultTypeInterface $resultType = ResultType::assoc) : RowInterface|null
    {
        if (!\array_key_exists($this->runCount, $this->payload)) {
            $this->runCount++;
            return null;
        }

        $data = $this->payload[$this->runCount];

        $this->runCount++;
        return new Row($data, $resultType);
    }

    /**
     * creates an associative array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function fetch_single_array(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        $result = $this->fetch_array($resultType);

        if ($result === null) {
            throw new DatabaseException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * creates an associative array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function fetch_single_array_assoc() : array
    {
        $result = $this->fetch_array_assoc();

        if ($result === null) {
            throw new DatabaseException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * creates an enumerated array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function fetch_single_array_num() : array
    {
        $result = $this->fetch_array_num();

        if ($result === null) {
            throw new DatabaseException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * creates an object out of a result resource
     * this functions returns a Row object or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     */
    public function fetch_single_object(ResultTypeInterface $resultType = ResultType::assoc) : RowInterface
    {
        $result = $this->fetch_object($resultType);

        if ($result === null) {
            throw new DatabaseException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function num_rows() : int
    {
        return count($this->payload);
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<int, array<(int|string), (int|float|string|bool|null)>>
     */
    public function fetch_all_array(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        return $this->payload;
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface         $resultType     the type of the result
     * @return          array<int<0, max>, RowInterface>
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
     */
    public function data_seek(int $offset) : bool
    {
        $this->runCount = $offset;
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
     */
    public function result(int $row, string $field) : string|int|float|null
    {
        return "result";
    }

    /**
     * gets a field out of a result resource as an int
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsInt(int $row, string $field) : int
    {
        return \intval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as an int
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsNullableInt(int $row, string $field) : int|null
    {
        $result = $this->result($row, $field);
        return $result === null
            ? null
            : \intval($result);
    }

    /**
     * gets a field out of a result resource as a float
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsFloat(int $row, string $field) : float
    {
        return \floatval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as a float
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsNullableFloat(int $row, string $field) : float|null
    {
        $result = $this->result($row, $field);
        return $result === null
            ? null
            : \floatval($result);
    }

    /**
     * gets a field out of a result resource as a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsString(int $row, string $field) : string
    {
        return \strval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsNullableString(int $row, string $field) : string|null
    {
        $result = $this->result($row, $field);
        return $result === null
            ? null
            : \strval($result);
    }

    /**
     * gets a field out of a result resource as a bool
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsBool(int $row, string $field) : bool
    {
        return \boolval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as a bool
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function resultAsNullableBool(int $row, string $field) : bool|null
    {
        $result = $this->result($row, $field);
        return $result === null
            ? null
            : \boolval($result);
    }
}
