<?php

use yii\helpers\Html;

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\DepDrop;
use yii\helpers\Url;



/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Tambah Dosen Wali';
$this->params['breadcrumbs'][] = ['label' => 'Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-create">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="dosen-form">
    
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
        'model' => $ModMhs,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'jr_id'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => app\models\Funct::JURUSAN(),
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => '... ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ], 
            'ds_wali'=>[
                'label'=>'Dosen',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => app\models\Funct::MHS(),
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => '... ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ], 
            'mhs_nim'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                'options'=>[
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => '... ',
                    ],
                    'select2Options'=>	[
                        'pluginOptions'=>['allowClear'=>true]
                    ],
                    'pluginOptions' => [
                            'depends'		=>	['mahasiswa-jr_id'],
                            'url' 			=> 	Url::to(['/akademik/dropmhs']),
                            'loadingText' 	=> 	'Loading...',
                    ],
                ],
            ], 
        ]
    
    
        ]);
        echo Html::submitButton($ModMhs->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
         ['class' => $ModMhs->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        ActiveForm::end(); ?>
    
    </div>
    <?= $this->render('dsn__form', [
			'ModDsn' => $ModDsn,
			'ModMhs' => $ModMhs,
    ]) ?>

</div>
