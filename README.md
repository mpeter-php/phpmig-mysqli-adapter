# phpmig-mysqli-adapter
A Phpmig MySQLi Adapter

## Getting started

```
$ php composer.phar require mpeter-php/phpmig-mysqli-adapter
```

## Examples

#### Non-CLI example
```php
<?php

require_once('vendor/autoload.php');

use Phpmig\Adapter\MysqlIAdapter;
use Phpmig\Api\PhpmigApplication;
use Symfony\Component\Console\Output\NullOutput;

$mysqli = new mysqli('db_host', 'db_user', 'db_pass', 'my_database');

(new PhpmigApplication(
    new ArrayObject(
        [
            'phpmig.migrations_path' => __DIR__ . DIRECTORY_SEPARATOR . 'migrations',
            'phpmig.adapter'         => new MysqlIAdapter($mysqli, 'migrations'),
            'db'                     => $mysqli
        ]
    ), new NullOutput()
))->up();

```

#### CLI Example
```php
<?php
/**
 * phpmig.php
 */

use Phpmig\Adapter;

$mysqli = new mysqli('db_host', 'db_user', 'db_pass', 'my_database');
return new ArrayObject(
    [
        [
            'phpmig.migrations_path' => __DIR__ . DIRECTORY_SEPARATOR . 'migrations',
            'phpmig.adapter'         => new Adapter\MysqlIAdapter($mysqli, 'migrations'),
            'db'                     => $mysqli
        ]
    ]
);

```