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
        <h1>Laporan Pengeluaran</h1>
    </div>
    <div class="maas-form">
    <?php
    	$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,
        	'options'=>['enctype'=>'multipart/form-data'] 
        ]);
    	echo $form->field($model, 'dt1')->widget(DateControl::classname(), [
        	'type'=>DateControl::FORMAT_DATE
    	]);
    	echo $form->field($model, 'dt2')->widget(DateControl::classname(), [
        	'type'=>DateControl::FORMAT_DATE
    	]);
    	echo Html::submitButton(Yii::t('app', 'Search'),
        	['class' =>'btn btn-primary']
    	);
    	ActiveForm::end(); 
    ?>
    </div>
</div>
<br/>
<br/>
<?php
   	if(isset($dataProvider)){
   		?>
   		<div class="page-header">
        	<h1 align="right">Total Pengeluaran : Rp <?= number_format($SummaryTotal,0,'.','.') ?></h1>
    	</div>


   		<?php
	    Pjax::begin(); echo GridView::widget([
	        'dataProvider' => $dataProvider,
	        'filterModel' => $dataProvider->getModels(),
	        'columns' => [
	            ['class' => 'kartik\grid\SerialColumn'],
	            'Tanggal',
	            'Transaksi',
            	[
                    'attribute'=>'Jumlah',
                    'value'=>function($data){
                        return "Rp.".number_format($data['Jumlah'],0,'.','.');
                    }
                ],
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
	    ]); Pjax::end();
    } 
?>
