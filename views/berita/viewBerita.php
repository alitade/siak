<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Berita */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Kelola Berita', 'url' => ['baa','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="berita-view">

<?= DetailView::widget([
    'model'=>$model,
    'condensed'=>true,
    'hover'=>true,
    'enableEditMode'=>false,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Berita : ' . $model->judul,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
    'attributes'=>[
        [
            'attribute'=>'id_user',
            'label'=>'Penerbit',
            'value'=>$model->idUser->name,
        ],
        'kategori',
        'judul',
        'isi_berita:ntext',
        'status',
        'tanggal',
    ]
]); ?>

</div>
