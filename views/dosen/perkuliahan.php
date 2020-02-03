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

$this->title = 'Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id'=>'pjaxNilai']); ?>
    <?php if (!empty($dataProvider)): ?>
    <?= GridView::widget([     
    'dataProvider'=> $dataProvider,
    'rowOptions' => function ($data, $index, $widget, $grid){
        if($data['Lock']>0){
            return ['style' => 'color:#000;font-weight:bold'];
        }else{
            return [];
        }
    },      
   
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'header'=>'Program',
            'value'=>function($model){
                return $model['Program'];
            },
        ],          
        [     
            'header' => 'Matakuliah',
			'format' => 'raw',
            'value' => function($model){
				return "$model[Kode] : $model[Matakuliah] ($model[Kelas])";
            },
        ], 
        'SKS',
        'Jam',
        [
            'header' => '&sum;Mhs.',
            'attribute' => 'Total'
        ],
        [
            'header' => '&sum;Hadir',
            'attribute' => 'Hadir'
        ],
        [     
            'width' => '5%',
            'label' => 'Absensi',
            'attribute' => 'Kode','format' => 'raw',
            'value' => function($model){
                    return Html::a('<i class="glyphicon glyphicon-list"></i>',
                     ['absensi-kuliah-v2', 'id' => $model['jdwl_id'],'matakuliah'=>$model['Kode'],'sort'=>'id'],
                     ['data-pjax'=> '0','class' => 'btn btn-info','target'=>'_blank']);
            },
        ], 
    ],
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i>Perkuliahan Hari Ini',
    ]
]) ?>     
    <?php endif ?>
   
<?php Pjax::end(); ?>
