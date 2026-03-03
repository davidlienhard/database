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

    /**
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string          $message        message to be thrown by the exception
     * @param           int             $code           error code
     * @param           Throwable|null  $previous       previous exception
     * @param           string          $columnName     name of the column
     * @param           int             $rowNumber      number of the row
     */
    public function __construct(
        $message = "",
        $code = 0,
        Throwable|null $previous = null,
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
