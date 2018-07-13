[![Latest Stable Version](https://img.shields.io/packagist/v/SAM-IT/yii2-mariadb.svg)](https://packagist.org/packages/sam-it/yii2-mariadb)
[![Total Downloads](https://img.shields.io/packagist/dt/SAM-IT/yii2-mariadb.svg)](https://packagist.org/sam-it/yii2-mariadb)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/?branch=master)
[![Build Status](https://travis-ci.org/SAM-IT/yii2-mariadb.svg?branch=master)](https://travis-ci.org/SAM-IT/yii2-mariadb)

# yii2-mariadb
While Yii2 supports MariaDB through its MySQL driver, the differences between MariaDB and MySQL are increasing.
At this time the driver included in Yii2 will not properly detect `JSON` columns in MariaDB and will not properly store
data in them.

The goal of this library is to implement the MariaDB specific changes required to get all features working in MariaDB that
are supported in the Yii2 core library for other DBMSes.

# Tests
The tests coverage is really high due to 2 reasons:
- All code extends their MySQL counter parts in the framework, only very little is added.
- We run the core tests for Yii2 (with some minor changes) to guarantee interoperability with the framework.

# Usage
To use the MariaDB `Schema` implementation there are 2 approaches:
- Update the `schemaMap` property in your `Connection` config (the drivername is still `mysql` since we use the MySQL PDO driver) (RECOMMENDED)

```php
'db' => [
    'class' => Connection::class,
    'schemaMap' => [
        'mysql' => SamIT\Yii2\MariaDb\Schema::class
    ]
]
```

- Add the `MariaDbBehavior` to your `Connection`.
```php
'db' => [
    'class' => Connection::class',
    'as mariadb' => \SamIT\Yii2\MariaDb\MariaDbBehavior::class
]
```

## Behavior method
The behavior will register a handler for the `EVENT_AFTER_OPEN` on the connection.
When a connection opens it will check the PDO attribute(s) to see if it's a MariaDB connection.
If that's the case then it will update the `$schemaMap` property on the connection.

While the behavior method in theory allows you to use the connection without knowing the database type in advance, it has some disadvantages.
Specifically the `Connection` class might instantiate a the `Schema` before opening the connection. This happens when a query builder is requested before the database connection is opened.
If you run into issues related to SQL syntax please try the first approach to see if that resolves the issue.

