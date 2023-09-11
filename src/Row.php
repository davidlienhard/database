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
     * @param           (int|float|string|bool|null)[]   $data        the result resource
     * @param           ResultTypeInterface              $resultType  the result resource
     */
    public function __construct(private array $data, private ResultTypeInterface $resultType)
    {
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @return          (int|float|string|bool|null)[]
     */
    public function getAll() : array
    {
        return $this->data;
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function get(int|string $key) : int|float|string|bool|null
    {
        if (!\array_key_exists($key, $this->data)) {
            throw new DatabaseException("key '".$key."' does not exixt");
        }

        return $this->data[$key];
    }

    /**
     * Creates an associative array out of a result resource
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           int|string          $key         key to use
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function getAsString(int|string $key) : string
    {
        return \strval($this->get[$key]);
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
