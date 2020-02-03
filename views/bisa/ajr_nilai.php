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
use yii\bootstrap\Collapse;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Input Nilai';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php

echo Collapse::widget([
    'items' => [
        [
            'label'     => 'Informasi Bobot Nilai (Klik Disini)',
            'content'   => [
               'Matakuliah '.$header['mtk_kode'].' '.$header['mtk_nama'],
               'TUGAS 1 = ' . $header['nb_tgs1'].' %',
               'TUGAS 2 = ' . $header['nb_tgs2'].' %',
               'TUGAS 3 = ' . $header['nb_tgs3'].' %',
               'QUIS = '    . $header['nb_quis'].' %',
               'TAMBAHAN = '. $header['nb_tambahan'].' %',
               'UTS = '     . $header['nb_uts'].' %',
               'UAS = '     . $header['nb_uts'].' %',
               "KLASIFIKASI GRADE { 
			   <b> A=>( 100.00 - ".number_format(($header['B'] + 0.01),2)." ) ,
			   B => ( ".number_format($header['B'],2)." - ".number_format(($header['C']+ 0.01),2)." ) , 
			   C => ( ".number_format($header['C'],2)." - ".number_format(($header['D']+ 0.01),2)." ) , 
			   D => ( ".number_format($header['D'],2)." - ".number_format(($header['E']+ 0.01),2)." ) , 
			   E => ( ".number_format($header['E'],2)." - 00.00)</b> }"
            ],
            'contentOptions' => [],
            'options' => [],
            'footer' => 'Sebelum input nilai, pastikan kembali Komponen Bobot Nilai telah di SET!'
        ],
    ]
]);
?>
<hr>
 
<?php Pjax::begin(['id'=>'pjaxInputNilai']); ?>
    <?php if (!empty($dataProvider)): ?>
    <?= $this->render('grid_nilai', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'BN'      => $header,
			'ID'	  => $ID
        ]); ?>
    <?php endif ?>
   
<?php Pjax::end(); ?>
  