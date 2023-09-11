<?php declare(strict_types=1);

namespace DavidLienhard\Database;

interface ResultInterface
{
    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result|mixed[]      $result      the result resource
     */
    public function __construct(\mysqli_result|array $result);

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          mixed[]
     */
    public function fetch_assoc() : array|null;

    /**
     * creates an object out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultType              $resultType     the type of the result
     * @return          RowInterface|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_object(ResultType $resultType = ResultType::assoc) : RowInterface|null;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_assoc() : array;

    /**
     * creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          RowInterface|null
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_row_object() : RowInterface|null;

    /**
     * Creates an array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int                 $resulttype     the type of the result
     * @return          mixed[]|null
     */
    public function fetch_array(int $resulttype = MYSQLI_BOTH) : array|null;

    /**
     * Counts the rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function num_rows() : int;

    /**
     * Creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          mixed[]|null
     */
    public function fetch_row() : array|null;

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int                 $resulttype     type of array to return
     * @return          mixed[]
     */
    public function fetch_all(int $resulttype = MYSQLI_NUM) : array;

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultType              $resultType     the type of the result
     * @return          mixed[]
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all_object(ResultType $resultType = ResultType::assoc) : array;

    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $offset      the row to jump
     */
    public function data_seek(int $offset) : bool;

    /**
     * Frees the memory
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function free() : void;

    /**
     * Gets a field out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     */
    public function result(int $row, string $field) : string|int|float|null;
}
