<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use app\models\Absensi;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination=false;
?>
<div class="jadwal-index">

    <?php 
	Pjax::begin(['timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); 
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        #'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            [
                'header'  => 'NPM',
                'value' =>function($model){return $model->mhs_nim;},
                'width' =>'10%',
            ],

            [
             'header'  => 'Nama',
             'value' => function($model) {
                    return Funct::getName($model->mhs_nim,'Nama');
                },
             'format'  => 'raw',
            ],                 

        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>$model->bn->ds->ds_nm." | ".app\models\Funct::MTK()[$model->bn->mtk_kode]." ($model->jdwl_kls) <br>"
			.app\models\Funct::HARI()[$model->jdwl_hari]." $model->jdwl_masuk - $model->jdwl_keluar"
			
			,
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]); Pjax::end(); 
	?>

</div>
