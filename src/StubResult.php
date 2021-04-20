<?php

declare(strict_types=1);

namespace DavidLienhard\Database;

use \DavidLienhard\Database\ResultInterface;

class StubResult implements ResultInterface
{
    /**
     * payloads
     * @var     mixed[]
     */
    private array $payload;

    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result|mixed[]        $payload      payload to use
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
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_assoc() : array|null
    {
        return $this->payload[0] ?? $this->payload;
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
        return $this->payload[0] ?? $this->payload;
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
        return $this->payload[0] ?? $this->payload;
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
     * @return          mixed[]|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row() : array|null
    {
        return $this->payload[0] ?? $this->payload;
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
        return $this->payload;
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
