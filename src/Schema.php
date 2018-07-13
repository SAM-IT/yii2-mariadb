<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;


use yii\db\Expression;

class Schema extends \yii\db\mysql\Schema
{
    public function createQueryBuilder()
    {
        $result = new QueryBuilder($this->db);
        $result->setExpressionBuilders([
            'yii\db\JsonExpression' => JsonExpressionBuilder::class
        ]);
        return $result;
    }

    private $jsonColumns = [];

    public function getJsonColumns($table)
    {
        $sql = $this->getCreateTableSql($table);

        $result = [];

        $regexp = '/CHECK\s*\(\s*json_valid\(\`(.+)\`\s*\)\s*\)/mi';
        if (\preg_match_all($regexp, $sql, $matches, PREG_SET_ORDER)) {
            foreach($matches as $match) {
                $result[] = $match[1];
            }
        }

        return $result;
    }

    protected function findColumns($table)
    {
        // Preload JSON columns by checking SQL.
        $this->jsonColumns = $this->getJsonColumns($table);
        return parent::findColumns($table);
    }


    protected function loadColumnSchema($info)
    {
        $columnSchema = parent::loadColumnSchema($info);
        if ($info['type'] === 'longtext'
            && \in_array($info['field'], $this->jsonColumns, true)
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