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
To use the MariaDB `Schema` implementation there are several approaches.

## Override the schema class used for MySQL
Update the `schemaMap` property in your `Connection` config (the drivername is still `mysql` since we use the MySQL PDO driver) (RECOMMENDED)

```php
'db' => [
    'class' => Connection::class,
    'schemaMap' => [
        'mysql' => SamIT\Yii2\MariaDb\Schema::class
    ]
]
```

## Add schema class to schemaMap
Append the new Schema class to the `schemaMap` property and set the `driverName` property manually.

```php
'db' => [
    'class' => Connection::class,
    'driverName' => 'mariadb',
    'schemaMap' => [
        'mariadb' => SamIT\Yii2\MariaDb\Schema::class
    ]
]
```

# JSON Column detection
Since MariaDB has no built-in JSON data type we need to do some extra work to detect JSON columns.
We do this by parsing the SQL obtained when using `SHOW CREATE TABLE`. Since MariaDB supports `CHECK` constraints these are used to ensure a column can only contain valid JSON.
Any constraint that of the form: ``json_valid(`column1`)`` will identify the column as JSON. Note that this could lead to problems if you have weird constraints, consider this:
```sql
`column1` longtext CHECK(not json_valid(`column1`));
```
Will mark `column1` as a JSON column.

# Column creation
When creating JSON columns the `ColumnSchemaBuilder` requires the name of the column to add the table constraint.
Since this is not the case for all other column types Yii does not pass the name of the column to the builder.
Consider this code, for example in a migration:

```php
$this->alterColumn('{{test}}', 'field1', $this->json());
```

Here there is no way for the `ColumnSchemaBuilder` to know what the name of the column is going to be.
Since the schema builder is ultimately passed to `QueryBuilder::alterColumn()`, we can intercept it there and replace the column name in the constraint.

If you coerce the `ColumnSchemaBuilder` to string early, or use it without the `QueryBuilder` you will end up with SQL like this:
```php
ALTER COLUMN `field` JSON CHECK(json_valid({name}));
```
That will clearly not work.
For those cases we have added a `toString(string $columnName)` method to the builder.
```php
// Will result in broken SQL.
$this->alterColumn('{{test}}', 'field1', $this->json() . ' --APPEND SOMETHING');
// Will result in working SQL.
$this->alterColumn('{{test}}', 'field1', $this->json()->toString('field1') . ' --APPEND SOMETHING');
```
