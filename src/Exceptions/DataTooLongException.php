<?php declare(strict_types=1);

/**
 * Database Data Too Long Exception Class
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */

namespace DavidLienhard\Database\Exceptions;

use DavidLienhard\Database\Exceptions\Exception as DatabaseException;

/**
 * Database Exception Class
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */
class DataTooLongException extends DatabaseException
{
    public function __construct(
        private string $columnName,
        private int $rowNumber,
        string $message = "",
        int $code = 0,
        \Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getColumnName() : string
    {
        return $this->columnName;
    }

    public function getRowNumber() : int
    {
        return $this->rowNumber;
    }
}
