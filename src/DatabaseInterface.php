<?php
/**
 * contains a custom database interface class
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\ParameterInterface;

/**
 * defines an interface to use for database connections
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */
interface DatabaseInterface
{
    /**
     * connects to the database
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string          $host           the hostname to connect
     * @param           string          $user           the username
     * @param           string          $pass           the password
     * @param           string          $dbname         the database
     * @param           int|null        $port           port to use to connect
     * @param           string          $charset        charset to use for the database connection
     * @param           string          $collation      collation to use for the database connection
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        int|null $port = null,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci"
    ) : void;


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function reconnect() : void;


    /**
     * closes the database connection
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
      */
    public function close() : void;


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           bool            $mode           the new mode to set
     */
    public function autocommit(bool $mode) : void;


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function begin_transaction() : void;


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function commit() : void;


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function rollback() : void;


    /**
     * Executes a query
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string              $query        the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
      */
    public function query(
        string $query,
        ParameterInterface ...$parameters
    ) : ResultInterface|bool;


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
      */
    public function execute(ParameterInterface ...$parameters) : ResultInterface|bool;


    /**
     * check if the connection to the server is still open
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function ping() : void;


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function insert_id() : int|string;


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function affected_rows() : int;


    /**
     * escapes a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string      $string      the string to escape
     */
    public function escape(string $string) : string;


    /**
     * returns the client info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function client_info() : string;


    /**
     * returns the host info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function host_info() : string;


    /**
     * returns the proto info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function proto_info() : int;


    /**
     * returns the server info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function server_info() : string;


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function errno() : int;


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function errstr() : string;


    /**
     * returns the time used by the database
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getDbTime() : float;


    /**
     * returns the number of queries executed
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getTotalQueries() : int;
}
