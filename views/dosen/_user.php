<?php

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use yii\web\View;
use app\models\Funct;


?>
<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">User</span>
    <div class="pull-right">
    </div>
    <div style="clear: both"></div>
</div>
<p></p>

<?=
Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'user'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Username ...']],
        [
            'label'=>'Password',
            'columns'=>2,
            'attributes'=>[
                'pass'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Password ...']],
                'passDef'=>['label'=>'Default','type'=> Form::INPUT_CHECKBOX,'options'=>['value'=>1,'id'=>'csm'] ]
            ]

        ],
    ]
]);
?>
