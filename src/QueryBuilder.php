<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;

use yii\db\JsonExpression;

class QueryBuilder extends \yii\db\mysql\QueryBuilder
{
    protected function defaultExpressionBuilders()
    {
        return \array_merge(parent::defaultExpressionBuilders(), [
            JsonExpression::class => JsonExpressionBuilder::class
        ]);
    }

    public function getColumnType($type)
    {
        if ($type instanceof ColumnSchemaBuilder
            && $type->isJson()
        ) {
            $type = \yii\db\Schema::TYPE_JSON;
        }
        return parent::getColumnType($type);
    }

    public function alterColumn($table, $column, $type)
    {
        // Do the replacement here since we need the column name.
        if ($type instanceof ColumnSchemaBuilder) {
            $type = $type->toString($column);
        }
        return parent::alterColumn($table, $column, $type);
    }

    public function addColumn($table, $column, $type)
    {
        // Do the replacement here since we need the column name.
        if ($type instanceof ColumnSchemaBuilder) {
            $type = $type->toString($column);
        }
        return parent::addColumn($table, $column, $type);
    }

    public function createTable($table, $columns, $options = null)
    {
        foreach ($columns as $name => &$type) {
            if ($type instanceof ColumnSchemaBuilder && \is_string($name)) {
                $type = $type->toString($name);
            }
        }
        return parent::createTable($table, $columns, $options);
    }
}
