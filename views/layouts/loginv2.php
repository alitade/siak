<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use Yii as Yii;
$this->title = 'Login Sistem Informasi Akademik';
$User = Yii::$app->user;

#return Yii::$app->response->redirect(Url::to(['info/maintenance']));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" -->
    <!-- link rel="shortcut icon" type="image/x-icon" href="<?= Url::to('@web/images/ypkp.png'); ?>"  /--->
    <link href="<?= Url::to('@web/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= Url::to('@web/css/fa/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css">
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
</html>
<?php $this->endPage() ?>
