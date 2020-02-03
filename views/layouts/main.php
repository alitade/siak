<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
/* @var $this \yii\web\View */
/* @var $content string */


use yii\web\AssetBundle;
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $js = [
        'datatables/dataTables.bootstrap.min.js',
        // more plugin Js here
    ];
    public $css = [
        'datatables/dataTables.bootstrap.css',
        // more plugin CSS here
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}

$User = Yii::$app->user;
#maintenance
#return Yii::$app->response->redirect(Url::to("@web/../under/index.html"));
if(isset($_GET['d1r1th3h3'])){
    if($_GET['d1r1th3h3']==1){
        $_SESSION['huha']=1;
        return Yii::$app->response->redirect(Url::to(['site/index']));
    }
}

if ( $User->isGuest or Yii::$app->controller->action->id === 'login') {
    return Yii::$app->response->redirect(Url::to(['site/login']));
} else {

	if(app\models\Funct::cekPassDef()){
		if(Yii::$app->controller->action->id!=='change-password'){
			return Yii::$app->response->redirect(Url::to(['site/change-password']));
		}
	}	

    app\assets\AdminLteAsset::register($this);
    app\assets\AdminLteBowerAsset::register($this);

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
    <link rel="shortcut icon" type="image/x-icon" href="<?=Yii::getAlias('@web').'/images/ypkp.png'?>">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset,'F'=>new Funct()]
        )
        ?>

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
