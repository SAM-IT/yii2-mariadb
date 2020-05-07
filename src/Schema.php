<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;

use yii\db\Exception;
use yii\db\Expression;
use yii\db\TableSchema;

class Schema extends \yii\db\mysql\Schema
{
    public function createQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this->db);
    }

    private $jsonColumns = [];

    /**
     * @throws Exception if the table does not exist
     * @return string[] The column names that are JSON
     */
    public function getJsonColumns(TableSchema $table): array
    {
        $sql = $this->getCreateTableSql($table);
        $result = [];

        $regexp = '/json_valid\([\`"](.+)[\`"]\s*\)/mi';
        if (\preg_match_all($regexp, $sql, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $result[] = $match[1];
            }
        }

        return $result;
    }

    /**
     * @param TableSchema $table
     * @throws \Exception
     * @return bool
     */
    protected function findColumns($table)
    {
        try {
            // Preload JSON columns by checking SQL.
            $this->jsonColumns = $this->getJsonColumns($table);
        } catch (Exception $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof \PDOException && \strpos($previous->getMessage(), 'SQLSTATE[42S02') !== false) {
                // table does not exist
                // https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html#error_er_bad_table_error
                return false;
            }
        }
        return parent::findColumns($table);
    }


    protected function loadColumnSchema($info)
    {
        if (\in_array($info['field'], $this->jsonColumns, true)) {
            $info['type'] = \yii\db\Schema::TYPE_JSON;
            if (\is_string($info['default']) && \preg_match("/^'(.*)'$/", $info['default'], $matches)) {
                $info['default'] = $matches[1];
            }
        }
        $columnSchema = parent::loadColumnSchema($info);

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
