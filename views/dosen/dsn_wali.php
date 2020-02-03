<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use app\models\Funct;
use app\models\Kurikulum;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = $model->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['dsn']];
$this->params['breadcrumbs'][] = $this->title;
$ApproveUrl = Url::To(['dosen/krs-approve']);
$this->registerJs(" 
    $(document).ready(function(){
    $('#btnApprove').click(function(){

        var checks = $('input[name^=detailKRS]');

        var checked = checks.filter(':checked').map(function () {
            return this.value;
        }).get();

        var unchecked = checks.not(':checked').map(function () {
            return this.value;
        }).get();
        
        console.log(checked);

        console.log(unchecked);

     
        

        
           
        var mhs_nim = $('#masterGrid').yiiGridView('getSelectedRows');

        var paketJNE = []; //wkwkwkwkwkwk
        for (var i = 0; i < mhs_nim.length; i++) {
            if (mhs_nim[i].length>5) {             
            
            var krs_data = {};
            krs_data.mhs = mhs_nim[i];
            krs_data.allKRS =   $('input[name^='+mhs_nim[i]+']').serialize();
            krs_data.allun =    $('input:checkbox[name^='+mhs_nim[i]+']:checked').serialize();
            krs_data.data = $('#childGrid_'+mhs_nim[i]).yiiGridView('getSelectedRows');
            paketJNE.push(krs_data);
            }
        };
          $.ajax({
            type: 'POST',
            url : '$ApproveUrl',
            data : {mhs_krs : paketJNE},
            success : function() {
                //$.pjax.reload({container: '#pjaxKRS'});
            }
        });
    });
   
    $('input[name^=selection_all]').change(function(e){
        $('input[name^=selection]').prop('checked', $(this).is(':checked'));
        $('input[name^=selection]').each(function (index)
        {
            $('input[name^='+$(this).val()+']').prop('checked', $(this).is(':checked'));
        });
    });

    $('input[name^=selection]').change(function(e){
        $('input[name^='+$(this).val()+']').prop('checked', $(this).is(':checked'));
    });


    });", \yii\web\View::POS_READY);
?>
<div class="dosen-view">

    <?= DetailView::widget([
        'model' => $model,
        'condensed'=>true,
        'hover'=>true,
        'enableEditMode'=>false,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
                    'heading'=>$this->title,
                    'type'=>DetailView::TYPE_PRIMARY,
                ],
        'attributes' => [
            'ds_nidn',
            'ds_user',
            'ds_nm',
            'ds_kat',
            'ds_email:email',
        ],
        'enableEditMode'=>false,
    ]) ?>

<div class="angge-search">
 <?php Pjax::begin(['enablePushState'=>FALSE]); ?>
    <?php $form = ActiveForm::begin([
        'method' => 'post',
    ],['data-pjax'=>1]); ?>
    <?= 
        $form->field($Kurikulum, 'kr_kode')->widget(Select2::classname(), [
            'data' =>app\models\Funct::AKADEMIK(),
            'language' => 'en',
            'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Tahun Akademik');
     ?>
      
    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['dosen/krs'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
     
    <?php 
     if (!empty($Kurikulum->kr_kode)){
     Pjax::begin(['id' => 'pjaxKRS']); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'    => 'masterGrid',
        'columns' => [
            ['class' => '\kartik\grid\CheckboxColumn'], 
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> @function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Progaram-'],
			],
            'mhs_nim',
            [
                'attribute' => 'mhs.people.Nama',
                'width'=>'50%',
            ],
             [
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'expandAllTitle' => 'Expand all',
                                'collapseTitle' => 'Collapse all',
                                'expandIcon'=>'<span class="glyphicon glyphicon-expand"></span>',
                                'value' => function ($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                },
                                'detail'=>function ($model, $key, $index, $column) use($Kurikulum) {
                                        return Yii::$app->controller->renderPartial('mhs_krs', ['model'=>$model,'Kurikulum' => $Kurikulum]);
                                },
                                //'detailUrl'  =>  Url::To(['dosen/krs-mhs','nim'=> 'mhs_nim']),

                    'detailOptions'=>[
                        'class'=> 'kv-state-enable',
                    ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Approval KRS Mahasiswa',
			'before'=> '<button type="button" id="btnApprove" class="btn btn-success" aria-label="Bold"><span class="glyphicon glyphicon-ok"></span>Approve Data Terpilih</button>' ,//Html::a('<i class="glyphicon glyphicon-checklist"></i> Approve Data Terpilih', ['akademik/dsn-wali','id'=>$model->ds_id], ['class' => 'btn btn-success pull-right']),
        ]
    ]); Pjax::end(); 

    }//End IF

    ?>

</div>
 