<?php

declare(strict_types=1);

namespace DavidLienhard\Database;

interface ResultInterface
{
    /**
     * initiates the new object
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result      $result      the result resource
     * @return          array|null
     */
    public function __construct(\mysqli_result $result);

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          array|null
     */
    public function fetch_assoc() : ?array;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          array
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_assoc() : array;

    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           int                 $resulttype     the type of the result
     * @return          array|null
     */
    public function fetch_array(int $resulttype = MYSQLI_BOTH) : ?array;

    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          int
     */
    public function num_rows() : int;

    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          array|null
     */
    public function fetch_row() : ?array;

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           int                 $resulttype     type of array to return
     * @return          array
     */
    public function fetch_all(int $resulttype = MYSQLI_NUM) : array;

    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $offset      the row to jump
     * @return          bool
     */
    public function data_seek(int $offset) : bool;

    /**
     * Frees the memory
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          void
     */
    public function free() : void;

    /**
     * Gets a field out of a result resource
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @return          string|int|float|null
     */
    public function result(int $row, string $field) : string | int | float | null;
}
