<?php
/**
 * contains a custom database interface class
 *
 * @package         davidlienhard/database
 * @author          David Lienhard <david@lienhard.win>
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use \DavidLienhard\Database\ParameterInterface;

/**
 * defines an interface to use for database connections
 *
 * @author          David Lienhard <david@lienhard.win>
 * @copyright       tourasia
 */
interface DatabaseInterface
{
    /**
     * connects to the database
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           string          $host           the hostname to connect
     * @param           string          $user           the username
     * @param           string          $pass           the password
     * @param           string          $dbname         the database
     * @param           int|null        $port           port to use to connect
     * @param           string          $charset        charset to use for the database connection
     * @param           string          $collation      collation to use for the database connection
     * @return          void
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        ?int $port = null,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci"
    ) : void;


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          void
     */
    public function reconnect() : void;


    /**
     * closes the database connection
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          void
      */
    public function close() : void;


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           bool            $mode           the new mode to set
     * @return          void
     */
    public function autocommit(bool $mode) : void;


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          void
     */
    public function begin_transaction() : void;


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          void
     */
    public function commit() : void;


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          void
     */
    public function rollback() : void;


    /**
     * Executes a query
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           string              $query        the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \DavidLienhard\Database\ResultInterface|bool
      */
    public function query(
        string $query,
        ParameterInterface ...$parameters
    ) : ResultInterface | bool;


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \DavidLienhard\Database\ResultInterface|bool
      */
    public function execute(ParameterInterface ...$parameters) : ResultInterface | bool;


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          int
     */
    public function affected_rows() : int;


    /**
     * escapes a string
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           string      $string      the string to escape
     * @return          string
     */
    public function esc(string $string) : string;


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          int
     */
    public function errno() : int;


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          string
     */
    public function errstr() : string;


    /**
     * returns the time used by the database
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          float
     */
    public function getDbTime() : float;



    /**
     * returns the number of queries executed
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @return          int
     */
    public function getTotalQueries() : int;
}
