<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\grid\GridView;
use app\models\Funct;

$sql=Yii::$app->db->createCommand("select * from tbl_matkul  WHERE  jr_id=$model->jr_id and mtk_kode not in(
                     SELECT kode FROM matkul_kr_det WHERE id_kr='$model->id' and isnull(RStat,0)=0
                )")->queryAll();
$arr=[];foreach($sql as $d){$arr[$d['mtk_kode']]=" [$d[mtk_kode] | $d[mtk_sks] SKS] : $d[mtk_nama] ";}

if(Funct::acc('/matkul-kr/add-matkul')){
	$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]);
	echo Form::widget([
		'form' => $form,
		'formName'=>'kode',
		'columns' => 2,
		'attributes' => [
			'kode'=>[
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' => $arr?$arr:[""],
					'options' => ['fullSpan'=>6,'placeholder' => 'matakuliah','multiple'=>true],
					'pluginOptions' => ['allowClear' => true],
				],
			],
			[
				'label'=>'',
				'type'=>Form::INPUT_RAW,
				'value'=> Html::submitButton(Yii::t('app', 'Tambah Matkul'),['class' =>'btn btn-primary','style'=>'text-align:right']
				)
			],
		]
	]);
	ActiveForm::end();
}
?>
<p></p>
<?php
$form1 = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig'=>[
        'labelSpan'=>2,'options'=>['onSubmit'=>'return confirm("Hapus Data?")']]
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute'	=> 'semester',
            'width'=>'20%',
            'value'=>function($model){ return "Semester ".$model->semester;},
            'group'=>true,  // enable grouping,
            'groupedRow'=>true,                    // move grouped column to a single grouped row
            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
        ],

        [
            'class'=>'kartik\grid\CheckboxColumn',
            'headerOptions'=>[
                'class'=>'kartik-sheet-style'
            ],
            'options'=>['value'=>$model->id],
			'visible'=>Funct::acc('/matkul-kr/delete'),
        ],
        ['class' => 'kartik\grid\SerialColumn',],
        ['attribute'=>'kode','width'=>'10%','pageSummary'=>'Total SKS',],
        ['attribute'=>'matkul',],
        ['attribute'=>'sks','pageSummary'=>true,'width'=>'1%'],
        ['label'=>'Prasyarat','attribute'=>'kode_',],
        [
            'class' => 'kartik\grid\ActionColumn',
			'visible'=>Funct::acc('/matkul-kr/delete'),
            'template'=>'{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
					if(!Funct::acc('/matkul-kr/delete')){return false;}
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        Yii::$app->urlManager->createUrl(['matkul-kr-det/delete','id'=>$model->id]),
                        ['data'=>['confirm' => "Hapus Data ini?",'method'=>'post',]]
                    );
                },
				
            ],
        ],
    ],
    'showPageSummary' => true,
    'responsive' => false,
    'hover' => true,
    'condensed' => true,
    'floatHeader' => false,
    'panel' => [
        'header'=>true,
        'heading'=>(!Funct::acc('/matkul-kr/delete')?"":Html::submitButton("Hapus Data",["class"=>'btn btn-primary'])),
        'type' => 'info',
        'before' =>false,
        'after' => (!Funct::acc('/matkul-kr/delete')?"":Html::submitButton("Hapus Data",["class"=>'btn btn-primary'])),
        'footer'=>false,
    ],
]);
ActiveForm::end();
?>