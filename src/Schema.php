<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;


use yii\db\Expression;

class Schema extends \yii\db\mysql\Schema
{
    /**
     * @var bool whether to detect JSON fields in MariaDB via the field comments.
     */
    public $detectJsonViaComment = true;

    public function createQueryBuilder()
    {
        $result = new QueryBuilder($this->db);
        $result->setExpressionBuilders([
            'yii\db\JsonExpression' => JsonExpressionBuilder::class
        ]);
        return $result;
    }

    protected function loadColumnSchema($info)
    {
        $columnSchema = parent::loadColumnSchema($info);
        if ($info['type'] === 'longtext'
            //&& strpos($info['comment'], 'yii-json') !== false
        ) {
            $columnSchema->type = \yii\db\Schema::TYPE_JSON;
            $columnSchema->phpType = 'array';
            $columnSchema->dbType = \yii\db\Schema::TYPE_JSON;
        }

        if ($info['default'] === 'current_timestamp()') {
            $columnSchema->defaultValue = new Expression('current_timestamp()');
        }

        return $columnSchema;
    }

    public function createColumnSchemaBuilder($type, $length = null)
    {
        return new ColumnSchemaBuilder($type, $length, $this->db);
    }

}