<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
$this->title = "Data Pembayaran";
?>
<div class="panel panel-primary">
    <div class="panel-heading">Biodata Mahasiswa</div>
    <div class="panel-body">
    <table class='table table-hover table-condensed'>
        <tr>
            <td>NIM</td>
            <td><?= $mhs->mhs_nim; ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td><?= Funct::profMhs($mhs->mhs_nim,"Nama");?></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><?= $jr->jr_id."-".$jr->jr_nama;?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td><?= Funct::getProgramKeuangan($mhs->mhs_nim); ?></td>
        </tr>
        <tr>
            <td>Pembimbing</td>
            <td><?= Funct::nameWali($mhs->ds_wali,"ds_nm");?></td>
        </tr>        
    </table>
</div>
</div>
<?php
echo Html::a('<span class="glyphicon glyphicon-print"></span> Cetak',
                            Yii::$app->urlManager->createUrl(
                                ['keuangan/cetak','id' => $mhs->mhs_nim]),['title' => Yii::t('yii', 'Cetak'), 'class'=>'btn btn-success' ]
                            );
echo "<br/><br/>";
yii\bootstrap\Modal::begin([
	'id' =>'myModal',
	]);
yii\bootstrap\Modal::end();


   	if(isset($pkrs)){
	    //Pjax::begin(); 
	    echo GridView::widget([
	        'dataProvider' => $pkrs,
	        'filterModel' => $pkrs->getModels(),
	        'columns' => [
	            ['class' => 'kartik\grid\SerialColumn'],
                'Tahun',
                [
                    'header'=>'DPP',
            		'attribute'=>'DPP',
            		'value'=>function($data){
            			return number_format($data['DPP'],0,'.','.');
            		}
            	],
            	[
                    'header'=>'SKS',
            		'attribute'=>'SKS',
            		'value'=>function($data){
            			return number_format($data['SKS'],0,'.','.');
            		}
            	],
            	[
                    'header'=>'Praktek',
            		'attribute'=>'Praktek',
            		'value'=>function($data){
            			return number_format($data['Praktek'],0,'.','.');
            		}
            	],
            	'Jml_SKS',
            	'Jml_Praktek',
            	[
                    'header'=>'Total',
            		'attribute'=>'Total',
            		'value'=>function($data){
            			return number_format($data['Total'],0,'.','.');
            		}
            	],
            	[
                    'header'=>'Sisa',
            		'attribute'=>'Sisa',
            		'value'=>function($data){
            			return number_format($data['Sisa'],0,'.','.');
            		}
            	],
                'Status',
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' =>
                    [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['keuangan/keuangandetail','tipe'=>'KRS','id' => $model['id'],'ket'=>$model['Tahun']]),['title' => Yii::t('yii', 'View'), 
                            		'data-toggle'=>"modal",
                                    'data-target'=>"#myModal",
                                ]
                            );
                        },
                    ],
                ]
	        ],
	        'responsive' => true,
	        'hover' => true,
	        'condensed' => true,
	        'floatHeader' => false,
	        'export'=>false,
	        'panel' => [
	            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> KRS </h3>',
	            'type' => 'info',
	            //'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
	            //'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
	            'showFooter' => false
	        ],
	        //'showPageSummary' => true,
	    ]); //Pjax::end();
    } 
?>
<br/>
<?php

   	if(isset($pbeban)){
	    //Pjax::begin(); 
	    echo GridView::widget([
	        'dataProvider' => $pbeban,
	        'filterModel' => $pbeban->getModels(),
	        'columns' => [
	            ['class' => 'kartik\grid\SerialColumn'],
                'Jenis',
                'Tahun',
            	[
                    'header'=>'Total',
            		'attribute'=>'Total',
            		'value'=>function($data){
            			return number_format($data['Total'],0,'.','.');
            		}
            	],
            	[
                    'header'=>'Sisa',
            		'attribute'=>'Sisa',
            		'value'=>function($data){
            			return number_format($data['Sisa'],0,'.','.');
            		}
            	],
                'Status',
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' =>
                    [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['keuangan/keuangandetail','tipe'=>'Beban','id' => $model['id'],'ket'=>$model['Jenis']]),['title' => Yii::t('yii', 'View'),
                            	'data-toggle'=>"modal",
                                'data-target'=>"#popupModal"]
                            );
                        },
                    ],
                ]
	        ],
	        'responsive' => true,
	        'hover' => true,
	        'condensed' => true,
	        'floatHeader' => false,
	        'export'=>false,
	        'panel' => [
	            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Non Rutin </h3>',
	            'type' => 'info',
	            //'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
	            //'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
	            'showFooter' => false
	        ],
	        //'showPageSummary' => true,
	    ]); //Pjax::end();
    } 


$this->registerJs("
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        $.post(href)
            .done(function( data ) {
                modal.find('.modal-body').html(data)
            });
        })
");
?>