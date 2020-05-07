<?php
declare(strict_types=1);
namespace SamIT\Yii2\MariaDb\Tests;

/**
 * @group db
 * @group mysql
 */
class CommandTest extends TestCase
{
    public function testBindParamValue(): void
    {
        $db = $this->getConnection();

        // bindParam
        $sql = 'INSERT INTO {{customer}}([[email]], [[name]], [[address]]) VALUES (:email, :name, :address)';
        $command = $db->createCommand($sql);
        $email = 'user4@example.com';
        $name = 'user4';
        $address = 'address4';
        $command->bindParam(':email', $email);
        $command->bindParam(':name', $name);
        $command->bindParam(':address', $address);
        $command->execute();

        $sql = 'SELECT [[name]] FROM {{customer}} WHERE [[email]] = :email';
        $command = $db->createCommand($sql);
        $command->bindParam(':email', $email);
        $this->assertSame($name, $command->queryScalar());

        $sql = <<<'SQL'
INSERT INTO {{type}} ([[int_col]], [[char_col]], [[float_col]], [[blob_col]], [[numeric_col]], [[bool_col]])
  VALUES (:int_col, :char_col, :float_col, :blob_col, :numeric_col, :bool_col)
SQL;
        $command = $db->createCommand($sql);
        $intCol = 123;
        $charCol = \str_repeat('abc', 33) . 'x'; // a 100 char string
        $boolCol = false;
        $command->bindParam(':int_col', $intCol, \PDO::PARAM_INT);
        $command->bindParam(':char_col', $charCol);
        $command->bindParam(':bool_col', $boolCol, \PDO::PARAM_BOOL);
        $floatCol = 1.23;
        $numericCol = '1.23';
        $blobCol = "\x10\x11\x12";
        $command->bindParam(':float_col', $floatCol);
        $command->bindParam(':numeric_col', $numericCol);
        $command->bindParam(':blob_col', $blobCol);
        $this->assertSame(1, $command->execute());

        $command = $db->createCommand('SELECT [[int_col]], [[char_col]], [[float_col]], [[blob_col]], [[numeric_col]], [[bool_col]] FROM {{type}}');

//        $command->prepare();
//        $command->pdoStatement->bindColumn('blob_col', $bc, \PDO::PARAM_LOB);
        $row = $command->queryOne();
        $this->assertEquals($intCol, $row['int_col']);
        $this->assertEquals($charCol, $row['char_col']);
        $this->assertEquals($floatCol, (float)$row['float_col']);
//        $this->assertEquals($floatCol, $row['float_col']);
        $this->assertSame($blobCol, $row['blob_col']);
        $this->assertSame($numericCol, $row['numeric_col']);
        $this->assertSame($boolCol, (bool) $row['bool_col']);


        // bindValue
        $sql = 'INSERT INTO {{customer}}([[email]], [[name]], [[address]]) VALUES (:email, \'user5\', \'address5\')';
        $command = $db->createCommand($sql);
        $command->bindValue(':email', 'user5@example.com');
        $command->execute();

        $sql = 'SELECT [[email]] FROM {{customer}} WHERE [[name]] = :name';
        $command = $db->createCommand($sql);
        $command->bindValue(':name', 'user5');
        $this->assertSame('user5@example.com', $command->queryScalar());
    }
}
