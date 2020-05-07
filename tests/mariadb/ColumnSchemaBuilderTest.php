<?php
declare(strict_types=1);
namespace SamIT\Yii2\MariaDb\Tests;

use SamIT\Yii2\MariaDb\ColumnSchemaBuilder;
use yiiunit\data\ar\ActiveRecord;

/**
 * ColumnSchemaBuilderTest tests ColumnSchemaBuilder for MySQL.
 * @covers \SamIT\Yii2\MariaDb\ColumnSchemaBuilder
 */
class ColumnSchemaBuilderTest extends TestCase
{
    /**
     * @param string $type
     * @param int $length
     * @return ColumnSchemaBuilder
     */
    public function getColumnSchemaBuilder($type, $length = null)
    {
        return new ColumnSchemaBuilder($type, $length, $this->getConnection());
    }

    public function testToString(): void
    {
        /** @var \SamIT\Yii2\MariaDb\ColumnSchemaBuilder $builder */
        $builder = $this->getColumnSchemaBuilder('json');
        $this->assertInstanceOf(\SamIT\Yii2\MariaDb\ColumnSchemaBuilder::className(), $builder);
        $this->assertStringNotContainsString('{name}', $builder->toString('test'));
    }
}
