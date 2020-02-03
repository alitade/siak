<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="ruang-form">
<div class="panel panel-primary">
    <div class="panel-heading">Ruangan</div>
        <div class="panel-body">
            <div class="col-lg-12">
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
            		'rg_kode'=>[
						'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kode Ruangan...']
					
					], 
            		'rg_nama'=>[
						'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Ruangan...']
					], 
            		'kapasitas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kapasitas...']], 

            		'IdGedung'=>[
                        'Label'=>'Gedung',
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' => Funct::GEDUNG(),
                            'options' => [
                                'fullSpan'=>6,
                                'placeholder' => 'Pilih Gedung... ',
                                'multiple' =>false,							
                            ],
                        ],
            		], 
                ]
            ]);
?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);?>

<?php

    
    ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
