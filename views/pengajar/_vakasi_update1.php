<?php

use app\models\Funct;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;


$prod		= \app\models\Produk::find();//->where(['isnull(Lock,0)'=>0]);
//$produk		= $prod->all();
$prodUas	= $prod->where(['kategori'=>3,'isnull(Lock,0)'=>0])->all();
$prodUts	= $prod->where(['kategori'=>'1','isnull(Lock,0)'=>0])->all();

$Jurusan=\app\models\Jurusan::find()->where($subAkses?["jr_id"=>$subAkses]:"")->all();
/*
echo"<pre>";
print_r($subAkses);
print_r($Jurusan);
echo"</pre>";
*/
//print_r($Jurusan);
$LP=ArrayHelper::map($produk,'kode','produk');
$LKajur=ArrayHelper::map($Jurusan,'jr_head','jr_head');

?>
		<?php
		//echo $anv;
		
        $form = ActiveForm::begin([
			'type'=>ActiveForm::TYPE_HORIZONTAL,
			'formConfig'=>[
				'labelSpan'=>4,
			]
		]); 
		
		?>
        
        <?php 
		//foreach($sql as $d):
		?>
        <fieldset style="border-bottom:solid 1px #000000;margin-bottom:2px;">
        	<!-- legend style="font-size:12px;"><b>UTS SUSULAN</b></legend -->
            <?php
			if(Funct::acc('/pengajar/vksbaa')){
			echo Form::widget([
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'TANDA[keuangan]'=>['label'=>'Kabag. Keuangan' ,'type'=>Form::INPUT_TEXT,],
						'TANDA[kajur]'=>['label'=>'Kabag. BAAK' ,'type'=>Form::INPUT_TEXT,],
						'TANDA[BAAK]'=>['label'=>false,'type'=>Form::INPUT_HIDDEN,'options'=>['value'=>1]],
					],
				]).(
				$anv==0?
				Form::widget([
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'PPH'=>['label'=>'PPH' ,'type'=>Form::INPUT_TEXT],
					],
				]):"");
				
			}else{
			echo Form::widget([
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'TANDA[keuangan]'=>['label'=>'Kabag. Keuangan' ,'type'=>Form::INPUT_TEXT,],
						'TANDA[kajur]'=>[
							'label'=>'Ketua Jurusan' ,
							'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
							'options'=>[
								'data' =>$LKajur,
								'options' => [
									'fullSpan'=>6,
									'placeholder' => '... ',
								],
								'pluginOptions' => [
									'allowClear' => true
								],
							],									
						],
					],
				]).(
				$anv==0?
				Form::widget([
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'PPH'=>['label'=>'PPH' ,'type'=>Form::INPUT_TEXT],
					],
				]):"")
				;
				
			}
			
			?>
        </fieldset>
        <?php 
		#validasi anvulen
		if($anv==0):
		?>
        
		<?php if(isset($p['UTS'])): ?>
        <fieldset style="border-bottom:solid 1px #000000;margin-bottom:2px;">
        	<legend style="font-size:12px;"><b>UTS</b></legend>
            <?php
			echo Form::widget(
				[
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'NUTS'=>[
                            'type'=>Form::INPUT_TEXT,
                            'options'=>['value'=>@$_SESSION['UTS']['NUTS']['q'],]
                        ],
						'PR[UTS]'=>[
							'label'=>'Honor Pengawas',
							'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
							'options'=>[
								'data' =>[1=>'PAGI','SORE'],
								'options' => [
									'fullSpan'=>6,
									'placeholder' => '... ',
								],
								'pluginOptions' => [
									'allowClear' => true
								],
							],									
						], 
						'ot'=>[
							'label'=>'Tambahan',
							'columns'=>2,
							'labeSpan'=>2,
							'attributes'=>[
								'TB[UTS]'=>[
									'label'=>false,
									'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
									'options'=>[
										'data' =>ArrayHelper::map($prodUts,'kode','produk'),
										'options' => [
											'fullSpan'=>6,
											'placeholder' => '... ',
										],
										'pluginOptions' => [
											'allowClear' => true
										],
									],									
									
								], 
								'QTY[UTS]'=>[
									'label'=>false,
									'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'QTY','readonly'=>($q['t']>0?true:false),]
								], 
							],
						],

					],
				])
			?>
        </fieldset>
		<?php endif; ?>
        <fieldset style="border-bottom:solid 1px #000000;margin-bottom:2px;">
        	<legend style="font-size:12px;"><b>UTS SUSULAN</b></legend>
            <?php
			echo Form::widget(
				[
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'NILAI[SUTS]'=>['label'=>'UTS Susulan' ,'type'=>Form::INPUT_TEXT,],
						'SOAL[SUTS]'=>['label'=>'Naskah Soal' ,'type'=>Form::INPUT_TEXT,],
					],
				])
			?>
        </fieldset>
        
        <?php if(isset($p['UAS'])): ?>
        <fieldset style="border-bottom:solid 1px #000000;margin-bottom:2px;">
        	<legend style="font-size:12px;"><b>UAS</b></legend>
            <?php
			echo Form::widget(
				[
					'form' => $form,
					'model' =>$mVakasi,
					'columns' => 1,
					'attributes' => [
						'NUAS'=>['type'=>Form::INPUT_TEXT,],
						'PR[UAS]'=>[
							'label'=>'Honor Pengawas',
							'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
							'options'=>[
								'data' =>[1=>'PAGI','SORE'],
								'options' => [
									'fullSpan'=>6,
									'placeholder' => '... ',
								],
								'pluginOptions' => [
									'allowClear' => true
								],
							],									
						], 
						'ot'=>[
							'label'=>'Tambahan',
							'columns'=>2,
							'labeSpan'=>2,
							'attributes'=>[
								'TB[UAS]'=>[
									'label'=>false,
									'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
									'options'=>[
										'data' =>ArrayHelper::map($prodUas,'kode','produk'),
										'options' => [
											'fullSpan'=>6,
											'placeholder' => '... ',
										],
										'pluginOptions' => [
											'allowClear' => true
										],
									],									
									
								], 
								'QTY[UAS]'=>[
									'label'=>false,
									'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'QTY','readonly'=>($q['t']>0?true:false),]
								], 
							],
						],
						'QTY[BUAS]'=>[
							'label'=>'Bonus UAS',
							'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'0%',]
						], 
					],
				])
			?>        
        </fieldset>
		<?php endif; ?>
        <fieldset style="border-bottom:solid 1px #000000;margin-bottom:2px;">
        	<legend style="font-size:12px;"><b>UAS SUSULAN</b></legend>
            <?php
			echo Form::widget(
				[
					'model' =>$mVakasi,
					'form' => $form,
					'columns' => 1,
					'attributes' => [
						'NILAI[SUAS]'=>['label'=>'UAS Susulan' ,'type'=>Form::INPUT_TEXT,],
						'SOAL[SUAS]'=>['label'=>'Naskah Soal' ,'type'=>Form::INPUT_TEXT,],
					],
				])
			?>
        </fieldset>
		<?php
		#validasi anvulen akhir
		endif;
		?>
        
        <?= Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan'), ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>

