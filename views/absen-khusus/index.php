<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\AbsenKhususSearch $searchModel
 */

$this->title = 'Absen Khususes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absen-khusus-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Absen Khusus', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'mhs_nim','kr_kode',
            ['attribute'=>'tgl_exp','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            ['attribute'=>'tgl_ins','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
			[
				'attribute'=>'tipe',
				'value'=>function($model){
					return ($model->tipe==3?'Dosen':'Mahasiswa');
				},
				'filter'=>[3=>'Dosen',5=>'Mahasiswa']				
			],	
			[
				'label'=>'Aksi',
				'format'=>'raw',
				'value'=>function($model){
					if($model->tipe==3){return Html::a('Cetak',['rekap-absen/absen-khusus','n'=>$model->mhs_nim,'s'=>1],['target'=>'_blank']);}
					return Html::a('Cetak',['mahasiswa/absen-khusus','n'=>$model->mhs_nim,'s'=>1],['target'=>'_blank']);					 
				}
			],	
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); 
	?>

</div>
