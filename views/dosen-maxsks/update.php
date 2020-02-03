<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;


/**
 * @var yii\web\View $this
 * @var app\models\DosenMaxsks $model
 */

$this->title = 'Update Dosen Maxsks: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Maxsks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Update Maksimal SKS (<?= $model->tipe->tipe ?>)</span></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 2,
            'attributes' =>[
                'maxsks'=>[
                    'label'=>'Max. SKS',
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Maxsks...']
                ],
                [
                    'label'=>false,
                    'type'=>Form::INPUT_RAW,
                    'value'=>Html::submitButton("<i class='fa fa-save'></i> Update", ['class' => 'btn btn-primary'])
                ],
            ]

        ]);
        ActiveForm::end(); ?>
    </div>

</div>