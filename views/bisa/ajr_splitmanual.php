<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <div class="col-sm-6">
    	<table class="table">
            <tr><th><?= $ModBn->ds->ds_nm." : ".$ModBn->mtk_kode.' '.$ModBn->mtk->mtk_nama." ($ModJd->jdwl_kls) ";?></th></tr>
        	<tr>
            	<td><?= Funct::HARI()[$ModJd->jdwl_hari]?>, <?= $ModJd->jdwl_masuk."-".$ModJd->jdwl_keluar?> | <i class="fa fa-users"></i>  <?= $ModKrs->find()->where(['jdwl_id'=>$ModJd->jdwl_id])->count()?> </td>
            </tr>
        </table>
    </div>

    <div class="col-sm-6">
    	<table class="table">
            <tr><th><?= $ModBn_->ds->ds_nm." : ".$ModBn_->mtk_kode.' '.$ModBn_->mtk->mtk_nama." ($ModJd_->jdwl_kls) ";?></th></tr>
            <tr>
                <td><?= Funct::HARI()[$ModJd_->jdwl_hari]?>, <?= $ModJd_->jdwl_masuk."-".$ModJd_->jdwl_keluar?> | <i class="fa fa-users"></i>  <?= $ModKrs->find()->where(['jdwl_id'=>$ModJd_->jdwl_id])->count()?> </td>
            </tr>
        </table>
    </div>
    <div style="clear: both"></div>

	<?php
	/* 
        $this->render('../akademik/schedule__form', [
        'model' => $model2,
        'mtk' => $ModBn->mtk,
    ]) 
	*/?>	

    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
	Pjax::begin(); 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'toolbar'=> false,
        'columns' => [
            [	
				'class' => 'yii\grid\SerialColumn',
				'headerOptions'=>[
					'class'=>'kartik-sheet-style',
					'width'=>'1%'	
				],
			],
            [
				'class' => 'yii\grid\CheckboxColumn',
				'headerOptions'=>[
					'class'=>'kartik-sheet-style',
					'width'=>'1%'	
				],
				'checkboxOptions' => function($model, $key, $index, $widget)use($ModJd_,$ModJd) {
	                if( $ModJd_->bn->mtk_kode==$ModJd_->bn->mtk_kode && $ModJd_->jdwl_hari=$ModJd->jdwl_hari
                        && $ModJd_->jdwl_masuk=$ModJd->jdwl_masuk  && $ModJd_->jdwl_keluar=$ModJd->jdwl_keluar
                    ){return ['disabled'=>false];}

					return ['disabled'=>Funct::AvKrs($model->mhs_nim,$ModJd_->jdwl_hari,$ModJd_->jdwl_masuk,$ModJd_->jdwl_keluar,$ModJd_->bn->kln->kr_kode)];
				},				
				
			],
			[
				'attribute'=>'mhs_nim',
			],
			[
				'label'=>'ket',
				'value'=>function($model)use($ModJd_,$ModJd){
                    if( $ModJd_->bn->mtk_kode==$ModJd_->bn->mtk_kode && $ModJd_->jdwl_hari=$ModJd->jdwl_hari
                            && $ModJd_->jdwl_masuk=$ModJd->jdwl_masuk  && $ModJd_->jdwl_keluar=$ModJd->jdwl_keluar
                    ){return "";}

					$dis=Funct::AvKrs($model->mhs_nim,$ModJd_->jdwl_hari,$ModJd_->jdwl_masuk,$ModJd_->jdwl_keluar,$ModJd_->bn->kln->kr_kode);

					return ($dis?"Bentrok":"");
				}
			],
			[
				'attribute'=>'mhsNim.mhs.people.Nama',
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i>'.$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama." : ".$ModBn->kln->pr->pr_nama,
			'after'=>Html::submitButton('Pindahkan', ['pindah'], ['class' => 'btn btn-primary']),
    	]
    ]); Pjax::end(); 
	ActiveForm::end();
	?>
</div>
