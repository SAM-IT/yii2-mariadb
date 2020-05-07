<?php
declare(strict_types=1);

namespace SamIT\Yii2\MariaDb\Tests;

use SamIT\Yii2\MariaDb\ColumnSchemaBuilder;
use SamIT\Yii2\MariaDb\JsonExpressionBuilder;
use SamIT\Yii2\MariaDb\QueryBuilder as QueryBuilder;
use yii\db\JsonExpression;
use yii\db\Schema;
use yiiunit\data\ar\Storage;

/**
 * Class QueryBuilderTest
 * @package SamIT\Yii2\MariaDb\Tests
 * @covers \SamIT\Yii2\MariaDb\QueryBuilder
 */
class QueryBuilderTest extends TestCase
{
    public function testGetColumnType()
    {
        $builder = $this->getConnection()->getQueryBuilder();

        /** @var ColumnSchemaBuilder $columnSchemaBuilder */
        $columnSchemaBuilder = $this->getConnection()->getSchema()->createColumnSchemaBuilder(Schema::TYPE_JSON);
        $this->assertInstanceOf(ColumnSchemaBuilder::class, $columnSchemaBuilder);
        $this->assertTrue($columnSchemaBuilder->isJson());
        $this->assertSame(Schema::TYPE_JSON, $builder->getColumnType($columnSchemaBuilder));
    }

    public function testAlterColumn()
    {
        /** @var ColumnSchemaBuilder $columnSchemaBuilder */
        $columnSchemaBuilder = $this->getConnection()->getSchema()->createColumnSchemaBuilder(Schema::TYPE_JSON);

        $builder = $this->getConnection()->getQueryBuilder();

        $sql = $builder->alterColumn(Storage::tableName(), 'id', $columnSchemaBuilder);
        $this->assertStringEndsWith($columnSchemaBuilder->toString('id'), $sql);
    }

    public function testAddColumn()
    {
        /** @var ColumnSchemaBuilder $columnSchemaBuilder */
        $columnSchemaBuilder = $this->getConnection()->getSchema()->createColumnSchemaBuilder(Schema::TYPE_JSON);

        $builder = $this->getConnection()->getQueryBuilder();

        $sql = $builder->addColumn(Storage::tableName(), 'abc', $columnSchemaBuilder);
        $this->assertStringEndsWith($columnSchemaBuilder->toString('abc'), $sql);
    }

    public function testCreateTable()
    {
        /** @var ColumnSchemaBuilder $columnSchemaBuilder */
        $columnSchemaBuilder = $this->getConnection()->getSchema()->createColumnSchemaBuilder(Schema::TYPE_JSON);

        $builder = $this->getConnection()->getQueryBuilder();

        $sql = $builder->createTable(Storage::tableName(), [
            'abc' => $columnSchemaBuilder
        ]);
        $this->assertStringContainsString($columnSchemaBuilder->toString('abc'), $sql);
    }

    public function testExpressionBuilders(): void
    {
        $builder = new QueryBuilder($this->getConnection());
        $expression = new JsonExpression(['abc' => 'def']);
        $this->assertInstanceOf(JsonExpressionBuilder::class, $builder->getExpressionBuilder($expression));
    }
}
