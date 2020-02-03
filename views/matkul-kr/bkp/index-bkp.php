<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Funct;
$this->title = 'Matakuliah Kurikulum';
$this->params['breadcrumbs'][] = $this->title;
?>
<p></p>
<div class="matkul-kr-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'value'=> @function($model){return  app\models\Funct::JURUSAN()[@$model->jr_id];},
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
                            '<span class="glyphicon glyphicon-pencil"></span> Update',['/matkul-kr/update','id' => $model->id,]
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
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span> Aktif',['matkul-kr/aktif','id' => $model->id, 'jenis'=>2]
                        );
                    },

                ],
            ],
        ],
        'responsive' =>false,
        'hover' => true,
        'condensed' => true,
        'floatHeader' =>false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type' => 'info',
            'before' => \app\models\Funct::acc('/matkul-kr/create')? Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']):"",
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false

        ],
    ]);
    ?>

</div>