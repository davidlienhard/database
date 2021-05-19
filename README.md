# davidlienhard/database
🐘 php library for easy access to databases

[![Latest Stable Version](https://img.shields.io/packagist/v/davidlienhard/database.svg?style=flat-square)](https://packagist.org/packages/davidlienhard/database)
[![Source Code](https://img.shields.io/badge/source-davidlienhard/database-blue.svg?style=flat-square)](https://github.com/davidlienhard/database)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/davidlienhard/database/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg?style=flat-square)](https://php.net/)
[![CI Status](https://github.com/davidlienhard/database/actions/workflows/check.yml/badge.svg)](https://github.com/davidlienhard/database/actions/workflows/check.yml)

## Setup

You can install through `composer` with:

```
composer require davidlienhard/database:^2
```

*Note: davidlienhard/database requires PHP 8.0*

## Examples

### Connect to the Database-Server
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exception as DatabaseException;
use DavidLienhard\Database\Mysqli;

try {
    $db = new Mysqli;
    $db->connect("hostname", "username", "password", "dbname");
} catch (DatabaseException $e) {
    echo "unable to connect to the database host";
    exit(1);
}
```

### Simple Select Query
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

while ($userData = $userResult->fetch_assoc()) {
    echo $userData['userID'].": ".$userData['userName'].PHP_EOL;
}
```

### Select Query with User-Data
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

while ($userData = $userResult->fetch_assoc()) {
    echo $userData['userID'].": ".$userData['userName'].PHP_EOL;
}
```

### Insert-Query
```php
<?php declare(strict_types=1);
use DavidLienhard\Database\Exception as DatabaseException;
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
    echo "unable to update table";
    exit(1);
}
```

## License

The MIT License (MIT). Please see [LICENSE](https://github.com/davidlienhard/database/blob/master/LICENSE) for more information.
