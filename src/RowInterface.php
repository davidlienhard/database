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
     * @param           (int|float|string|bool|null)[]   $data        the result resource
     * @param           ResultType                       $type        the result resource
     */
    public function __construct(array $data, ResultTypeInterface $type);

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          (int|float|string|bool|null)[]
     */
    public function getAll() : array;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function get(int|string $key) : int|float|string|bool|null;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsInt(int|string $key) : int;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsFloat(int|string $key) : float;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsString(int|string $key) : string;

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsBool(int|string $key) : bool;

    /**
     * returns the result type of this row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getResultType() : ResultTypeInterface;
}
