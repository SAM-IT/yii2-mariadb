<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;


use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * This behavior adds support for MariaDB specific functionality to Yii's DB connection class.
 * Attach it only to connections to MariaDB instances!
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
        if ($owner->getDriverName() !== 'mysql') {
            throw new InvalidConfigException('Driver name did not match expected "mysql", are you sure this connection is to a MariaDB connection?');
        }

        $owner->schemaMap['mysql'] = Schema::class;
    }



}