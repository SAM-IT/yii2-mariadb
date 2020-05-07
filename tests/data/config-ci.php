<?php
declare(strict_types=1);

$config = [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=127.0.0.1;port=' .getenv('PORT'). ';dbname=yiitest',
    'username' => 'root',
    'password' => '',
    'schemaMap' => [
        'mysql' => \SamIT\Yii2\MariaDb\Schema::class
    ]
];
return $config;
