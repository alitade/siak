<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */

$this->title = $model->mhs_nim;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-view">
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
            'mhs_nim',
            'mhs_angkatan',
            'jr_id',
            'pr_kode',
            'mhs_stat',
            'ds_wali',
        ],
        'deleteOptions'=>[
			'url'=>['delete', 'id' => $model->mhs_nim],
			'data'=>[
				'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
				'method'=>'post',
	        ],
        ],
        'enableEditMode'=>tru,
    ]) ?>

</div>
