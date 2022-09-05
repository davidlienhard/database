<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\ResultInterface;
use function array_key_exists;

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
                intval($e->getCode()),
                $e
            );
        }
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
                intval($e->getCode()),
                $e
            );
        }

        if ($result === null) {
            throw new DatabaseException("no more rows to fetch");
        }

        return $result;
    }

    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int                 $resulttype     the type of the result
     * @return          mixed[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_array(int $resulttype = MYSQLI_BOTH) : array|null
    {
        try {
            return $this->result->fetch_array($resulttype);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function num_rows() : int
    {
        return intval($this->result->num_rows);
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
                intval($e->getCode()),
                $e
            );
        }
    }

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int                 $resulttype     type of array to return
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all(int $resulttype = MYSQLI_NUM) : array
    {
        try {
            return $this->result->fetch_all($resulttype);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
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
        try {
            return $this->result->data_seek($offset);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
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
                intval($e->getCode()),
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
    public function result(int $row, string $field) : string|int|float|null
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

            if (!\is_string($fieldValue) && !\is_int($fieldValue) && !\is_float($fieldValue) && !\is_null($fieldValue)) {
                throw new DatabaseException("field '".$field."' must be of type string, int, float or null. is of type '".\gettype($field)."'");
            }

            return $fieldValue;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }//end try
    }
}
