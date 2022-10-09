<?php declare(strict_types=1);

/**
 * contains a custom mysql class
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */

namespace DavidLienhard\Database;

use DavidLienhard\Database\DatabaseInterface;
use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\ParameterInterface;
use DavidLienhard\FunctionCaller\Call as FunctionCaller;
use function count;
use function implode;
use function ini_get;
use function microtime;
use function mysqli_get_client_info;
use function mysqli_report;
use function preg_replace;
use function str_replace;
use function strlen;
use function substr;
use function trim;

/**
 * Methods for a comfortable use of the {@link http://www.mysql.com mySQL} database
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */
class Mysqli implements DatabaseInterface
{
    /** defines whether connect() has been used yet */
    private bool $isConnected = false;

    /** The Database connection resource */
    private \mysqli $mysqli;

    /** The miliseconds used by the database */
    private float $dbTime = 0;

    /** The number of queries */
    private int $totalQueries = 0;

    /** contains infos about the client */
    private string $client_info = "";

    /** contains infos about the host */
    private string $host_info = "";

    /** contains infos about the protocol */
    private int $proto_info = 0;

    /** contains infos about the server */
    private string $server_info = "";

    /** host to connect to */
    private string $host;

    /** username to use to connect */
    private string $user;

    /** password to use to connect */
    private string $pass;

    /** the name of the selected database */
    private string $dbname;

    /** port to connect to */
    private int|null $port;

    /** charset to use to connect */
    private string $charset;

    /** collation to use to connect */
    private string $collation;

    /** the last statement from the query */
    private \mysqli_stmt|false $stmt;

    /** the last query that was executed */
    private string $lastquery = "";


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
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function connect(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        int|null $port = null,
        string $charset = "utf8mb4",
        string $collation = "utf8mb4_unicode_ci"
    ) : void {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);      // set mysqli to throw exceptions

            $iniPort = ini_get("mysqli.default_port");
            $iniPort = $iniPort === false ? null : intval($iniPort);

            $this->mysqli = new \mysqli(                                    // connect to database
                $host,
                $user,
                $pass,
                $dbname,
                $port ?? $iniPort ?? 3306
            );

            $this->isConnected = true;
            $this->mysqli->set_charset($charset);                               // set charset
            $this->query("SET NAMES '".$charset."' COLLATE '".$collation."'");  // set charset / collation

            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->dbname = $dbname;
            $this->port = $port;
            $this->charset = $charset;
            $this->collation = $collation;

            $this->client_info = $this->mysqli->client_info;
            $this->host_info = $this->mysqli->host_info;
            $this->proto_info = (int) $this->mysqli->protocol_version;
            $this->server_info = $this->mysqli->server_info;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }//end try
    }


    /**
     * reconnects to the database server
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function reconnect() : void
    {
        $this->checkConnected();

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
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function close() : void
    {
        $this->checkConnected();

        try {
            $this->client_info = $this->host_info = $this->server_info = "";
            $this->proto_info = 0;
            $result = $this->mysqli->close();

            if ($result === false) {
                throw new DatabaseException("unable to close connection to database");
            }
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * changes the mode of autocommit
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           bool            $mode           the new mode to set
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function autocommit(bool $mode) : void
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->autocommit($mode);
            $this->dbTime = $this->dbTime + microtime(true) - $dbStart;

            if ($result === false) {
                throw new DatabaseException("unable to change autocommit mode");
            }
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * Starts a transaction
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function begin_transaction() : void
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->begin_transaction();
            $this->dbTime = $this->dbTime + microtime(true) - $dbStart;

            if ($result === false) {
                throw new DatabaseException("unable to start transaction to database");
            }
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * Commits a transaction
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function commit() : void
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->commit();
            $this->dbTime = $this->dbTime + microtime(true) - $dbStart;

            if ($result === false) {
                throw new DatabaseException("unable to commit transaction to database");
            }
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * Rolls a transaction back
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function rollback() : void
    {
        $this->checkConnected();

        try {
            $dbStart = microtime(true);
            $result = $this->mysqli->rollback();
            $this->dbTime = $this->dbTime + microtime(true) - $dbStart;

            if ($result === false) {
                throw new DatabaseException("unable to rollback transaction to database");
            }
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * Executes a query
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string              $query       the sql query
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function query(string $query, ParameterInterface ...$parameters) : MysqliResult|bool
    {
        $this->checkConnected();

        $dbStart = microtime(true);

        if ($query === $this->lastquery && count($parameters) !== 0) {
            return $this->execute(...$parameters);
        }

        try {
            if (count($parameters) === 0) {
                $this->stmt = $this->mysqli->prepare($query);

                if ($this->stmt === false) {
                    throw new DatabaseException("unable to prepare query");
                }

                $this->stmt->execute();
                $result = $this->stmt->get_result();
                if ($result instanceof \mysqli_result) {
                    $result = new MysqliResult($result);
                }
                $this->lastquery = $query;
            } else {
                $types = "";
                $values = [];
                foreach ($parameters as $parameter) {
                    $types .= $parameter->getType();
                    $values[] = $parameter->getValue();
                }

                $this->stmt = $this->mysqli->prepare($query);

                if ($this->stmt === false) {
                    throw new DatabaseException("unable to prepare query");
                }

                $this->stmt->bind_param($types, ...$values);
                $this->stmt->execute();
                $result = $this->stmt->get_result();
                if ($result instanceof \mysqli_result) {
                    $result = new MysqliResult($result);
                }
                $this->lastquery = $query;
            }//end if

            $this->dbTime = $this->dbTime + microtime(true) - $dbStart;
            $this->totalQueries++;

            return $result;
        } catch (\mysqli_sql_exception $e) {
            // create error message with given parameters
            $message = "error in mysql query: ".$e->getMessage();
            if (count($parameters) > 0) {
                $message .= "\n\tparameters given:\n\t";
                $message .= implode(
                    "\n\t",
                    array_map(
                        fn ($p) => " - ".$p->getType().": '".self::formatParameter(strval($p->getValue()))."'",
                        $parameters
                    )
                );
                $message .= "\n\t";
            }

            throw new DatabaseException(
                $message,
                intval($e->getCode()),
                $e
            );
        }//end try
    }


    /**
     * executes an already prepared statement
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           \DavidLienhard\Database\ParameterInterface  $parameters  parameters to add to the query
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function execute(ParameterInterface ...$parameters) : MysqliResult|bool
    {
        $this->checkConnected();

        if (!($this->stmt instanceof \mysqli_stmt)) {
            throw new DatabaseException("saved statement is invalid");
        }

        try {
            $types = "";
            $values = [];
            foreach ($parameters as $parameter) {
                $types .= $parameter->getType();
                $values[] = $parameter->getValue();
            }

            $stmt = $this->stmt;
            if (strlen($types) > 0 && count($values) > 0) {
                $stmt->bind_param($types, ...$values);
            }
            $stmt->execute();
            $result = $this->stmt->get_result();

            return $result instanceof \mysqli_result
                ? new MysqliResult($result)
                : $result;
        } catch (\mysqli_sql_exception $e) {
            // create error message with given parameters
            $message = "error in mysql query: ".$e->getMessage();
            if (count($parameters) > 0) {
                $message .= "\n\tparameters given:\n\t";
                $message .= implode(
                    "\n\t",
                    array_map(
                        fn ($p) => " - ".$p->getType().": '".substr(str_replace("\r\n", " ", (string) $p->getValue()), 0, 100)."'",
                        $parameters
                    )
                );
                $message .= "\n\t";
            }

            throw new DatabaseException(
                $message,
                intval($e->getCode()),
                $e
            );
        }//end try
    }


    /**
     * check if the connection to the server is still open
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function ping() : void
    {
        $this->checkConnected();

        try {
            $caller = new FunctionCaller([ $this->mysqli, "ping" ]);
            $result = $caller->getResult();

            if ($result === false) {
                $error = $caller->getLastError()?->getErrstr();
                throw new DatabaseException("unable to ping database host: ".$error);
            }
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * returns the id of the last inserted row
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function insert_id() : int|string
    {
        $this->checkConnected();

        $id = $this->mysqli->insert_id;

        if ($id === 0) {
            throw new DatabaseException(
                "no auto increment id is available at this point",
            );
        }

        return $id;
    }


    /**
     * returns the number of affected rows
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function affected_rows() : int
    {
        $this->checkConnected();

        return intval($this->mysqli->affected_rows);
    }


    /**
     * escapes a string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string      $string      the string to escape
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function escape(string $string) : string
    {
        $this->checkConnected();

        try {
            return $this->mysqli->real_escape_string($string);
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }
    }


    /**
     * returns the client info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function client_info() : string
    {
        $this->checkConnected();

        return $this->client_info;
    }


    /**
     * returns the host info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function host_info() : string
    {
        $this->checkConnected();

        return $this->host_info;
    }


    /**
     * returns the proto info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function proto_info() : int
    {
        $this->checkConnected();

        return $this->proto_info;
    }


    /**
     * returns the server info
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function server_info() : string
    {
        $this->checkConnected();

        return $this->server_info;
    }


    /**
     * returns the size of the db
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string|null      $dbname         optional mysqli connection
     * @throws          \Exception if no database name is set
     * @throws          \DavidLienhard\Database\Exception if any mysqli function failed
     */
    public function size(string|null $dbname = null) : int
    {
        $this->checkConnected();

        try {
            if ($dbname === null) {
                if (empty($this->dbname)) {
                    throw new \Exception("no database name ist set");
                }

                $dbname = $this->dbname;
            }

            $result = $this->query("SHOW TABLE STATUS FROM `".$dbname."`");

            if (!($result instanceof MysqliResult)) {
                throw new DatabaseException("unable to fetch tables in database");
            }

            $size = 0;
            while ($data = $result->fetch_assoc()) {
                $size += intval($data['Data_length']) + intval($data['Index_length']);
            }

            return $size;
        } catch (\mysqli_sql_exception $e) {
            throw new DatabaseException(
                $e->getMessage(),
                intval($e->getCode()),
                $e
            );
        }//end try
    }


    /**
     * returns the latest error number
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function errno() : int
    {
        $this->checkConnected();

        return $this->mysqli->errno;
    }


    /**
     * returns the latest error string
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function errstr() : string
    {
        $this->checkConnected();

        return $this->mysqli->error;
    }


    /**
     * shortens the parameter value to be printed as exception
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string      $value          value to format as string
     */
    private static function formatParameter(string $value) : string
    {
        $value = preg_replace("/\s\s+/", " ", $value) ?? $value;
        return trim(substr($value, 0, 100));
    }


    /**
     * throws an exception if connect() has not been used yet
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    private function checkConnected() : void
    {
        if (!$this->isConnected) {
            throw new \BadMethodCallException("this ".__CLASS__." object is no connected yet. use connect() first");
        }
    }


    /**
     * returns the time used by the database
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getDbTime() : float
    {
        return $this->dbTime;
    }


    /**
     * returns the number of queries executed
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getTotalQueries() : int
    {
        return $this->totalQueries;
    }
}
