# davidlienhard/database
🐘 php library for easy access to databases

[![Latest Stable Version](https://img.shields.io/packagist/v/davidlienhard/database.svg?style=flat-square)](https://packagist.org/packages/davidlienhard/database)
[![Source Code](https://img.shields.io/badge/source-davidlienhard/database-blue.svg?style=flat-square)](https://github.com/davidlienhard/database)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/davidlienhard/database/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.4-8892BF.svg?style=flat-square)](https://php.net/)
[![CI Status](https://github.com/davidlienhard/database/actions/workflows/check.yml/badge.svg)](https://github.com/davidlienhard/database/actions/workflows/check.yml)

## Setup

You can install through `composer` with:

```
composer require davidlienhard/database:^3
```

*Note: davidlienhard/database requires PHP 8.4*

## Examples

### Connect to the Database-Server
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exceptions\Exception as DatabaseException;
use DavidLienhard\Database\Mysqli;

try {
    $db = new Mysqli;
    $db->connect("hostname", "username", "password", "dbname");
} catch (DatabaseException $e) {
    echo "unable to connect to the database host";
    exit(1);
}
```

### Fetch Results as Objects (Recommended)

#### Fetching Multiple Rows as Objects
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Mysqli;

$userResult = $db->query(
    "SELECT
        `userID`,
        `userName`,
        `userLevel`
    FROM
        `user`"
);

while ($userData = $userResult->fetch_object()) {
    echo $userData->get('userID').": ".$userData->get('userName').PHP_EOL;
}
```

#### Fetching a Single Row as Object
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exceptions\NoRowsException;
use DavidLienhard\Database\Mysqli;

try {
    $userResult = $db->query(
        "SELECT
            `userID`,
            `userName`,
            `userLevel`
        FROM
            `user`
        WHERE
            `userID` = ?",
        new DBParam("i", $userId)
    );

    $user = $userResult->fetch_single_object();
    echo $user->get('userName');
} catch (NoRowsException $e) {
    echo "user not found";
}
```

### Fetch Results as Arrays

#### Fetching Multiple Rows as Associative Arrays
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Mysqli;

$userResult = $db->query(
    "SELECT
        `userID`,
        `userName`
    FROM
        `user`"
);

while ($userData = $userResult->fetch_array_assoc()) {
    echo $userData['userID'].": ".$userData['userName'].PHP_EOL;
}
```

#### Fetching a Single Row as Associative Array
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exceptions\NoRowsException;
use DavidLienhard\Database\Mysqli;

try {
    $userResult = $db->query(
        "SELECT
            `userID`,
            `userName`
        FROM
            `user`
        WHERE
            `userID` = ?",
        new DBParam("i", $userId)
    );

    $user = $userResult->fetch_single_array_assoc();
    echo $user['userName'];
} catch (NoRowsException $e) {
    echo "user not found";
}
```

### Select Query with Parameters
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Mysqli;
use DavidLienhard\Database\Parameter as DBParam;

$userResult = $db->query(
    "SELECT
        `userID`,
        `userName`
    FROM
        `user`
    WHERE
        `userLevel` = ? and
        `userType` = ?",
    new DBParam("i", $userLevel),
    new DBParam("s", $userType)
);

while ($user = $userResult->fetch_object()) {
    echo $user->get('userID').": ".$user->get('userName').PHP_EOL;
}
```

### Insert Query
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exceptions\Exception as DatabaseException;
use DavidLienhard\Database\Mysqli;
use DavidLienhard\Database\Parameter as DBParam;

try {
    $db->query(
        "INSERT INTO
            `user`
        SET
            `userName` = ?,
            `userLevel` = ?,
            `userType` = ?",
        new DBParam("s", $userName),
        new DBParam("i", $userLevel),
        new DBParam("s", $userType)
    );
} catch (DatabaseException $e) {
    echo "unable to insert into table";
    exit(1);
}
```

### Update Query
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exceptions\Exception as DatabaseException;
use DavidLienhard\Database\Mysqli;
use DavidLienhard\Database\Parameter as DBParam;

try {
    $db->query(
        "UPDATE
            `user`
        SET
            `userName` = ?,
            `userLevel` = ?
        WHERE
            `userID` = ?",
        new DBParam("s", $userName),
        new DBParam("i", $userLevel),
        new DBParam("i", $userId)
    );
} catch (DatabaseException $e) {
    echo "unable to update table";
    exit(1);
}
```

### Transactions
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Mysqli;
use DavidLienhard\Database\Parameter as DBParam;

try {
    $db->begin_transaction();

    $db->query(
        "INSERT INTO `user` SET `userName` = ?",
        new DBParam("s", $userName)
    );

    $db->query(
        "INSERT INTO `user_log` SET `action` = ?",
        new DBParam("s", "user_created")
    );

    $db->commit();
} catch (Exception $e) {
    $db->rollback();
    echo "transaction failed";
}
```

## API Overview

### Fetch Methods

- **`fetch_object(ResultType $resultType = assoc): RowInterface|null`** - Returns a `RowInterface` object that wraps the row data. Use `$row->get('columnName')` or `$row->getAll()` to access data.
- **`fetch_single_object(ResultType $resultType = assoc): RowInterface`** - Like `fetch_object()` but throws `NoRowsException` if no rows are available.
- **`fetch_array_assoc(): array|null`** - Returns an associative array with column names as keys.
- **`fetch_single_array_assoc(): array`** - Like `fetch_array_assoc()` but throws `NoRowsException` if no rows are available.
- **`fetch_array_num(): array|null`** - Returns an enumerated array with numeric indices.
- **`fetch_single_array_num(): array`** - Like `fetch_array_num()` but throws `NoRowsException` if no rows are available.
- **`fetch_array(ResultType $resultType): array|null`** - Generic fetch returning either associative or numeric arrays based on `$resultType`.

### Deprecated Methods

The following methods are deprecated and should not be used in new code:

- `fetch_assoc()` - Use `fetch_array_assoc()` instead
- `fetch_row()` - Use `fetch_array_num()` instead

## License

The MIT License (MIT). Please see [LICENSE](https://github.com/davidlienhard/database/blob/master/LICENSE) for more information.
