<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\ResultTypeInterface;

interface RowInterface
{
    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           array<(int|string), (int|float|string|bool|null)>   $data        the result resource
     * @param           ResultType                       $type        the result resource
     */
    public function __construct(array $data, ResultTypeInterface $type);

    /**
     * returns the whole row as an array
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function getAll() : array;

    /**
     * returns one single element from the row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function get(int|string $key) : int|float|string|bool|null;

    /**
     * returns one single element from the row as an int
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsInt(int|string $key) : int;

    /**
     * returns one single element from the row as an int or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsNullableInt(int|string $key) : int|null;

    /**
     * returns one single element from the row as a float
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsFloat(int|string $key) : float;

    /**
     * returns one single element from the row as a float or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsNullableFloat(int|string $key) : float|null;

    /**
     * returns one single element from the row as a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsString(int|string $key) : string;

    /**
     * returns one single element from the row as a string or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsNullableString(int|string $key) : string|null;

    /**
     * returns one single element from the row as a bool
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsBool(int|string $key) : bool;

    /**
     * returns one single element from the row as a bool or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     */
    public function getAsNullableBool(int|string $key) : bool|null;

    /**
     * returns the result type of this row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getResultType() : ResultTypeInterface;
}
