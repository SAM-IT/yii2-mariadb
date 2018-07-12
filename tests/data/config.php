<?php
declare(strict_types=1);
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * This is the configuration file for the Yii 2 unit tests.
 *
 * You can override configuration values by creating a `config.local.php` file
 * and manipulate the `$config` variable.
 * For example to change MySQL username and password your `config.local.php` should
 * contain the following:
 * ```php
 * <?php
 * $config['databases']['mysql']['username'] = 'yiitest';
 * $config['databases']['mysql']['password'] = 'changeme';
 * ```
 */
$config = [
    'databases' => [
        'mysql' => [
            'dsn' => 'mysql:host=127.0.0.1;dbname=yiitest',
            'username' => 'travis',
            'password' => '',
            'fixture' => __DIR__ . '/mariadb.sql',
        ],
    ],
];

if (\is_file(__DIR__ . '/config.local.php')) {
    include __DIR__ . '/config.local.php';
}

return $config;
