<?php
declare(strict_types=1);
use yii\db\mysql\ColumnSchemaBuilder;
use yii\db\Schema;

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
}
