<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\TKrsHeadSearch $searchModel
 */

$this->title = 'Perwalian Aktif';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tkrs-head-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Tkrs Head', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        #'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'header'=>'Jurusan',
                'value'=>function($model){return $model->mhs->jr->jr_jenjang.' '.$model->mhs->jr->jr_nama;},
				'group'=>true,  // enable grouping,
				'groupedRow'=>true,                    // move grouped column to a single grouped row
				'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
				'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'header'=>'Program',
                'value'=>function($model){
                    return $model->mhs->pr->pr_nama;
                },
                'group'=>true,  // enable grouping
                'subGroupOf'=>1, // supplier column index is the parent group,

            ],
            [
                'header'=>'NIM | Nama',
                'value'=>function($model){
                    return $model->nim.' | '.$model->mhs->dft->bio->Nama;
                },
            ],
            [
                'header'=>'Dosen Wali',
                'value'=>function($model){return $model->ds->ds_nm;},
            ],

            [
                'header'=>'Status',
                'value'=>function($model){
                    if($model->status==1){return 'Disetujui';}
                    return 'Disetujui';
                },
            ],
            'kr_kode',[
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['tkrs-head/view','id' => $model->kode,'edit'=>'t']), [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
