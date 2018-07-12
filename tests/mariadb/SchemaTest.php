<?php
declare(strict_types=1);
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yiiunit\framework\db\mysql;
use yii\db\Expression;

use yiiunit\framework\db\AnyCaseValue;

/**
 * @group db
 * @group mysql
 */
class SchemaTest extends \yiiunit\framework\db\SchemaTest
{
    public $driverName = 'mysql';

    public function testLoadDefaultDatetimeColumn(): void
    {
        $sql = <<<SQL
CREATE TABLE  IF NOT EXISTS `datetime_test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
SQL;

        $this->getConnection()->createCommand($sql)->execute();

        $schema = $this->getConnection()->getTableSchema('datetime_test');

        $dt = $schema->columns['dt'];

        $this->assertInstanceOf(Expression::className(),$dt->defaultValue);
    }

    public function testGetSchemaNames(): void
    {
        $this->markTestSkipped('Schemas are not supported in MariaDB.');
    }

    public function constraintsProvider()
    {
        $result = parent::constraintsProvider();
        $result['1: check'][2] = false;

        $result['2: primary key'][2]->name = null;
        $result['2: check'][2] = false;

        // Work aroung bug in MySQL 5.1 - it creates only this table in lowercase. O_o
        $result['3: foreign key'][2][0]->foreignTableName = new AnyCaseValue('T_constraints_2');
        $result['3: check'][2] = false;

        $result['4: check'][2] = false;
        return $result;
    }

    public function getExpectedColumns()
    {
        $result = parent::getExpectedColumns();
        $result['ts_default']['defaultValue'] = new Expression('current_timestamp()');
        return $result;
    }
}
