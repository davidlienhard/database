<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\NoRowsException;
use DavidLienhard\Database\ResultInterface;
use DavidLienhard\Database\ResultType;
use DavidLienhard\Database\ResultTypeInterface;
use DavidLienhard\Database\Row;
use DavidLienhard\Database\RowInterface;

class MysqliResult implements ResultInterface
{
    /** result returned by mysqli */
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
     * creates an array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<(int|string), (int|float|string|bool|null)>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_array(ResultTypeInterface $resultType = ResultType::assoc) : array|null
    {
        try {
            return match ($resultType) {
                ResultType::assoc => $this->result->fetch_assoc(),
                ResultType::num => $this->result->fetch_row(),
                default => $this->result->fetch_array(ResultType::both->toMysqli())
            };
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<string, (int|float|string|bool|null)>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_array_assoc() : array|null
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
     * creates an associative array out of a result resource
     * use fetch_array_assoc() when ever possible as this function will be deprecated
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<string, (int|float|string|bool|null)>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_array_num() : array|null
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
     * creates an enumerated array out of a result resource
     * use fetch_array_num() when ever possible as this function will be deprecated
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<int, (int|float|string|bool|null)>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_object(ResultTypeInterface $resultType = ResultType::assoc) : RowInterface|null
    {
        $result = $this->fetch_array($resultType);

        if ($result === null) {
            return null;
        }

        return new Row($result, $resultType);
    }

    /**
     * creates an associative array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<(int|string), (int|float|string|bool|null)>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @throws          \DavidLienhard\Database\NoRowsException if no row can be fetched
     */
    public function fetch_single_array(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        $result = match ($resultType) {
            ResultType::assoc => $this->result->fetch_assoc(),
            ResultType::num => $this->result->fetch_row(),
            default => $this->result->fetch_array(ResultType::both->toMysqli())
        };

        if ($result === null) {
            throw new NoRowsException("no more rows to fetch");
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
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @throws          \DavidLienhard\Database\NoRowsException if no row can be fetched
     */
    public function fetch_single_array_assoc() : array
    {
        $result = $this->fetch_array_assoc();

        if ($result === null) {
            throw new NoRowsException("no more rows to fetch");
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
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @throws          \DavidLienhard\Database\NoRowsException if no row can be fetched
     */
    public function fetch_single_array_num() : array
    {
        $result = $this->fetch_array_num();

        if ($result === null) {
            throw new NoRowsException("no more rows to fetch");
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
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @throws          \DavidLienhard\Database\NoRowsException if no row can be fetched
     */
    public function fetch_single_object(ResultTypeInterface $resultType = ResultType::assoc) : RowInterface
    {
        $result = $this->fetch_object($resultType);

        if ($result === null) {
            throw new NoRowsException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * returns the number of rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function num_rows() : int
    {
        return \intval($this->result->num_rows);
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<int, array<(int|string), (int|float|string|bool|null)>>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all_array(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        try {
            return $this->result->fetch_all($resultType->toMysqli());
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * creates an array containing all data of a result resource as Row objects
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface         $resultType     the type of the result
     * @return          array<int<0, max>, RowInterface>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all_object(ResultTypeInterface $resultType = ResultType::assoc) : array
    {
        try {
            $result = $this->result->fetch_all($resultType->toMysqli());
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                \intval($e->getCode()),
                $e
            );
        }

        $data = \array_map(
            fn ($row) => new Row($row, $resultType),
            $result
        );

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
     * frees the memory
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
     * gets a field out of a result resource
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
            $dataRow = $this->fetch_array_assoc();

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
     * gets a field out of a result resource as an int
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
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
     * @throws          \Exception if the required field is does not exist
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function resultAsNullableBool(int $row, string $field) : bool|null
    {
        $result = $this->result($row, $field);
        return $result === null
            ? null
            : \boolval($result);
    }
}
