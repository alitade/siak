<br/><br/>
<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
if(isset($history)){
	    //Pjax::begin(); 
	    echo GridView::widget([
	        'dataProvider' => $history,
	        'filterModel' => $history->getModels(),
	        'columns' => [
	            ['class' => 'kartik\grid\SerialColumn'],
                'Tanggal',
                'JTU',
            	[
                    'header'=>'Jumlah',
            		'attribute'=>'Jumlah',
            		'value'=>function($data){
            			return number_format($data['Jumlah'],0,'.','.');
            		}
            	],
                'Keterangan',
                'Jenis',
	        ],
	        'panel' => [
	            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Detail Pembayaran '.$ket.'</h3>',
	            'type' => 'info',
	            //'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
	            //'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
	            'showFooter' => false
	        ],
	        //'showPageSummary' => true,
	    ]); //Pjax::end();
    }else{
    	echo "<b>Tidak tersedia</b>";
    } 
?>