<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use app\assets\AppAsset;
use app\models\User;
use Yii as Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
?>
<link href='https://fonts.googleapis.com/css?family=Sura' rel='stylesheet' type='text/css'>
<div class="colorful-page-wrapper" >

  <div class="center-block">
    <div class="login-block">
       <form id="" class="orb-form" method="post">
        <header>
          <h5>LOGIN SIAKAD</h5><p><h5>Sistem Informasi Akademik</h5></p>
          <br>
        </header>
        <fieldset>
	   <section>
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
            <div class="row">
              <div class="col col-12">
                <label class="input"><i class="ic-append fa fa-user"></i>
                  <?= $form->field($model, 'username')->textInput(['placeholder'=>'Username/NPM'])->label(''); ?>
                </label>
              </div>
            </div>
          </section>
          <section>
            <div class="row">
              <div class="col col-12">
                <label class="input"><i class="ic-append fa fa-lock"></i>
                  <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password'])->label('') ?>
                </label>
                <a class="popover-hovered note" href="#" data-toggle="tooltip" data-content="Silahkan Hubungi Dir. Sistem Informasi & Multimedia atau email ke <i>sim@usbypkp.ac.id</i> untuk Reset Password" title="Lupa Password ?">Lupa Password ?</a>
              </div>
            </div>
          </section>
        </fieldset>
        <footer>
          <div class="col-lg-8">
            <?= Html::submitButton('<i class="fa fa-sign-in"></i> Login', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>  
          </div>
        </footer>
      </form>
      <?php ActiveForm::end(); ?>
    </div>

      <!--<div class="copyrights">&copy; <?php /*echo date('Y'); */?> Developed by <a href="http://internofa.com" title="Internofa IT Solution" target="_blank"><i style="color:black"><b>Internofa IT Solution</b></i></a> </div>-->
  </div>
</div>


