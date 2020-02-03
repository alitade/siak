<?php
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class="matkul-kr-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' =>false,
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'width'=>'10px',
            ],
            [
                'attribute'	=>'pr.pr_nama',
                'label'=>'Konsentrasi',
                'width'=>'20%',
            ],
            'ket',
            [
                'header'=>'&sum;SKS',
                'attribute'=>'totSks',
                'value'=>function($model){
                    $sks=0;
                    foreach($model->mkDet as $d){$sks+=$d->sks;}
                    return $sks;
                },
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
                'class' => 'kartik\grid\ActionColumn',
                'template'=>
                    '
								<li>{view}</li>
								<li>{update}</li>
								<li>{sub}</li>
								<li>{aktif}</li>
								<li>{delete}</li>
					'
                ,
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        if(!\app\models\Funct::acc('/matkul-kr/update')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span> Update',['form/cetak-absensi','id' => $model->id, 'jenis'=>2]
                        );
                    },
                    'delete'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/delete')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span> Delete',['matkul-kr/delete','id' => $model->id, 'jenis'=>2]
                        );
                    },
                    'view'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/matkul-kr/view')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span> Detail',['matkul-kr/sub-view','id' => $model->id, 'jenis'=>2]
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
            'heading' => false,
            'type' => 'info',
            'before' => false,
            'after' =>false,
            'showFooter' => false
        ],
    ]);
    ?>

</div>