<?php
declare(strict_types=1);

/**
 * @group db
 * @group mysql
 */
class CommandTest extends \yiiunit\framework\db\mysql\CommandTest
{
    public function testBindParamValue()
    {
        if (\defined('HHVM_VERSION') && $this->driverName === 'pgsql') {
            $this->markTestSkipped('HHVMs PgSQL implementation has some specific behavior that breaks some parts of this test.');
        }

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
        $this->assertEquals($name, $command->queryScalar());

        $sql = <<<'SQL'
INSERT INTO {{type}} ([[int_col]], [[char_col]], [[float_col]], [[blob_col]], [[numeric_col]], [[bool_col]])
  VALUES (:int_col, :char_col, :float_col, :blob_col, :numeric_col, :bool_col)
SQL;
        $command = $db->createCommand($sql);
        $intCol = 123;
        $charCol = str_repeat('abc', 33) . 'x'; // a 100 char string
        $boolCol = false;
        $command->bindParam(':int_col', $intCol, \PDO::PARAM_INT);
        $command->bindParam(':char_col', $charCol);
        $command->bindParam(':bool_col', $boolCol, \PDO::PARAM_BOOL);
        if ($this->driverName === 'oci') {
            // can't bind floats without support from a custom PDO driver
            $floatCol = 2;
            $numericCol = 3;
            // can't use blobs without support from a custom PDO driver
            $blobCol = null;
            $command->bindParam(':float_col', $floatCol, \PDO::PARAM_INT);
            $command->bindParam(':numeric_col', $numericCol, \PDO::PARAM_INT);
            $command->bindParam(':blob_col', $blobCol);
        } else {
            $floatCol = 1.23;
            $numericCol = '1.23';
            $blobCol = "\x10\x11\x12";
            $command->bindParam(':float_col', $floatCol);
            $command->bindParam(':numeric_col', $numericCol);
            $command->bindParam(':blob_col', $blobCol);
        }
        $this->assertEquals(1, $command->execute());

        $command = $db->createCommand('SELECT [[int_col]], [[char_col]], [[float_col]], [[blob_col]], [[numeric_col]], [[bool_col]] FROM {{type}}');

//        $command->prepare();
//        $command->pdoStatement->bindColumn('blob_col', $bc, \PDO::PARAM_LOB);
        $row = $command->queryOne();
        $this->assertEquals($intCol, $row['int_col']);
        $this->assertEquals($charCol, $row['char_col']);
        // Allow the backend to pad floats with zeroes.
        $this->assertRegExp("/{$floatCol}0*/", $row['float_col']);
//        $this->assertEquals($floatCol, $row['float_col']);
        if ($this->driverName === 'mysql' || $this->driverName === 'sqlite' || $this->driverName === 'oci') {
            $this->assertEquals($blobCol, $row['blob_col']);
        } elseif (\defined('HHVM_VERSION') && $this->driverName === 'pgsql') {
            // HHVMs pgsql implementation does not seem to support blob columns correctly.
        } else {
            $this->assertInternalType('resource', $row['blob_col']);
            $this->assertEquals($blobCol, stream_get_contents($row['blob_col']));
        }
        $this->assertEquals($numericCol, $row['numeric_col']);
        if ($this->driverName === 'mysql' || $this->driverName === 'oci' || (\defined('HHVM_VERSION') && \in_array($this->driverName, ['sqlite', 'pgsql']))) {
            $this->assertEquals($boolCol, (int) $row['bool_col']);
        } else {
            $this->assertEquals($boolCol, $row['bool_col']);
        }

        // bindValue
        $sql = 'INSERT INTO {{customer}}([[email]], [[name]], [[address]]) VALUES (:email, \'user5\', \'address5\')';
        $command = $db->createCommand($sql);
        $command->bindValue(':email', 'user5@example.com');
        $command->execute();

        $sql = 'SELECT [[email]] FROM {{customer}} WHERE [[name]] = :name';
        $command = $db->createCommand($sql);
        $command->bindValue(':name', 'user5');
        $this->assertEquals('user5@example.com', $command->queryScalar());
    }
}
