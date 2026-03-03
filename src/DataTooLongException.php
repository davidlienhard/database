<?php declare(strict_types=1);

/**
 * Database Data Too Long Exception Class
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */

namespace DavidLienhard\Database;

use DavidLienhard\Database\Exception as DatabaseException;

/**
 * Database Exception Class
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */
class DataTooLongException extends DatabaseException
{
    public function __construct(
        string $message = "",
        int $code = 0,
        \Throwable $previous,
        private string $columnName,
        private int $rowNumber
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
