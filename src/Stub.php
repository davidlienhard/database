<?php
/**
 * contains a stub for database interface
 *
 * @package         tourBase
 * @author          David Lienhard <david.lienhard@tourasia.ch>
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use \DavidLienhard\Database\DatabaseInterface;
use \DavidLienhard\Database\ParameterInterface;

/**
 * stub for \DavidLienhard\Database\DatabaseInterface
 *
 * @category        Database
 * @author          David Lienhard <david.lienhard@tourasia.ch>
 * @copyright       tourasia
 */
class Stub implements DatabaseInterface
{
    /** host to connect to */
    private string $host;

    /** username to use to connect */
    private string $user;

    /** password to use to connect */
    private string $pass;

    /** the name of the selected database */
    private string $dbname;

    /** port to connect to */
    private ?int $port;

    /** charset to use to connect */
    private string $charset;

    /** collation to use to connect */
    private string $collation;

    /**
     * the payload to use in the config
     * @var     mixed[]
     */
    private array $payload = [];

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
     * @param           string          $collation      encoding to use for the database connection
     * @uses            self::$host
     * @uses            self::$user
     * @uses            self::$pass
     * @uses            self::$dbname
     * @uses            self::$port
     * @uses            self::$charset
     * @uses            self::$collation
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        ?int $port = null,
        string $charset = "utf8mb4_unicode_ci",
        string $collation = "utf8"
    ) : void {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->port = $port;
        $this->charset = $charset;
        $this->collation = $collation;

        return;
    }


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     * @uses            self::connect()
     * @uses            self::$host
     * @uses            self::$user
     * @uses            self::$pass
     * @uses            self::$dbname
     * @uses            self::$port
     * @uses            self::$charset
     * @uses            self::$collation
     * @uses            self::checkConnected()
     */
    public function reconnect() : void
    {
        $this->connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname,
            $this->port,
            $this->charset,
            $this->collation
        );
    }


    /**
     * closes the database connection
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function close() : void
    {
        return;
    }


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           bool            $mode           the new mode to set
     */
    public function autocommit(bool $mode) : void
    {
        return;
    }


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function begin_transaction() : void
    {
        return;
    }


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function commit() : void
    {
        return;
    }


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function rollback() : void
    {
        return;
    }


    /**
     * Executes a query
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           string              $query        the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \DavidLienhard\Database\ResultInterface|bool
     */
    public function query(string $query, ParameterInterface ...$parameters) : ResultInterface | bool
    {
        return true;
    }


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @return          \DavidLienhard\Database\ResultInterface|bool
     */
    public function execute(ParameterInterface ...$parameters) : ResultInterface | bool
    {
        return true;
    }


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function insert_id() : int
    {
        return 1;
    }


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function affected_rows() : int
    {
        return 1;
    }


    /**
     * escapes a string
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           string      $string      the string to escape
     */
    public function esc(string $string) : string
    {
        return $string;
    }


    /**
     * returns the client info
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function client_info() : string
    {
        return "client info";
    }


    /**
     * returns the host info
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function host_info() : string
    {
        return "host info";
    }


    /**
     * returns the proto info
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function proto_info() : int
    {
        return 1;
    }


    /**
     * returns the server info
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function server_info() : string
    {
        return "server info";
    }


    /**
     * returns the size of the db
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           string|null     $dbname         optional mysqli connection
     */
    public function size(?string $dbname = null) : int
    {
        return 1;
    }


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function errno() : int
    {
        return 1;
    }


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function errstr() : string
    {
        return "error";
    }

    /**
     * adds payload to the object
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     * @param           mixed[]         $payload        the payload to add
     */
    public function addPayload(array $payload) : void
    {
        $this->payload = $payload;
    }

    /**
     * returns the time used by the database
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getDbTime() : float
    {
        return 1;
    }

    /**
     * returns the number of queries executed
     *
     * @author          David Lienhard <david@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getTotalQueries() : int
    {
        return 1;
    }
}
