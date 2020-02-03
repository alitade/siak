<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\PendaftaranSearch $searchModel
 */

$this->title = 'Pendaftaran Mahasiswa Baru';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pendaftaran-index">
    <?php
        Pjax::begin();
        echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kd_daftar',
            'No_Registrasi',
            'bio.no_ktp',
            'bio.nama',
            [
                'class' => 'kartik\grid\ActionColumn',

                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'template'=>'
                        <li>{view}</li>
                        <li>{npm}</li>
                        <li>{update}</li>
                ',
                'buttons' => [
                    'update' => function ($url, $model) {
                        #if(!\app\models\Funct::acc('/matkul-kr/update')){return false;}
                        return Html::a(
                            '<span class="fa fa-pencil"></span> Update',['/matkul-kr/update','id' => $model->id,]
                        );
                    },
                    'view' => function ($url, $model) {
                        #if(!\app\models\Funct::acc('/matkul-kr/update')){return false;}
                        return Html::a('<span class="fa fa-eye"></span> Update',['view-calon','id' => $model->kd_daftar,]);
                    },
                    'npm' => function ($url, $model) {
                        #if(!\app\models\Funct::acc('/matkul-kr/update')){return false;}
                        if($model->No_Registrasi==''){return false;}
                        return Html::a('<span class="fa fa-plus"></span> NPM',['npm','id' => $model->No_Registrasi,]);
                    },

//                    'update' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['pendaftaran/view','id' => $model->No_Registrasi,'edit'=>'t']), [
//                            'title' => Yii::t('yii', 'Edit'),
//                        ]);
//                    }

                ],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['daftar','k'=>'-'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
