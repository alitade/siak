<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
$this->title = 'Prediksi Pengambilan Matakuliah';
$this->params['breadcrumbs'][] = $this->title;
#$subAkses='';
?>
<div class="panel panel-info">
    <div class="panel-heading"><h4 class="panel-title">Perkiraan Peserta perkuliahan</h4></div>
    <div class="panel-body">
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]);
    echo Form::widget([
        'form' => $form,
        'formName'=>'S',
        'columns' => 2,
        'attributes' => [
            'jr_id'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => 'Pilih Jurusan ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'type'=>Form::INPUT_RAW,
                'value'=> Html::submitButton( Yii::t('app', 'Cari'),['class' =>'btn btn-primary','style'=>'text-align:right','name'=>'cari']).
                    " ".(!Funct::acc('/matkul-kr/info-ex')?"":Html::submitButton( Yii::t('app', 'Export Excel'),['class' =>'btn btn-primary','style'=>'text-align:right','name'=>'ex']))

            ],
        ]
    ]);
    ActiveForm::end();
	
    ?>
    </div>
    <div class="panel-body">
    <?php if($sql){?>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Matkul</th>
                    <th rowspan="2">SKS</th>
                    <th rowspan="2">&sum;Mhs</th>
                    <th rowspan="2">&sum;Tercapai</th>
                    <th colspan="6">Detail </th>
                    <th rowspan="2">&sum;Sisa</th>
                </tr>
                <tr>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                    <th>E</th>
                    <th>N</th>
                </tr>
                </thead>
                <tbody>
                <?php $n=0; foreach($sql as $d): $n++;?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= $d['kode'] ?></td>
                        <td><?= $d['matkul'] ?></td>
                        <td><?= $d['sks'] ?></td>
                        <td><?= $d['mhs'] ?></td>
                        <td><?= $d['total'] ?></td>
                        <td><?= $d['A'] ?></td>
                        <td><?= $d['B'] ?></td>
                        <td><?= $d['C'] ?></td>
                        <td><?= $d['D'] ?></td>
                        <td><?= $d['E'] ?></td>
                        <td><?= $d['N'] ?></td>
                        <td><?= $d['sisa'] ?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            
	<?php }else{echo"Data Tidak Ada";}?>
    
    </div>
</div>
