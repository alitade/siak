<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;


use app\models\Funct;

$this->title = 'Form Vakasi';
$this->params['breadcrumbs'][] = $this->title;

$MK="";$_MK=[];
$JR="";$_JR=[];
foreach($All as $d){
	if(!isset($_MK[$d->bn->mtk_kode])){
		$_MK[$d->bn->mtk_kode]=1;
		$MK.=$d->bn->mtk_kode.",";
	}
	if(!isset($_JR[$d->bn->kln->jr->jr_id])){
		$_JR[$d->bn->kln->jr->jr_id]=1;
		$JR.=$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.", ";
	}
}
echo"<pre>";
//print_r($_SESSION);
echo"</pre>";
?>
<div class="panel panel-primary">
	<div class="panel-heading">
    	<span class="panel-title"><?= $this->title ?></span>
    </div>
    <div class="panel-body">
    	<div class="col-sm-6">
    	<table class="table table-bordered">
        	<tr>
            	<th> Bapak/Ibu Dosen</th>
                <th></th>
                <td><?= $model->bn->ds->ds_nm ?></td>
            </tr>
        	<tr>
            	<th> Kode/Matakuliah</th>
                <th></th>
                <td><?= $MK ?></td>
            </tr>
        	<tr>
            	<th> Program Studi</th>
                <th></th>
                <td><?= $JR ?></td>
            </tr>        
        </table>
        </div>
		<div class="col-sm-6">
    	<?php
        if($sql){
		?>
        <table class="table table-bordered">
        	<thead>
            <tr>
					<th> &sum;Mhs </th>
					<th> &sum;Tgs1 </th>
					<th> &sum;Tgs2</th>
					<th> &sum;Tgs3</th>
					<th> &sum;Quiz </th>
					<th> &sum;UTS</th>
					<th> &sum;UAS</th>
            </tr>
            </thead>
            <tbody>
            <?php
			foreach($sql as $d){
				echo'
				<tr>
				<td>'.$d['totMhs'].' </td>
				<td>'.Html::a('<i class="fa fa-unlock"> </i> '.$d['tgs1'],['#']).' </td>
				<td>'.Html::a('<i class="fa fa-unlock"> </i> '.$d['tgs2'],['#']).' </td>
				<td>'.Html::a('<i class="fa fa-unlock"> </i> '.$d['tgs3'],['#']).' </td>
				<td>'.Html::a('<i class="fa fa-unlock"> </i> '.$d['quiz'],['#']).' </td>
				<td>'.Html::a('<i class="fa fa-unlock"> </i> '.$d['uts'],['#']).' </td>
				<td>'.Html::a('<i class="fa fa-unlock"> </i> '.$d['uas'],['#']).' </td>
				</tr>';
			}
			?>
            </tbody>
        </table>
        <?php	
		}
        ?>
		</div>

    </div>
</div>


<div class="panel panel-primary">
	<div class="panel-heading">
    	<span class="panel-title"><?= $this->title ?></span>
    </div>
    <div class="panel-body">
		<div class="col-sm-6">
		<?php
        $form = ActiveForm::begin([
			'type'=>ActiveForm::TYPE_HORIZONTAL,
			'formConfig'=>[
				'labelSpan'=>3,
			]
		]); 
		
		?>
        
        <?php 
		//foreach($sql as $d):
		?>
        <fieldset>
        	<legend>UTS</legend>
            <?php
			echo Form::widget(
				[
					'model' =>$mVakasi,
					'form' => $form,
					'formName'=>'UTS',
					'columns' => 1,
					'attributes' => [
						'NUTS'=>['type'=>Form::INPUT_TEXT,],
						'PR[UTS]'=>[
							'lable'=>'Honor Pengawas',
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

					],
				])
			?>
        </fieldset>

        <fieldset>
        	<legend>UTS SUSULAN</legend>
        </fieldset>


        <fieldset>
        	<legend>UAS</legend>
            <?php
			echo Form::widget(
				[
					'form' => $form,
					'model' =>$mVakasi,
					'columns' => 1,
					'attributes' => [
						'NUAS'=>['type'=>Form::INPUT_TEXT,],
						'AWS2'=>['type'=>Form::INPUT_TEXT,],

					],
				])
			?>        
        </fieldset>

        <fieldset>
        	<legend>UAS SUSULAN</legend>
        </fieldset>

        
        <?php
		/*
        echo Form::widget([
            'formName' => 'uts_',
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'kalender'=>[
                    'label'=>'Tahun Akademik',
                    'options'=>['placeholder'=>'...'],
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => 
                            ArrayHelper::map(app\models\Kalender::find()->all(),'kr_kode',
                                function($model,$defaultValue){
                                    //print_r($model->kr->kr_nama);die();
                                    return $model->kr->kr_kode." : ".$model->kr->kr_nama;
                                }		
                            ),
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ],
                ], 
        
                'jurusan'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                    'options'=>[
                        'type'=>2,
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'select2Options'=>	[
                            'pluginOptions'=>['allowClear'=>true]
                        ],
                        'pluginOptions' => [
                                'depends'		=>	['bobotnilai-kalender'],
                                'url' 			=> 	Url::to(['/pengajar/jurusan']),
                                'loadingText' 	=> 	'Loading...',
                        ],
                    ],
                ], 
        
                'kln_id'=>[
                    'label'=>'Program',
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                    'options'=>[
                        'type'=>2,
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'select2Options'=>	[
                            'pluginOptions'=>['allowClear'=>true]
                        ],
                        'pluginOptions' => [
                                'depends'		=>	['bobotnilai-jurusan'],
                                'url' 			=> 	Url::to(['/akademik/klnpro']),
                                'loadingText' 	=> 	'Loading...',
                        ],
                    ],
                ], 
        
                'mtk_kode'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                    'options'=>[
                        'type'=>2,
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'select2Options'=>	[
                            'pluginOptions'=>['allowClear'=>true]
                        ],
                        'pluginOptions' => [
                                'depends'		=>	['bobotnilai-jurusan'],
                                'url' 			=> 	Url::to(['/akademik/klass']),
                                'loadingText' 	=> 	'Loading...',
                        ],
                    ],
                ], 
                'ds_nidn'=>[
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => app\models\Funct::DSN(),
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ],
                ], 
            ]
            ]);
			*/
        ?>
        <?= Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan'), ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); 
		//unset($_SESSION['UTS']);unset($_SESSION['UAS']);
		?>
		</div>
        
        
        <div class="col-sm-6">
            <h4>Faktur</h4>
            <table class="table table-bordered">
            	<tr><th colspan="3">UTS</th></tr>
                <?= (isset($_SESSION['UTS']['TGS1'])?
					'<tr>
						<td>Tugas 1</td><td width="1">:</td>
						<td>'.number_format($_SESSION['UTS']['TGS1']['q'])."</td>
						<td>".number_format($_SESSION['UTS']['TGS1']['q'])."x".number_format($_SESSION['UTS']['TGS1']['h'])."</td>
						<td>".number_format($_SESSION['UTS']['TGS1']['t'])."</td>
					</tr>"
					:"").
					(isset($_SESSION['UTS']['UTS'])?
					'<tr>
						<td>UTS</td><td width="1">:</td>
						<td>'.number_format($_SESSION['UTS']['UTS']['q'])."</td>
						<td>".number_format($_SESSION['UTS']['UTS']['q'])."x".number_format($_SESSION['UTS']['UTS']['h'])."</td>
						<td>".number_format($_SESSION['UTS']['UTS']['t'])."</td>
					</tr>"
					:"").
					(isset($_SESSION['UTS']['NUTS'])?
					'<tr>
						<td>NASKAH SOAL</td><td width="1">:</td>
						<td>'.number_format($_SESSION['UTS']['NUTS']['q'])."</td>
						<td>".number_format($_SESSION['UTS']['NUTS']['q'])."x".number_format($_SESSION['UTS']['NUTS']['h'])."</td>
						<td>".number_format($_SESSION['UTS']['NUTS']['t'])."</td>
					</tr>"
					:"").
					(isset($_SESSION['UTS']['AWS'])?
					'<tr>
						<td>Honor Pengawas</td><td width="1">:</td>
						<td>'.number_format($_SESSION['UTS']['AWS']['q'])."</td>
						<td>".number_format($_SESSION['UTS']['AWS']['q'])."x".number_format($_SESSION['UTS']['AWS']['h'])."</td>
						<td>".number_format($_SESSION['UTS']['AWS']['t'])."</td>
					</tr>"
					:"")
					
				?>
            	<tr><th colspan="3">UTS SUSULAN</th></tr>
            	<tr><th colspan="3">UAS</th></tr>
                <?= (isset($_SESSION['UAS']['TGS2'])?
					'<tr>
						<td>Tugas 2</td><td width="1">:</td>
						<td>'.number_format($_SESSION['UAS']['TGS2']['q'])."</td>
						<td>".number_format($_SESSION['UAS']['TGS2']['q'])."x".number_format($_SESSION['UAS']['TGS2']['h'])."</td>
						<td>".number_format($_SESSION['UAS']['TGS2']['t'])."</td>
					</tr>"
					:"").
					(isset($_SESSION['UAS']['UAS'])?
					'<tr>
						<td>UAS</td><td width="1">:</td>
						<td>'.number_format($_SESSION['UAS']['UAS']['q'])."</td>
						<td>".number_format($_SESSION['UAS']['UAS']['q'])."x".number_format($_SESSION['UAS']['UAS']['h'])."</td>
						<td>".number_format($_SESSION['UAS']['UAS']['t'])."</td>
					</tr>"
					:"")
					
				?>
            	<tr><th colspan="3">UAS SUSULAN</th></tr>
            
            </table>
            
        </div>
        
    </div>
</div>

