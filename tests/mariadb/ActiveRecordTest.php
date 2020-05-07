<?php
declare(strict_types=1);
namespace SamIT\Yii2\MariaDb\Tests;

use yii\db\ActiveQueryInterface;
use yii\db\Expression;
use yii\db\JsonExpression;
use yii\db\Query;
use yiiunit\data\ar\Storage;

/**
 * @group db
 * @group mysql
 * @covers \SamIT\Yii2\MariaDb\JsonExpressionBuilder
 *
 */
class ActiveRecordTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $defaultStorage = new Storage();
        $defaultStorage->loadDefaultValues();

        $this->assertSame($defaultStorage->defaultValue1, null, 'NULL values preserved');
        $this->assertSame($defaultStorage->defaultValue2, [], 'Empty array converted');
        $this->assertSame($defaultStorage->defaultValue3, [], 'Empty object converted');
        $this->assertSame($defaultStorage->defaultValue4, [1,2], 'Array with elements converted');
        $this->assertSame($defaultStorage->defaultValue5, ["a"=>1,"b"=>2], 'Object with properties converted');
    }

    public function testSave(): void
    {
        $data = [
            'obj' => ['a' => ['b' => ['c' => 2.7418]]],
            'array' => [1,2,null,3],
            'null_field' => null,
            'boolean_field' => true,
            'last_update_time' => '2018-02-21',
        ];


        $storage = new Storage(['data'=>$data]);
        $this->assertTrue($storage->save(), 'Storage can not be saved');
        $this->assertNotNull($storage->id);
    }

    /**
     * @depends testSave
     */
    public function testRetrieval(): void
    {
        $data = [
            'obj' => ['a' => ['b' => ['c' => 2.7418]]],
            'array' => [1,2,null,3],
            'null_field' => null,
            'boolean_field' => true,
            'last_update_time' => '2018-02-21',
        ];

        $storage = new Storage(['data'=>$data]);
        $this->assertTrue($storage->save());
        $retrievedStorage = Storage::findOne($storage->id);
        $this->assertInstanceOf(Storage::class, $retrievedStorage);
        $this->assertSame($data, $retrievedStorage->data, 'Properties are restored from JSON to array without changes');

        $retrievedStorage->data = ['updatedData' => $data];
        $this->assertSame(1, $retrievedStorage->update(), 'Storage can be updated');

        $retrievedStorage->refresh();
        $this->assertSame(['updatedData' => $data], $retrievedStorage->data, 'Properties have been changed during update');
    }

    public function testSubQuery(): void
    {
        $query = Storage::find()->andWhere(['id' => (new Query())->select('id')->from(Storage::tableName())]);
        $this->assertInstanceOf(ActiveQueryInterface::class, $query);
        $result = $query->asArray()->all();
        $this->assertNotEmpty($result);
    }
    /**
     * @depends testSave
     */
    public function testUpdate(): void
    {
        $data = [
            'obj' => ['a' => ['b' => ['c' => 2.7418]]],
            'array' => [1,2,null,3],
            'null_field' => null,
            'boolean_field' => true,
            'last_update_time' => '2018-02-21',
        ];

        $storage = new Storage(['data'=>$data]);
        $this->assertTrue($storage->save());

        $storage->data = ['updatedData' => $data];
        $this->assertSame(1, $storage->update(), 'Storage can be updated');

        $storage->refresh();
        $this->assertSame(['updatedData' => $data], $storage->data, 'Properties have been changed during update');
    }

    public function testJsonExpressionWithQueryValue()
    {
        // When writing code for mariadb there is no need to ever wrap a subquery in a json expression.
        // Regardless, due to the parent implementation supporting this, we support it as well.

        $data = [
            'obj' => ['a' => ['b' => ['c' => 2.7418]]],
            'array' => [1,2,null,3],
            'null_field' => null,
            'boolean_field' => true,
            'last_update_time' => '2018-02-21',
            'testname' => base64_encode(random_bytes(40))
        ];

        $storage = new Storage(['data'=>$data]);
        $this->assertTrue($storage->save());


        $retrievedViaSubQuery = Storage::find()->andWhere([
            'data' => new JsonExpression(Storage::find()->select('data')->andWhere(['id' => $storage->id]))
        ])->one();

        $this->assertSame($storage->id, $retrievedViaSubQuery->id);
    }
}
