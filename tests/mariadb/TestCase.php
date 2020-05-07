<?php
declare(strict_types=1);

namespace SamIT\Yii2\MariaDb\Tests;

use yii\db\ActiveRecord;
use yii\db\Connection;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public function getConnection(): Connection
    {
        return \yiiunit\data\ar\ActiveRecord::getDb();
    }
    public static function initDatabase(array $config)
    {
        \yiiunit\data\ar\ActiveRecord::$db = \Yii::createObject($config);
    }
}
