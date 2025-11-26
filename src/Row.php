<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\ResultTypeInterface;
use DavidLienhard\Database\RowInterface;

class Row implements RowInterface
{
    /**
     * initiates the new object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           array<(int|string), (int|float|string|bool|null)> $data        the result resource
     * @param           ResultTypeInterface                 $resultType  the result resource
     */
    public function __construct(private array $data, private ResultTypeInterface $resultType)
    {
    }

    /**
     * returns the whole row as an array
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          array<(int|string), (int|float|string|bool|null)>
     */
    public function getAll() : array
    {
        return $this->data;
    }

    /**
     * returns one single element from the row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function get(int|string $key) : int|float|string|bool|null
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return $this->data[$key];
    }

    /**
     * returns one single element from the row as an int
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsInt(int|string $key) : int
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return \intval($this->data[$key]);
    }

    /**
     * returns one single element from the row as an int or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsNullableInt(int|string $key) : int|null
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return $this->data[$key] === null
            ? null
            : \intval($this->data[$key]);
    }

    /**
     * returns one single element from the row as a float
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsFloat(int|string $key) : float
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return \floatval($this->data[$key]);
    }

    /**
     * returns one single element from the row as a float or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsNullableFloat(int|string $key) : float|null
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return $this->data[$key] === null
            ? null
            : \floatval($this->data[$key]);
    }

    /**
     * returns one single element from the row as a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsString(int|string $key) : string
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return \strval($this->data[$key]);
    }

    /**
     * returns one single element from the row as a string or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsNullableString(int|string $key) : string|null
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return $this->data[$key] === null
            ? null
            : \strval($this->data[$key]);
    }

    /**
     * returns one single element from the row as a bool
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsBool(int|string $key) : bool
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return \boolval($this->data[$key]);
    }

    /**
     * returns one single element from the row as a bool or null
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsNullableBool(int|string $key) : bool|null
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exist");
        }

        return $this->data[$key] === null
            ? null
            : \boolval($this->data[$key]);
    }

    /**
     * returns the result type of this row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getResultType() : ResultTypeInterface
    {
        return $this->resultType;
    }
}
