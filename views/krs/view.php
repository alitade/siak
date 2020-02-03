<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Krs $model
 */

$this->title = $model->krs_id;
$this->params['breadcrumbs'][] = ['label' => 'Krs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="krs-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
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
            'krs_id',
            [
                'attribute'=>'krs_tgl',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME
                ]
            ],
            'jdwl_id',
            'mhs_nim',
            'krs_tgs1',
            'krs_tgs2',
            'krs_tgs3',
            'krs_tambahan',
            'krs_quis',
            'krs_uts',
            'krs_uas',
            'krs_tot',
            'krs_grade',
            'krs_stat',
            'krs_ulang',
            'kr_kode_',
            'ds_nidn_',
            'ds_nm_',
            'mtk_kode_',
            'mtk_nama_',
            'sks_',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->krs_id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
