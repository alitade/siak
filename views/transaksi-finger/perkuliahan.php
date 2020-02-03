<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiFingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PERKULIAHAN '.\app\models\Funct::HARI()[date('w')].', '.date('Y-m-d');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-finger-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'jdwl_masuk',
            'jdwl_keluar',
            'mtk_kode',
            'mtk_nama',
            'dosen',
            'pengajar',
			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>
					'
								<li>{mAwal}</li>
								<li>{pAwal}</li>
								<li>{gDosen}</li>
					'
				,
				'buttons' => [
					'mAwal'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Masuk Awal'
							,['transaksi-finger/masuk-awal','id' => $model->jdwl_id,'view'=>'t']);
					},
					'pAwal'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Pulang Awal'
							,['transaksi-finger/pulang-awal','id' => $model->jdwl_id,'view'=>'t']);
					},
					'gDosen'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Ganti Pengajar'
							,['transaksi-finger/ganti-dosen','id' => $model->jdwl_id,'view'=>'t']);
					},						
				],
				'dropdown'=>true,
				'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
			],
        ],
    ]); 
	?>
</div>
