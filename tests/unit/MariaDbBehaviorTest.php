<?php


class MariaDbBehaviorTest extends \yiiunit\TestCase
{

    public function testBadOwner()
    {
        $this->expectException(\yii\base\InvalidConfigException::class);
        $behavior = new \SamIT\Yii2\MariaDb\MariaDbBehavior();
        $behavior->attach(new stdClass());
    }

    public function testBadConnection()
    {
        $connection = new \yii\db\Connection([
            'dsn' => 'sqlite::memory:'
        ]);
        $this->expectException(\yii\base\InvalidConfigException::class);
        $behavior = new \SamIT\Yii2\MariaDb\MariaDbBehavior();
        $connection->attachBehavior('mariadb', $behavior);
    }

    public function testValidConfig()
    {
        $config = self::getParam('databases')['mysql'];
        $connection = new \yii\db\Connection([
            'dsn' => $config['dsn'],
            'username' => $config['username'],
            'password' => $config['password']
        ]);
        $behavior = new \SamIT\Yii2\MariaDb\MariaDbBehavior();
        $connection->attachBehavior('mariadb', $behavior);
        $this->assertInstanceOf(\SamIT\Yii2\MariaDb\MariaDbBehavior::class, $connection->getBehavior('mariadb'));
    }
}