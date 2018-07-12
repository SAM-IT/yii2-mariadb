<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;


use yii\db\Connection;

class ColumnSchemaBuilder extends \yii\db\mysql\ColumnSchemaBuilder
{
    public function __construct(string $type, $length = null, ?Connection $db = null, array $config = [])
    {
        parent::__construct($type, $length, $db, $config);
        if ($this->isJson()) {
            $this->check("JSON_VALID([[$type]])");
        }
    }

    public function isJson(): bool
    {
        return $this->type === \yii\db\Schema::TYPE_JSON;
    }


}