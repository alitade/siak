<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\DosenPengganti $model
 */

$this->title = $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Penggantis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



\app\models\Funct::TanggalPengganti(1);


?>
<div class="dosen-pengganti-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <h1><?= Html::encode($model->jdw->jdwl_hari) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'Id',
            'ds_id',
            [
                'attribute'=>'Tgl',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE
                ]
            ],
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->Id],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
