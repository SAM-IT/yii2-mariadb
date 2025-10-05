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
    /**
     * @var string pattern that is used for the check-clause
     *             token `{name}` will be replaced with the name of the column
     */
    public string $checkPattern = "json_valid([[{name}]])";

    public function __construct(string $type, $length = null, ?Connection $db = null, array $config = [])
    {
        parent::__construct($type, $length, $db, $config);
        if ($this->isJson()) {
            $this->check($this->checkPattern);
        }
    }

    public function isJson(): bool
    {
        return $this->type === \yii\db\Schema::TYPE_JSON;
    }

    public function toString(string $columnName)
    {
        switch ($this->getTypeCategory()) {
            case self::CATEGORY_PK:
                $format = '{type}{length}{comment}{append}{check}{pos}';
                break;
            case self::CATEGORY_NUMERIC:
                $format = '{type}{length}{unsigned}{notnull}{default}{unique}{comment}{append}{check}{pos}';
                break;
            default:
                $format = '{type}{length}{notnull}{default}{unique}{comment}{append}{check}{pos}';
        }

        return \strtr($this->buildCompleteString($format), ['{name}' => $columnName]);
    }
}
