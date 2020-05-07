<?php
declare(strict_types=1);

$config = [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=mariadb;dbname=yiitest',
    'username' => 'root',
    'password' => '',
    'schemaMap' => [
        'mysql' => \SamIT\Yii2\MariaDb\Schema::class
    ]
];
return $config;
