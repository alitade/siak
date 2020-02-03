<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Funct;
use kartik\builder\Form;

use yii\bootstrap\Modal;

$this->title = 'Jadwal Perkuliahan Perwalian';
$this->params['breadcrumbs'][] = $this->title;
Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'id'=>'modals',
    'header'=>'Filter Jadwal Perkuliahan',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']

]);
?>
<?php
$form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' =>2, 'deviceSize' => ActiveForm::SIZE_SMALL],
    'action' => ['/krs/jadwal-aktif'],
    'method' => 'get',
]);
echo Form::widget([
    'model' => $searchModel,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        [
            'label'=>'Jurusan',
            'columns'=>2,
            'attributes'=>[
                'jr_id'=>[
                    'label'=>False,
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' =>app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                        'options' => ['fullSpan'=>6,'placeholder' => ' Jurusan ',],
                        'pluginOptions' => ['allowClear' => true,],
                    ]
                ],
                'pr_kode'=>[
                    'label'=>false,
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' =>app\models\Funct::PROGRAM(),
                        'options' => ['fullSpan'=>6,'placeholder' => 'Program',],
                        'pluginOptions' => ['allowClear' => true,],
                    ]
                ],

            ]
        ],
        'ds_nm'=>[
            'label'=>'Dosen',
            'options' => ['placeholder' => 'Dosen',],
        ],
        'mtk_nama'=>[
            'label'=>'Matakuliah',
            'type'=>Form::INPUT_TEXT,
            'options'=>['placeholder'=>'Matakuliah']
        ],

        'jadwal'=>[
            'label'=>'Jadwal',
            'columns' =>3,
            'attributes'=>[
                'jdwl_hari'=>[
                    'label'=>false,
                    'type'=>Form::INPUT_DROPDOWN_LIST,
                    'items'=>@app\models\Funct::HARI(),
                    'options' => [
                        'fullSpan'=>6,'prompt'=>'- Hari -'
                    ],
                ],
                'jdwl_masuk'=>[
                    'label'=>false,
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\TimePicker',
                    'value'=>false,
                    'options'=>[
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'defaultTime'=>false,
                        ],
                        'options'=>['placeholder'=>'masuk']
                    ]
                ],
                'jdwl_keluar'=>[
                    'label'=>false,
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\TimePicker',
                    'value'=>false,
                    'options'=>[
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'defaultTime'=>false,
                        ],
                        'options'=>['placeholder'=>'keluar']
                    ]
                ],
            ]

        ],
        [
            'label'=>'',
            'type'=>Form::INPUT_RAW,
            'value'=>Html::submitButton('Search', ['class' => 'btn btn-primary']).' '.HTML::a('Reset',['berjalan'],['class'=>'btn btn-default'])
        ]
    ]
])

?>
<?php ActiveForm::end(); ?>

<?php


Modal::end();
$btn = Html::a('<i class="fa fa-search"></i> Pencarian Data Jadwal', ['#'], ['class' => 'btn btn-success','id'=>'popupModal' ]);
$nCari=0;
$ketCari='';
if(@$searchModel->pr_kode){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Program Perkuliahan: '.app\models\Funct::PROGRAM()[$searchModel->pr_kode].'</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->jr_id){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Program Studi: '.app\models\Funct::JURUSAN()[$searchModel->jr_id].'</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->ds_nm){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Dosen: % '.$searchModel->ds_nm.' %</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mtk_nama){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Matakuliah: % '.$searchModel->mtk_nama.' %</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->jdwl_hari){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Hari : '.Funct::getHari()[$searchModel->jdwl_hari].' </i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->jdwl_masuk){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Masuk: % '.$searchModel->jdwl_masuk.' %</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->jdwl_keluar){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Keluar: % '.$searchModel->jdwl_keluar.' %</i> </span>&nbsp;'; $nCari+=1;}
if($nCari>0){
    $btn .=" ".Html::a('<i class="fa fa-refresh"></i> Reset Pencarian',['jadwal-aktif',],['class'=>'btn btn-info']);
    echo $ketCari='<p></p><div>'.$ketCari."&nbsp;"."</div><p></p>";
}
?>
<div class="jadwal-index">
    <?php

    #Funct::v($_SERVER);
	echo $d= GridView::widget([
        'dataProvider' => $dataProvider,
        #'filterModel' => $searchModel,
        'summary'	=> 'Menampilkan {begin, number}-{end, number} dari {totalCount, number}',
        'toolbar'=> [
            Html::a('<i class="fa fa-download"></i> PDF',Url::to(['jadwal-aktif','c'=>1]).'&'.$_SERVER['QUERY_STRING'],['class' => 'btn btn-primary','target'=>'_blank']),
            Html::a('<i class="fa fa-download"></i> Excel',Url::to(['excel-perwalian','t'=>1]).'&'.$_SERVER['QUERY_STRING'],['class' => 'btn btn-primary','target'=>'_blank']),
            '{toggleData}',
            #(!Funct::acc('/gridview/export/*')?"":'{export}'),
        ],
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'width'=>'1%',
            ],
			[
                'attribute'	=> 'jr_id',
                'label'	=> 'Jurusan',
				'width'=>'20%',
				'value'		=> function($model){return $model->jr_jenjang." ".$model->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
				'group'=>true,  // enable grouping,
				'groupedRow'=>true,                    // move grouped column to a single grouped row
				'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
				'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
			],			
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> function($model){return @$model->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
				#'pageSummary'=>'Total',
			],
            [
                'label'=>'Jadwal',
                'value'=>function($model){return @app\models\Funct::HARI()[@$model->jdwl_hari].', '.$model->jadwal;},
            ],

			[
                'attribute'=>'jdwl_kls',
                'label'=>'Kls.',
				'width'=>'5%',
				'contentOptions'=>['class'=>'row col-xs-1',],
			],
            [
                'label'=>'Kode',
                'format'=>'raw',
                'value'=>function($model){return '<span class="text-nowrap">'.$model->mtk_kode.'</span>';}
            ],

			[
				'label'=>'Matakuliah',
				'value'=>function($model){return Html::decode($model->mtk_nama);}
			],
			[
				'label'=>'SKS',
				'value'=>function($model){
					return Html::decode($model->bn->mtk->mtk_sks);
				},
				#'pageSummary'=>true,

			],
			[
				'attribute'=>'ds_nm',
				'filter'=>true,
				
			],

            [
                'format'=>'raw',
                'header'=>'<span class="label label-primary"><i class="fa fa-users"></i> </span>',
                'width'=>'1%',
                'value'=>function($model){
                    $v="<span class='text-nowrap'><span class='btn btn-primary btn-xs'>".($model->peserta?:0)."</span></span>";
                    return $v;

                    return Html::a($model->jum,
                        Yii::$app->urlManager->createUrl(
                            ['akademik/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
                    );
                },


            ],
            [
                'format'=>'raw',
                'header'=>'<span class="label label-success"><i class="fa fa-check"></i> </span>',
                'width'=>'1%',
                'value'=>function($model){
                    $v="<span class='text-nowrap'><span class='btn btn-success btn-xs'>".($model->app?:0)."</span></span>";
                    return $v;

                    return Html::a($model->jum,
                        Yii::$app->urlManager->createUrl(
                            ['akademik/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
                    );
                },


            ],
            [
                'format'=>'raw',
                'header'=>'<span class="label label-danger"><i class="fa fa-warning"></i> </span>',
                'width'=>'1%',
                'value'=>function($model){
                    $v="<span class='text-nowrap'><span class='btn btn-danger btn-xs'>".($model->jum?:0)."</span> </span>";
                    return $v;

                    return Html::a($model->jum,
                        Yii::$app->urlManager->createUrl(
                            ['akademik/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
                    );
                },


            ],

			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>'<div class="text-nowrap">{split} {update} {delete}</div>',
				'buttons' => [
                    /*'view'=> function ($url, $model, $key) {
                        #if(!Funct::acc('/jadwal/delete')){return false;}
                        #if($model->jum>0){return false;}
                        return Html::a('<i class="fa fa-eye"></i> ',['/jadwal/view','id' => $model->jdwl_id],[
                            'data-confirm'=>'Hapus Data Ini?',
                            'class'=>'btn btn-success btn-xs','title'=>'Detail'
                        ]);
                    },*/
                    'update'=> function ($url, $model, $key) {
                        if(($model->peserta?:0)>0){return false;}

                        return Html::a('<i class="fa fa-pencil"></i> ',['/jadwal/update','id' => $model->jdwl_id],[
                            'class'=>'btn btn-success btn-xs','title'=>'Update','target'=>"_blank"

                        ]);
                    },
                    'delete'=> function ($url, $model, $key) {
                        #if(($model->peserta?:0)>0){return false;}
                        return Html::a('<i class="fa fa-trash"></i> ',['/jadwal/delete','id' => $model->jdwl_id],[
                            'class'=>'btn btn-danger btn-xs',
                            'data-confirn'=>'Hapus Data Ini?','title'=>'Hapus Data'
                        ]);
                    },
                    'split'=> function ($url, $model, $key) {
                        if(!Funct::acc('/krs/split-perwalian')){return false;}
                        return Html::a('<i class="fa fa-exchange"></i> ',['/krs/split-perwalian','id' => $model->jdwl_id],[
                            'class'=>'btn btn-success btn-xs','title'=>'Split Peserta','target'=>'_blank'
                        ]);
                    },

				],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
		'showPageSummary'=>true, 
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
            'before'=>$btn,
    	]
    ]);
	?>

</div>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
?>
