<?php
declare(strict_types=1);
namespace SamIT\Yii2\MariaDb\Tests;

/**
 * @group db
 * @group mysql
 * @group validators
 */
class UniqueValidatorTest extends \yiiunit\framework\validators\UniqueValidatorTest
{
    public $driverName = 'mysql';
}
