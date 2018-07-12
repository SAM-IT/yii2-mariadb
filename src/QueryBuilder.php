<?php


namespace SamIT\Yii2\MariaDb;


use yii\db\JsonExpression;

class QueryBuilder extends \yii\db\mysql\QueryBuilder
{
    protected function defaultExpressionBuilders()
    {
        return array_merge(parent::defaultExpressionBuilders(), [
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
}