<?php
declare(strict_types=1);
namespace SamIT\Yii2\MariaDb\Tests;

use SamIT\Yii2\MariaDb\ColumnSchemaBuilder;

/**
 * ColumnSchemaBuilderTest tests ColumnSchemaBuilder for MySQL.
 * @group db
 * @group mysql
 */
class ColumnSchemaBuilderTest extends \yiiunit\framework\db\mysql\ColumnSchemaBuilderTest
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
        $this->assertNotContains('{name}', $builder->toString('test'));
    }
}
