<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
// ensure we get report on all possible php errors
\error_reporting(-1);

\define('YII_ENABLE_ERROR_HANDLER', false);
\define('YII_DEBUG', true);
\define('YII_ENV', 'test');
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;



$frameworkTestDir = __DIR__ . '/../vendor/yiisoft/yii2-dev/tests';
require $frameworkTestDir . '/../framework/Yii.php';

if (\getenv('TEST_RUNTIME_PATH')) {
    Yii::setAlias('@yiiunit/runtime', \getenv('TEST_RUNTIME_PATH'));
    Yii::setAlias('@runtime', \getenv('TEST_RUNTIME_PATH'));
    \SamIT\Yii2\MariaDb\Tests\TestCase::initDatabase(require __DIR__ . '/data/config-docker.php');
} else {
    \SamIT\Yii2\MariaDb\Tests\TestCase::initDatabase(require __DIR__ . '/data/config-ci.php');
}
