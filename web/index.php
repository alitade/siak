<?php
ini_set('max_execution_time', 300);
#ini_set('default_charset', 'UTF-8');
ini_set('display_errors', 'Off');
error_reporting(0);

#FILTER IP MANUAL
$ip=[
    '192.168.11.97',
    '192.168.11.246',
	'180.245.106.193',
];
$TOLAK=TRUE;
foreach ($ip as $k=>$v){if($v==$_SERVER['REMOTE_ADDR']){$TOLAK=false;}}
#if($TOLAK){die('GaBisaAkses');}


// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/../config/web.php');

#if(Yii::$app->request->userIP!='192.168.11.9') {echo "IP".Yii::$app->request->userIP;die(Yii::$app->request->userIP);}
(new yii\web\Application($config))->run();
