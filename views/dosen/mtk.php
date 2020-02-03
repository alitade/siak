<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use kartik\select2\Select2;
use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Bobot Nilai';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-primary">
<div class="panel-heading"></div>
<div class="panel-body">
 <div class="angge-search">
 <?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ],['data-pjax'=>1]); ?>
    <?= 
        $form->field($searchModel, 'kr_kode')->widget(Select2::classname(), [
            'data' =>app\models\Funct::AKADEMIK(),
            'language' => 'en',
            'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Tahun Akademik');
     ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['dosen/bobot'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
</div>
</div>


<?php 

Pjax::begin(['id'=>'pjaxNilai']); ?>
<?= $this->render('grid_bn', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'KR'=>$KR,
			
        ]); 
?>
<?php Pjax::end(); ?>