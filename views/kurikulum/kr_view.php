<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Kurikulum $model
 */

$this->title = $model->kr_kode;
$this->params['breadcrumbs'][] = ['label' => 'Kurikulums', 'url' => ['/kurikulum/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kurikulum-view">
	<p></p>
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
            'kr_kode',
            'kr_nama',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->kr_kode],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
