<?php
declare(strict_types=1);

use yiiunit\data\ar\Storage;

/**
 * @group db
 * @group mysql
 */
class ActiveRecordTest extends \yiiunit\framework\db\mysql\ActiveRecordTest
{
    public function testJsonColumn(): void
    {
        $data = [
            'obj' => ['a' => ['b' => ['c' => 2.7418]]],
            'array' => [1,2,null,3],
            'null_field' => null,
            'boolean_field' => true,
            'last_update_time' => '2018-02-21',
        ];

        $defaultStorage = new Storage();
        $defaultStorage->loadDefaultValues();

        $this->assertSame($defaultStorage->defaultValue1, null, 'NULL values preserved');
        $this->assertSame($defaultStorage->defaultValue2, [], 'Empty array converted');
        $this->assertSame($defaultStorage->defaultValue3, [], 'Empty object converted');
        $this->assertSame($defaultStorage->defaultValue4, [1,2], 'Array with elements converted');
        $this->assertSame($defaultStorage->defaultValue5, ["a"=>1,"b"=>2], 'Object with properties converted');

        $storage = new Storage(['data'=>$data]);
        $this->assertTrue($storage->save(), 'Storage can be saved');
        $this->assertNotNull($storage->id);

        $retrievedStorage = Storage::findOne($storage->id);
        $this->assertSame($data, $retrievedStorage->data, 'Properties are restored from JSON to array without changes');

        $retrievedStorage->data = ['updatedData' => $data];
        $this->assertSame(1, $retrievedStorage->update(), 'Storage can be updated');

        $retrievedStorage->refresh();
        $this->assertSame(['updatedData' => $data], $retrievedStorage->data, 'Properties have been changed during update');
    }
}
