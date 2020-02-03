<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use yii\widgets\Pjax;

?>
<div class="maas-create">
    <div class="page-header">
        <h1>Data Mahasiswa</h1>
    </div>
    <div class="maas-form">
    <?php
    	$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,
        	'options'=>['enctype'=>'multipart/form-data'] 
        ]);
    	echo Form::widget([ // hide label and extend input to full width
		    'model'=>$model,
		    'form'=>$form,
		    'columns'=>1,
		    'attributes'=>[
		        'searchString'=>[
		            //'label'=>true, 
		            'options'=>[
		                'placeholder'=>'NPM atau Nama'
		            ]
		        ],
		    ]
		]);
		?>
		<div class="col-md-offset-2">
		<?
    	echo Html::submitButton(Yii::t('app', 'Search'),
        	['class' =>'btn btn-primary']
    	);
    	ActiveForm::end(); 
    ?>
    </div>
    </div>
</div>
<br/>
<?php
   	if(isset($dataProvider)){
	    //Pjax::begin(); 
	    echo GridView::widget([
	        'dataProvider' => $dataProvider,
	        'filterModel' => $dataProvider->getModels(),
	        'columns' => [
	            ['class' => 'kartik\grid\SerialColumn'],
	            'NPM',
                'Nama',
                'Jurusan',
                'Program',
                'Angkatan',
                'TA',
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' =>
                    [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['keuangan/pembayaran','id' => $model['NPM']]),['title' => Yii::t('yii', 'View'),]
                            );
                        },
                    ],
                ]
	        ],
	        'responsive' => true,
	        'hover' => true,
	        'condensed' => true,
	        'floatHeader' => true,
	        'export'=>false,
	        'panel' => [
	            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
	            'type' => 'info',
	            //'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
	            //'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
	            'showFooter' => false
	        ],
	        //'showPageSummary' => true,
	    ]); //Pjax::end();
    } 
?>