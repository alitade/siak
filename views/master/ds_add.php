<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


$this->title = 'Tambah Data Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Data Dosen', 'url' => ['master/ds']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="panel panel-primary">
    <div class="panel-heading">
    <i class="fa fa-plus"></i> Tambah Data Dosen
    </div>
   <div class="panel-body">
       <?php
       $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
       ?>
       <div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
           <span style="font-size:16px;font-weight:bold">Biodata</span>
           <div class="pull-right">
           </div>
           <div style="clear: both"></div>
       </div>
       <p></p>
       <?=
       Form::widget([
           'model' => $mBio,
           'form' => $form,
           'columns' => 1,
           'attributes' => [
               'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'No KTP']],
               [
                   'label'=>'Nama',
                   'columns'=>3,
                   'attributes'=>[
                       'glr_depan'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Gelar'],],],],
                       'nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Lengkap']],
                       'glr_belakang'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Gelar'],],],],
                   ],
               ],

               [
                   'label'=>'Tempat & Tanggal Lahir',
                   'columns'=>2,
                   'attributes'=>[
                       'tempat_lahir'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Tempat'],],],],
                       'tanggal_lahir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],
                   ]

               ],
               'agama'=>[
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                   'options'=>[
                       'dataset' => [[
                           'local'=>app\models\Functdb::agama_dft(1),
                           'limit'=>10,
                       ]],
                       'options' => ['fullSpan'=>6,'placeholder' => 'Agama',],
                   ],
               ],
               'jk'=>['label'=>'Jenis Kelamin',
                   'type'=> Form::INPUT_RADIO_LIST,
                   'items'=>['0'=>'Perempuan','1'=>'Laki-Laki'],
                   'options'=>['inline'=>true]
               ],
               'status_ktp'=>[
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                   'options'=>[
                       'dataset' => [[
                           'local'=>app\models\Functdb::statKTP(1),
                           'limit'=>10,
                       ]],
                       'options' => ['fullSpan'=>6,'placeholder' => 'Status Pernikahan',],
                   ],
               ],
               'pekerjaan'=>[
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                   'options'=>[
                       'dataset' => [[
                           'local'=>app\models\Functdb::kerja_dft(1),
                           'limit'=>10,
                       ]],
                       'options' => ['fullSpan'=>6,'placeholder' => 'Pekerjaan',],
                   ],
               ],
               'add'=>['label'=>'Alamat KTP','type'=> Form::INPUT_TEXT,'options'=>['placeholder','id'=>'cadd']],
               [
                   'label'=>'&nbsp;',
                   'columns'=>6,
                   'attributes'=>[
                       'rt'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],'options'=>['id'=>'crt']],
                       'rw'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],'options'=>['id'=>'crw']],
                       'keldes'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckd']],
                       'kec'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckc']],
                   ],
               ],
               [
                   'label'=>'&nbsp;',
                   'columns'=>4,
                   'attributes'=>[
                       'kota'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],'options'=>['id'=>'ckt']],
                       'propinsi'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Propinsi'],],],'options'=>['id'=>'cpr'],'columnOptions'=>['colspan'=>2]],
                       'kode_pos'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,'options'=>['id'=>'ckdp']],
                   ],

               ],

               #alamat tinggal

               'add1'=>[
                   'label'=>'Alamat Tinggal',
                   'columns'=>6,
                   'attributes'=>[
                       'add1'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Alamat Tinggal Saat Ini','id'=>'cadd1'],'columnOptions'=>['colspan'=>5]],
                       'sama'=>['label'=>'Sesuai KTP','type'=> Form::INPUT_CHECKBOX,'options'=>['value'=>1,'id'=>'csm'] ]
                   ]
               ],
               [
                   'label'=>'&nbsp;',
                   'columns'=>6,
                   'attributes'=>[
                       'rt1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],'options'=>['id'=>'crt1']],
                       'rw1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],'options'=>['id'=>'crw1']],
                       'keldes1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckd1']],
                       'kec1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckc1']],
                   ],
               ],
               [
                   'label'=>'&nbsp;',
                   'columns'=>2,
                   'attributes'=>[
                       'kota_tinggal'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],'options'=>['id'=>'ckt1']],
                       'kode_pos_tinggal'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,'options'=>['id'=>'ckdp1']],
                   ],

               ],
               [
                   'label'=>'Kontak',
                   'columns'=>2,
                   'attributes'=>[
                       'tlp'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Tlp.'],],],],
                       'email'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Email'],],],'type'=> Form::INPUT_TEXT,],
                   ],
               ],
           ],
       ]);
       ?>
       <div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
           <span style="font-size:16px;font-weight:bold">Dosen</span>
           <div class="pull-right">
           </div>
           <div style="clear: both"></div>
       </div>
       <p></p>

       <?=
        Form::widget([
           'model' => $model,
           'form' => $form,
           'columns' => 1,
           'attributes' => [
               'ds_nidn'=>[
                   'label'=>'NIDN',
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                   'options'=>[
                       'dataset' => [[
                           'local'=>app\models\Funct::DSN(2),
                           'limit'=>10,
                       ]],
                       'options' => [
                           'fullSpan'=>6,
                           'placeholder'=>'Dosen NIDN'
                       ],
                   ],
               ],

               'id_tipe'=>[
                   'label'=>'*) Kategori',
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                   'options'=>[
                       'data' => app\models\Functdb::tipeDosen(0),
                       'options' => ['fullSpan'=>6,'placeholder' => 'Kategori',],
                       'pluginOptions' => ['allowClear' => true],
                   ],
               ],

               'jr_id'=>[
                   'label'=>'Jurusan',
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                   'options'=>[
                       'data' => app\models\Functdb::jurusan_plh(),
                       'options' => ['fullSpan'=>6,'placeholder' => 'Jurusan',],
                       'pluginOptions' => ['allowClear' => true],
                   ],
               ],


               'ds_user'=>[
                   'label'=>' *) Username',
                   'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                   'options'=>[
                       'dataset' => [[
                           'local'=>app\models\Funct::DSN(2,'ds_user'),
                           'limit'=>10,
                       ]],
                       'options' => [
                           'fullSpan'=>6,
                           'placeholder'=>'Username'
                       ],
                   ],
               ],
           ]


       ]);
       ?>


       <?php
       echo Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']);
       ActiveForm::end();
       ?>

    </div>


</div>

<span id='chadd1' style='display: none'></span>
<span id='chrt1' style='display: none'></span>
<span id='chrw1' style='display: none'></span>
<span id='chkd1' style='display: none'></span>
<span id='chkc1' style='display: none'></span>
<span id='chkt1' style='display: none'></span>
<span id='chkdp1' style='display: none'></span>

<?php
$this->registerJs(
    '
    $(document).ready(
        function () {
            $("#cadd1").change(function(){$("#chadd1").val($("#cadd1").val());});$("#crt1").change(function(){$("#chrt1").val($("#crt1").val());});$("#crw1").change(function(){$("#chrw1").val($("#crw1").val());});$("#ckd1").change(function(){$("#chkd1").val($("#ckd1").val());});$("#ckc1").change(function(){$("#chkc1").val($("#ckc1").val());});$("#ckt1").change(function(){$("#chkt1").val($("#ckt1").val());});$("#ckdp1").change(function(){$("#chkdp1").val($("#ckdp1").val());});
            
            $("#csm").click(function () {
                $("#cadd1").val($("#chadd1").val());$("#crt1").val($("#chrt1").val());$("#crw1").val($("#chrw1").val());$("#ckd1").val($("#chkd1").val());$("#ckc1").val($("#chkc1").val());$("#ckt1").val($("#chkt1").val());$("#ckdp1").val($("#chkdp1").val());
                if($("#csm:checked").length===1){
                    $("#cadd1").val($("#cadd").val());$("#crt1").val($("#crt").val());$("#crw1").val($("#crw").val());$("#ckd1").val($("#ckd").val());$("#ckc1").val($("#ckc").val());    
                    $("#ckt1").val($("#ckt").val());$("#ckdp1").val($("#ckdp").val());    
                }              
            });

        }
    );
    
    
    
'
);
