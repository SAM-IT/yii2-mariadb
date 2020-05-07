<?php
declare(strict_types=1);
namespace SamIT\Yii2\MariaDb\Tests;

use SamIT\Yii2\MariaDb\ColumnSchemaBuilder;
use SamIT\Yii2\MariaDb\JsonExpressionBuilder;
use SamIT\Yii2\MariaDb\QueryBuilder;
use SamIT\Yii2\MariaDb\Schema;
use yii\db\Expression;

use yii\db\JsonExpression;
use yii\db\mysql\ColumnSchema;
use yiiunit\data\ar\Storage;
use yiiunit\framework\db\AnyCaseValue;

/**
 *
 * @covers \SamIT\Yii2\MariaDb\Schema
 */
class SchemaTest extends TestCase
{
    public function testCreateQueryBuilder()
    {
        $result = $this->getConnection()->getSchema()->createQueryBuilder();
        $this->assertInstanceOf(QueryBuilder::class, $result);
    }

    public function testGetJsonColumns()
    {
        /** @var Schema $schema */
        $schema = $this->getConnection()->getSchema();
        $jsonColumns = $schema->getJsonColumns(Storage::getTableSchema());
        $this->assertSame(['data', 'defaultValue1', 'defaultValue2', 'defaultValue3', 'defaultValue4', 'defaultValue5'], $jsonColumns);
    }

    public function testCreateColumnSchemaBuilder()
    {
        $this->assertInstanceOf(ColumnSchemaBuilder::class, $this->getConnection()->schema->createColumnSchemaBuilder(Schema::TYPE_PK));
    }
    public function testUnknownTableSchema()
    {
        $this->assertNull($this->getConnection()->schema->getTableSchema('abc'));
    }

    public function testColumnWithDefaultTimestamp()
    {
        $typeSchema = $this->getConnection()->schema->getTableSchema('type');
        $column = $typeSchema->getColumn('ts_default');
        $this->assertInstanceOf(ColumnSchema::class, $column);
        $this->assertInstanceOf(Expression::class, $column->defaultValue);
    }
}
