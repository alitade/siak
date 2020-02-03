<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p></p>
<div class="user-view">
    <?= DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'enableEditMode'=>false,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'User',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            'username',
            'username2',
            'name',
            'posisi',            
        ]
    ]); 
	?>
    <?php if (Yii::$app->authManager->checkAccess($model->id,'SubAkses')):?>
    <div class="panel panel-primary">
    	<div class="panel-heading"><h4 class="panel-title">SubAkses</h4></div>
        <div class="panel-body">
        	<?php
			
			$SubAkses = app\models\SubAkses::find()->where("kode not in(select kode from sub_akses_det where user_id=$model->id)")->all();
			$arr=[];
			foreach($SubAkses as $d){
				$jr="";
				if($d->tbl='jurusan'){
					$jr= \app\models\Funct::JURUSAN()[$d->nilai];	
				}
				$arr[$d->kode]="$d->kode $d->tbl | $d->nilai ".($jr?"($jr)":"");
			}
			
			$form = ActiveForm::begin([
				'type' => ActiveForm::TYPE_HORIZONTAL,
				'formConfig'=>['labelSpan'=>2],
				'action'=>Url::to(['/user/add-sub','id'=>$model->id])
			]);
			echo Form::widget([
				'form' => $form,
				'formName'=>'kode',
				'columns' => 2,
				'attributes' => [
					'kode'=>[
						'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
						'options'=>[
							'data' => $arr?$arr:[""],
							'options' => ['fullSpan'=>6,'placeholder' => 'Sub Akses','multiple'=>true],
							'pluginOptions' => ['allowClear' => true],
						],
					],
					[
						'label'=>'',
						'type'=>Form::INPUT_RAW,
						'value'=> Html::submitButton(Yii::t('app', 'Tambah Sub Akses'),['class' =>'btn btn-primary','style'=>'text-align:right']
						)
					],
				]
			]);
			ActiveForm::end();
			
			?>
			<table class="table table-bordered">
            	<thead>
                <tr>
                	<th>No</th>
                	<th>Sub Akses</th>
                	<th>Akses Data</th>
                	<th> </th>
                </tr>
                </thead>
                <tbody>
                <?php 
				if($modAkses): $n=0;foreach($modAkses as $d):$n++?>
                <tr>
                	<td><?= $n ?></td>
                	<td><?= $d->akses->kode ?></td>
                	<td><?= $d->akses->nilai ?></td>
                	<td><?= Html::a("Hapus",["/user/sub-delete","id"=>$model->id,'kode'=>$d->kode]) ?></td>
                </tr>
                        
				<?php endforeach;endif; ?>
                </tbody>
                
            </table>
        
        </div>
    	
    </div>
    
    <?php endif;?>
    
</div>
