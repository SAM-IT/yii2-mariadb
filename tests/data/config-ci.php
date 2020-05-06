<?php
declare(strict_types=1);

return \yii\helpers\ArrayHelper::merge(require 'config-docker.php', [
    'databases' => [
        'mysql' => [
            'dsn' => 'mysql:host=127.0.0.1;port=' .getenv('PORT'). ';dbname=yiitest',
        ]
    ]
]);
