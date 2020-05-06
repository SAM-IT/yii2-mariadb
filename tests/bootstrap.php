<?php
declare(strict_types=1);
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

// ensure we get report on all possible php errors
\error_reporting(-1);

\define('YII_ENABLE_ERROR_HANDLER', false);
\define('YII_DEBUG', true);
\define('YII_ENV', 'test');
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

// require composer autoloader if available
$composerAutoload = __DIR__ . '/../vendor/autoload.php';

$frameworkTestDir = __DIR__ . '/../vendor/yiisoft/yii2-dev/tests';

class Yii extends \yii\BaseYii
{

};
Yii::$classMap = require $frameworkTestDir . '/../framework/classes.php';
Yii::$container = new yii\di\Container();
Yii::setAlias('@yiiunit', $frameworkTestDir);
spl_autoload_register(['Yii', 'autoload'], true, false);

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require $composerAutoload;
foreach ($loader->getClassMap() as $class => $file) {
    if (preg_match('~vendor/composer/\.\./\.\./tests/overrides~', $file)) {
        class_exists($class);
    }
}



if (\getenv('TEST_RUNTIME_PATH')) {
    Yii::setAlias('@yiiunit/runtime', \getenv('TEST_RUNTIME_PATH'));
    Yii::setAlias('@runtime', \getenv('TEST_RUNTIME_PATH'));
}

if (\getenv('TEST_RUNTIME_PATH') === "true") {
    \yiiunit\TestCase::$params = require __DIR__ . '/data/config-docker.php';
} else {
    \yiiunit\TestCase::$params = require __DIR__ . '/data/config-ci.php';
}
