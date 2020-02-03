<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Funct;

$this->title = 'Matakuliah';
$this->params['breadcrumbs'][] = $this->title;

$id = $id=Yii::$app->user->identity->id;
//var_dump(Yii::$app->authManager->checkAccess($id,'Jurusan'));
?>
<div class="matkul-index">
	<p></p>
    <?php 
	Pjax::begin(['enablePushState'=>false]);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'toolbar'=> [
	        ['content'=>
				(Funct::acc('/matkul/create')?Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['/matkul/create'],['class'=>'btn btn-success']):"")." "
				.(Funct::acc('/matkul/pdf')?Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF ',Url::to(["/matkul/pdf"]).'&c=1',['class'=>'btn btn-info','target'=>'_blank']):"")
	        ],
	        '{toggleData}',
	    ],
        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'=>function($model){return $model->jr->jr_jenjang." ".$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
            'mtk_kode',
            'mtk_nama',
            'mtk_sks',
			[
				'attribute'=>'mtk_sub',
				'value'=>function($model){
					return @Funct::MTK()[@$model->mtk_sub];
				},
			],
            'mtk_semester', 
			[
				'attribute'=>'mtk_kat',
				'value'=>function($model){
					$a=" ";
					if($model->mtk_kat=='0'){$a=" Teori ";}
					if($model->mtk_kat=='1'){$a=" Praktek ";}
					if($model->mtk_kat=='2'){$a=" Teori + Praktek";}
					if($model->mtk_kat=='3'){$a=" Tugas Besar";}
					return $a;
					;},
				'filter'=>['Teori','Praktek','Teori + Praktek'," Tugas Besar"]	
			],

            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [

                'view' => function ($url, $model) {
						if(!Funct::acc('/matkul/view')){return false;}
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['matkul/view','id' => $model->mtk_kode]),['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'update' => function ($url, $model) {
						if(!Funct::acc('/matkul/update')){return false;}						
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
						Yii::$app->urlManager->createUrl(
							['matkul/update','id' => $model->mtk_kode]),['title' => Yii::t('yii', 'Edit'),]
						);
					},
                'delete' => function ($url, $model) {
						if(!Funct::acc('/matkul/delete')){return false;}
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['matkul/delete','id' => $model->mtk_kode]),[
								'title' => Yii::t('yii', 'Hapus'),
								'onClick'=>"return confirm('Hapus Data ".$model->mtk_kode."')"
							]
						);
					},

                ],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'floatHeader'=>false,
        'condensed'=>true,
        'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i> Matakuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['mtk'], ['class' => 'btn btn-info']),
    	]
    ]);Pjax::end(); ?>
</div>