<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\ResultTypeInterface;
use DavidLienhard\Database\RowInterface;

interface ResultInterface
{
    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \mysqli_result|array<int, array<(int|string), (int|float|string|bool|null)>>   $result      the result resource
     */
    public function __construct(\mysqli_result|array $result);

    /**
     * creates an array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<(int|string), (int|float|string|bool|null)>|null
     */
    public function fetch_array(ResultTypeInterface $resultType) : array|null;

    /**
     * creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<string, (int|float|string|bool|null)>
     */
    public function fetch_array_assoc() : array|null;

    /**
     * creates an associative array out of a result resource
     * use fetch_array_assoc() when ever possible as this function will be deprecated
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<string, (int|float|string|bool|null)>
     * @deprecated      3.0.0 use fetch_array_assoc() whenever possible
     */
    public function fetch_assoc() : array|null;

    /**
     * creates an enumerated array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<int, (int|float|string|bool|null)>
     */
    public function fetch_array_num() : array|null;

    /**
     * creates an enumerated array out of a result resource
     * use fetch_array_num() when ever possible as this function will be deprecated
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<int, (int|float|string|bool|null)>
     * @deprecated      3.0.0 use fetch_array_num() whenever possible
     */
    public function fetch_row() : array|null;

    /**
     * creates an object out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_object(ResultTypeInterface $resultType) : RowInterface|null;

    /**
     * creates an associative array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function fetch_single_array(ResultTypeInterface $resultType = ResultType::assoc) : array;

    /**
     * creates an associative array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function fetch_single_array_assoc() : array;

    /**
     * creates an enumerated array out of a result resource
     * this functions returns an array or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function fetch_single_array_num() : array;

    /**
     * creates an object out of a result resource
     * this functions returns a Row object or throws an exception if no rows can be found
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     */
    public function fetch_single_object(ResultTypeInterface $resultType = ResultType::assoc) : RowInterface;

    /**
     * counts the rows of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function num_rows() : int;

    /**
     * creates an array containing all data of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     type of array to return
     * @return          array<int, array<(int|string), (int|float|string|bool|null)>>
     */
    public function fetch_all_array(ResultTypeInterface $resultType) : array;

    /**
     * creates an array containing all data of a result resource as Row objects
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           ResultTypeInterface     $resultType     the type of the result
     * @return          array<int<0, max>, RowInterface>
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function fetch_all_object(ResultTypeInterface $resultType) : array;

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
    public function result(int $row, string $field) : string|int|float|bool|null;

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
    public function resultAsInt(int $row, string $field) : int;

    /**
     * gets a field out of a result resource as a float
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     */
    public function resultAsFloat(int $row, string $field) : float;

    /**
     * gets a field out of a result resource as a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     */
    public function resultAsString(int $row, string $field) : string;

    /**
     * gets a field out of a result resource as a bool
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int             $row         the row
     * @param           string          $field       the column
     * @throws          \Exception if the required field is does not exist
     */
    public function resultAsBool(int $row, string $field) : bool;
}
