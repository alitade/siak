<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\User;
use yii\bootstrap\ActiveForm;
use Yii as Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
$this->title = 'Login Sistem Informasi Akademik';
$User = Yii::$app->user;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
 
    
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="<?= Url::to('@web/images/ypkp.png'); ?>"  />
    <link href="<?= Url::to('@web/css/login2/styles2.css'); ?>" rel="stylesheet" type="text/css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        .btn {
            border-radius: 0;
            border: 0;
            color: white;
            cursor: pointer;
            
        }
        .btn-block {
            display: block;
            width: 90%;
        }
    </style>
</head>
<body>
    <?php $this->beginBody() ?>
        <?=$content?>
      <?php $this->endBody() ?>
</body>

<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery-ui.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/bootstrap.min.js'); ?>"></script> 

<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery.form.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery.validate.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery.maskedinput.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery.steps.min.js'); ?>"></script> 

<script type="text/javascript" src="<?= Url::to('@web/css/login2/jquery.nanoscroller.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/query.sparkline.min.js'); ?>"></script> 

<script type="text/javascript" src="<?= Url::to('@web/css/login2/scripts.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/todo.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/css/login2/modernizr.custom.js'); ?>"></script> 
</html>
<?php $this->endPage() ?>
