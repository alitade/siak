<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukHarga $model
 */

$this->title = $model->harga;
$this->params['breadcrumbs'][] = ['label' => 'Produk Hargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-harga-view">
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
            'kode_produk',
            'harga',
            'aktif',
            'cuid',
            'uuid',
            [
                'attribute'=>'utgl',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME
                ]
            ],
            'duid',
            [
                'attribute'=>'dtgl',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME
                ]
            ],
            'Rstat',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->harga],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
