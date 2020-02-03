<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;

$this->title = 'Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;

/*Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Filter Data Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
echo $this->render('/mhs/mhs__search', ['model' => $searchModel]);
Modal::end();*/
?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
if($searchModel->jr_id){echo "<span class='badge bg-info'> $searchModel->jr_id </span> ";}
?>
<p> </p>
<div class="mahasiswa-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
#        'filterModel' => $searchModel,
        'toolbar'=> [
            [
                'content'=>
                #Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
                    Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF ',Url::to().'&c=1',['class'=>'btn btn-info','target'=>'_blank'])
            ],
            '{toggleData}',
            (!Funct::acc('gridview')?false:"{export}"),
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
                'attribute'	=> 'pr_kode',
                'width'=>'10%',
                'value'		=> @function($model){return @$model->pr->pr_nama;},
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
			],
            [
                'attribute' => 'Nama',
                'width'=>'20%',
            ],
            [
                'attribute' => 'mhs_nim',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->mhs_nim;
                    return Html::a($model->mhs_nim,['pindah','id'=>$model->mhs_nim],['target'=>"_blank"]);
                }
            ],

            'mhs_angkatan',
			[
                'label'=>'Kurikulum',
                'attribute'=>'thn',
                'width'=>'20%',
            ],
			[
                'attribute'	=> 'ws',
                'width'=>'5%',
                'format'=>'raw',
                'value'		=> function($model){
                    return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
                },
                'filter'=>['N','Y'],

            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        'before'=>Html::a('<i class="glyphicon glyphicon-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]),
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> '.$this->title,
    ]
    ]); ?>
</div>
