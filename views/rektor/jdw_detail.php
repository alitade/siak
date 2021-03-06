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
?>
<div class="jadwal-index">

    <?php 
	Pjax::begin(['timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); 
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'mhs_nim',
			[
             'header'  => 'Nama',
             'value' => function($model) {
                    return Funct::getName($model->mhs_nim,'Nama');
                },
             'format'  => 'raw',
            ],                 
            [
             'header'  => 'Absensi',
             'value' => function($model) {
                    $ab = Absensi::find()->where(['krs_id'=>$model->krs_id])->one();
                    return $ab ? "Ya" :  Html::a('<i class="glyphicon glyphicon-plus"></i>', ['akademik/create-absensi', 'id' => $model->jdwl_id,'krs'=>$model->krs_id,'nim'=>$model->mhs_nim]);
                },
             'format'  => 'raw',
             ],                             
			
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'
			<i class="fa fa-navicon"></i>'.$model->bn->ds->ds_nm."<br />".app\models\Funct::MTK()[$model->bn->mtk_kode]."<br />"
			." Kelas $model->jdwl_kls, ".app\models\Funct::HARI()[$model->jdwl_hari]." $model->jdwl_masuk - $model->jdwl_keluar"
			
			,
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]); Pjax::end(); 
	?>

</div>
