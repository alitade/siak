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
<div class="panel">
    <div class="panel-body">

            <div class="col-sm-3" style="border: solid 1px #000;">

                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
                <label class="input"><i class="ic-append fa fa-user"></i>
                    <?= $form->field($model, 'username',
                        [
                            'addon' => ['append' => ['content'=>'.00']],
                        ]
                        )->textInput(['placeholder'=>'Username/NPM'])->label(''); ?>
                </label>
                <label class="input"><i class="ic-append fa fa-lock"></i>
                    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password'])->label('') ?>
                </label>
                <?php $form = ActiveForm::end(); ?>
            </div>
            <div class="col-sm-9" style="border: solid 1px #000;">
                &nbsp;

            </div>

    </div>


</div>

