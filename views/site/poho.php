<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use app\assets\AppAsset;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

$this->title = 'Resset Password';

?>
<link href='https://fonts.googleapis.com/css?family=Sura' rel='stylesheet' type='text/css'>
<div class="colorful-page-wrapper" >
  <div class="center-block">
    <div class="login-block">
       <form id="" class="orb-form" method="post">
        <header>
          <div class="image-block"><img src="<?= Url::to('@web/images/ypkp.png'); ?>" alt="image"></div>
          <h5>Sistem Informasi Akademik</h5><p><h5>Universitas Sangga Buana YPKP</h5></p>
        </header>
        <fieldset>
          <section>
            <?php $form = ActiveForm::begin(['id' => 'Forgot', 'enableClientValidation' => false]); ?>
            <div class="row">
              <div class="col col-12">
                <label class="input"><i class="ic-append fa fa-user"></i>
                  <?= $form->field($model, 'username')->textInput(['placeholder'=>'Masukan Email Anda'])->label(''); ?>
                </label>
              </div>
            </div>
          </section>
        </fieldset>
        <footer>
          <div class="col-lg-8">
            <?= Html::submitButton('<i class="fa fa-sign-in"></i> Reset Password', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>  		
          </div>
        </footer>
      </form>
      <?php ActiveForm::end(); ?>
    </div>
   
    <div class="copyrights">
     &copy; <?php echo date('Y'); ?> Developed by <a href="http://internofa.com" title="Internofa IT Solution" target="_blank"><i style="color:black"><b>Internofa IT Solution</b></i></a> </div>
  </div>
</div>


