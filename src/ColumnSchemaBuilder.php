<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;

use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * Class ColumnSchemaBuilder
 * @package SamIT\Yii2\MariaDb
 */
class ColumnSchemaBuilder extends \yii\db\mysql\ColumnSchemaBuilder
{
    public function __construct(string $type, $length = null, ?Connection $db = null, array $config = [])
    {
        parent::__construct($type, $length, $db, $config);
        if ($this->isJson()) {
            $this->check("[[{name}]] is null or json_valid([[{name}]])");
        }
    }

    public function isJson(): bool
    {
        return $this->type === \yii\db\Schema::TYPE_JSON;
    }

    public function toString(string $columnName)
    {
        return \strtr(parent::__toString(), ['{name}' => $columnName]);
    }
}
