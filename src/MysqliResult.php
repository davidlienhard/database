<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\ResultInterface;
use DavidLienhard\Database\ResultType;

class MysqliResult implements ResultInterface
{
    /**
     * result returned by mysqli
     */
    private \mysqli_result $result;

    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result      $result      the result resource
     */
    public function __construct(\mysqli_result|array $result)
    {
        if (!($result instanceof \mysqli_result)) {
            throw new \TypeError("parameter \$result must be type of \mysqli_result. is '".gettype($result)."'");
        }

        $this->result = $result;
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_assoc() : array|null
    {
        try {
            return $this->result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * creates an object out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultType              $resultType     the type of the result
     * @return          RowInterface|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_object(ResultType $resultType = ResultType::assoc) : RowInterface|null
    {
        try {
            $result = $this->result->fetch_array($resultType::toMysqli());
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }

        if ($result !== null) {
            return new Row($result, $resultType);
        }

        return null;
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_assoc() : array
    {
        try {
            $result = $this->result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }

        if ($result === null) {
            throw new DatabaseException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          RowInterface|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_object() : RowInterface|null
    {
        try {
            $result = $this->result->fetch_row();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }

        if ($result !== null) {
            return new Row($result, ResultType::both);
        }

        return null;
    }

    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultType              $resultType     the type of the result
     * @return          mixed[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_array(ResultType $resultType = ResultType::assoc) : array|null
    {
        try {
            return $this->result->fetch_array($resultType->toMysqli());
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * counts the rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function num_rows() : int
    {
        return \intval($this->result->num_rows);
    }

    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          mixed[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row() : array|null
    {
        try {
            return $this->result->fetch_row();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultType              $resultType     the type of the result
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all(ResultType $resultType = ResultType::assoc) : array
    {
        try {
            return $this->result->fetch_all($resultType, $resultType::toMysqli());
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultType              $resultType     the type of the result
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all_object(ResultType $resultType = ResultType::assoc) : array
    {
        try {
            $result = $this->result->fetch_all($resultType);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }

        $data = [];
        foreach ($result as $row) {
            $data[] = new Row($row, $resultType);
        }

        return $data;
    }

    /**
     * sets the pointer to the given row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $offset      the row to jump
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function data_seek(int $offset) : bool
    {
        try {
            return $this->result->data_seek($offset);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * Frees the memory
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function free() : void
    {
        try {
            $this->result->free();
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
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
    public function result(int $row, string $field) : string|int|float|bool|null
    {
        try {
            $this->data_seek($row);
            $dataRow = $this->fetch_assoc();

            if ($dataRow === null) {
                throw new DatabaseException("unable to fetch assoc array");
            }

            if (!array_key_exists($field, $dataRow)) {
                throw new DatabaseException("field '".$field."' does not exist");
            }

            $fieldValue = $dataRow[$field];

            if (!\is_string($fieldValue) &&
                !\is_int($fieldValue) &&
                !\is_float($fieldValue) &&
                !\is_null($fieldValue) &&
                !\is_bool($fieldValue)
            ) {
                throw new DatabaseException(
                    "field '".$field."' must be of type string, int, float or null. ".
                    "is of type '".\gettype($field)."'"
                );
            }

            return $fieldValue;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }//end try
    }

    /**
     * gets a field out of a result resource as an int
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function resultAsInt(int $row, string $field) : int
    {
        return \intval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as a float
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function resultAsFloat(int $row, string $field) : float
    {
        return \floatval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function resultAsString(int $row, string $field) : string
    {
        return \strval($this->result($row, $field));
    }

    /**
     * gets a field out of a result resource as a bool
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function resultAsBool(int $row, string $field) : bool
    {
        return \boolval($this->result($row, $field));
    }
}
