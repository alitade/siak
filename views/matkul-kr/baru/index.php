<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;

$this->title = 'Matakuliah Kurikulum';
$this->params['breadcrumbs'][] = $this->title;

Modal::begin(['id'=>'modals']);
#echo $this->render('_search', ['model' => $searchModel]);
Modal::end();

?>

<p></p>
<div class="matkul-kr-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kode',
            'ket',
            [
                'header'=>'&sum;SKS',
                'attribute'=>'totSks',
                'width'=>'1%',
            ],
            [
                'header'=>"&sum;MK",
                'format'=>'raw',
                'value'=>function($model){
                    return sizeof($model->mkDet);
                },
                'width'=>'1%',

            ],
            [
                'header'=>"&sum;Mhs",
                'format'=>'raw',
                'value'=>function($model){return sizeof($model->mhsAll);},
                'width'=>'1%',

            ],
            [
                'header'=>"&sum;Sub",
                'format'=>'raw',
                'value'=>function($model){return sizeof($model->subKr);},
                'width'=>'1%',

            ],
            [
                'attribute'	=> 'jr_id',
                'width'=>'20%',
                'value'=> function($model){return  $model->jr->jr_jenjang.' '.$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
				'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
            [
                'attribute'=>'aktif',
                'value'=>function($model){return ($model->aktif?'Y':'N');},
                'filter'=>["T",'Y']
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>
                    '
								<li>{view}</li>
								<li>{mtk}</li>
								<li>{mhs}</li>
								<li>{update}</li>
								<li>{sub}</li>
								<li>{aktif}</li>
					'
								#<li>{delete}</li>
                ,
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        if(!\app\models\Funct::acc('/matkul-kr/update')){return false;}
                        return Html::a(
                            '<span class="fa fa-pencil"></span> Update',['/matkul-kr/update','id' => $model->id,]
                        );
                    },
                    'mhs'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/delete')){return false;}
                        return Html::a(
                            '<span class="fa fa-users"></span> Mahasiswa',['/matkul-kr/mhs','id' => $model->id,]
                        );
                    },
                    'mtk'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/delete')){return false;}
                        return Html::a(
                            '<span class="fa fa-book"></span> Matakuliah',['/matkul-kr/matkul','id' => $model->id,]
                        );
                    },
                    'delete'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/delete')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span> Delete',['/matkul-kr/delete','id' => $model->id,]
                        );
                    },
                    'view'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/view')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span> Detail',['matkul-kr/view','id' => $model->id,]
                        );
                    },
                    'sub'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/sub-create')){return false;}
                        return Html::a(
                                '<span class="glyphicon glyphicon-plus"></span> Sub kurikulum',['matkul-kr/sub-create','id' => $model->id,]
                        );
                    },
                    'aktif'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/aktif')){return false;}

                        if($model->aktif==0){
                            return Html::a('<span class="fa fa-check"></span> Aktif',['matkul-kr/aktif','id' => $model->id, 'jenis'=>2]);
                        }
                        return false;

                    },

                ],
            ],
        ],
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
        'responsive' =>false,
        'hover' => true,
        'condensed' => true,
        'floatHeader' =>false,
        'toolbar'=>[
        [
            'content'=>false,
        ],
        '{toggleData}',
        (!Funct::acc('/gridview/export/*')?"":'{export}'),

    ],
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type' => 'primary',
            #'before' => \app\models\Funct::acc('/matkul-kr/create')? Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']):"".' '.Html::a('<i class="glyphicon glyphicon-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]),
            'before'=>Html::a('<i class="fa fa-plus"></i> Tambah Data', ['create'], ['class' => 'btn btn-success']).' '.Html::a('<i class="glyphicon glyphicon-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]),
			'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false

        ],
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
