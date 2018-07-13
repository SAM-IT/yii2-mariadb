<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;


use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yiiunit\framework\db\mysql\ConnectionTest;

/**
 * This behavior adds support for MariaDB specific functionality to Yii's DB connection class.
 * It will auto detect the database to which the connection exists.
 * @property Connection $owner
 */
class MariaDbBehavior extends Behavior
{
    public function attach($owner): void
    {
        parent::attach($owner);
        if (!$owner instanceof Connection) {
            throw new InvalidConfigException('This behavior can only be attached to database connections');
        }
    }

    public function events()
    {
        return [
            Connection::EVENT_AFTER_OPEN => 'connectionOpenHandler'
        ];
    }


    /**
     * Updates the owners' schemaMap if needed.
     */
    public function connectionOpenHandler(): void
    {
        if (isset($this->owner->pdo)
            && \strpos($this->owner->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION), 'MariaDB') !== false
        ) {
            $this->owner->schemaMap['mysql'] = Schema::class;
        }
    }
}