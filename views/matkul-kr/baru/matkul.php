<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\grid\GridView;
use app\models\Funct;
?>

<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> <?= $model->jr->jr_jenjang.' '.$model->jr->jr_nama ?> (<?= $model->kode ?>)</span>
            <div class="pull-right">
                <?php
                if($model->aktif==1){echo '<h4><span class="label label-success">Status Aktif</span></h4>';}
                if($model->aktif==0){echo '<h4><span class="label label-danger">Status Tidak Aktif</span></h4>';}
                ?>


            </div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">
            ket : <?= $model->ket ?>
        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <?php
        $form1 = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig'=>[
                'labelSpan'=>2,'options'=>['onSubmit'=>'return confirm("Hapus Data?")']]
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
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
                [
                    'label'=>'Matakuliah (Courses)',
                    'attribute'=>'matkul',
                    'value'=>function($model){
                        return $model->matkul." (".($model->matkul_en?:"-------").")";
                    }

                ],
                [
                    'label'=>'Jenis',
                    'value'=>function($model){
                        return $model->mk->jns->jenis;
                    }

                ],
                [
                    'label'=>'Katagori',
                    'value'=>function($model){
                        return $model->mk->kat->kategori;
                    }

                ],
                [
                    'label'=>'Status',
                    'value'=>function($model){
                        $status=[0=>'Wajib','Pilihan'];
                        return $status[$model->mk->status];
                    }

                ],

                ['attribute'=>'sks','pageSummary'=>true,'width'=>'1%'],
                ['label'=>'Prasyarat','attribute'=>'sub_kode',],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'visible'=>Funct::acc('/matkul-kr/delete'),
                    'template'=>'{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            if(!Funct::acc('/matkul-kr/delete')){return false;}
                            return Html::a('<span class="fa fa-trash"></span>',
                                Yii::$app->urlManager->createUrl(['matkul-kr-det/delete','id'=>$model->id_kr]),
                                [
                                    'class'=>'btn btn-danger btn-xs',
                                    'data'=>['confirm' => "Hapus Data ini?",'method'=>'post',]
                                ]
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
                'heading'=>false,
                'type' => 'info',
                'before' =>false,
                'after' =>
                    (!Funct::acc('/matkul-kr/delete')?"":Html::submitButton("Hapus Data",["class"=>'btn btn-danger btn-sm'])).' '
                    .(
                    !Funct::acc('/matkul-kr/add-matkul')?"":
                        Html::a('<i class="fa fa-plus"></i> Matakuliah Non Konsentrasi', ['add-matkul','id'=>$model->id], ['class' => 'btn btn-success btn-sm','style'=>'color:#fff;']).' '
                        .Html::a('<i class="fa fa-plus"></i> Matakuliah Konsentrasi', ['add-matkul','id'=>$model->id], ['class' => 'btn btn-success btn-sm','style'=>'color:#fff;'])
                    ),


                'footer'=>false,
            ],
        ]);
        ActiveForm::end();
        ?>
    </div>


</div>

