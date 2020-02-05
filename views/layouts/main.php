<?php
use yii\helpers\Html;
$F = (new \app\models\Funct);


//dd(Yii::$app->user->identity->ccode);
if (Yii::$app->controller->action->id === 'login') {
    echo $this->render('main-login',['content' => $content]);
} else {
    if(Yii::$app->user->isGuest){Yii::$app->response->redirect(['site/login']);}

    app\assets\AdminLteAsset::register($this);
    //app\assets\AdminLteBowerAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-black fixed sidebar-mini sidebar-mini-expand-feature">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render('left.php',['directoryAsset' => $directoryAsset,'F'=>$F])?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
