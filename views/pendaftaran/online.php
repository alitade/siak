<?php


use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

$this->title = 'Form Pendaftaran';
$this->params['breadcrumbs'][] = ['label' => 'Pendaftaran Online Mahasiswa Baru', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


#$this->registerJsFile(Url::to('@web/sc/jquery.hotkeys.js'));
#$this->registerJsFile(Yii::getAlias('@web').'/sc/jquery-1.4.2.js');
#$this->registerJsFile(Yii::getAlias('@web').'/sc/jquery.hotkeys.js');

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
?>
<div class="panel panel-default">
    <div class="panel-heading"><span class="panel-title"> Input Data Calon Mahasiswa </span></div>
    <div class="panel-body">

    <?= $this->render('_formCalon', ['model' => $mBio,'form' => $form]) ?>
    <?= $this->render('_formWali', ['model' => $mWali,'form' => $form]) ?>
    <?= $this->render('_formDaftar', ['model' => $model,'form' => $form]) ?>
    <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        ActiveForm::end();
    ?>
    </div>
</div>
<?php

$this->registerJs(
    '
    $(document).ready(
        function () {

            $("#cadd1").change(function(){$("#chadd1").val($("#cadd1").val());});$("#crt1").change(function(){$("#chrt1").val($("#crt1").val());});$("#crw1").change(function(){$("#chrw1").val($("#crw1").val());});$("#ckd1").change(function(){$("#chkd1").val($("#ckd1").val());});$("#ckc1").change(function(){$("#chkc1").val($("#ckc1").val());});$("#ckt1").change(function(){$("#chkt1").val($("#ckt1").val());});$("#ckdp1").change(function(){$("#chkdp1").val($("#ckdp1").val());});
            $("#wadd").change(function(){$("#whadd").val($("#wadd").val());});$("#wrt").change(function(){$("#whrt").val($("#wrt").val());});$("#wrw").change(function(){$("#whrw").val($("#wrw").val());});$("#wkd").change(function(){$("#whkd").val($("#wkd").val());});$("#wkc").change(function(){$("#whkc").val($("#wkc").val());});$("#wkt").change(function(){$("#whkt").val($("#wkt").val());});$("#wkdp").change(function(){$("#whkdp").val($("#wkdp").val());});
            $("#wpr").change(function(){$("#whpr").val($("#wpr").val());});$("#wng").change(function(){$("#whng").val($("#wng").val());});
            
            $("#wadd1").change(function(){$("#whadd1").val($("#wadd1").val());});$("#wrt1").change(function(){$("#whrt1").val($("#wrt1").val());});$("#wrw1").change(function(){$("#whrw1").val($("#wrw1").val());});$("#wkd1").change(function(){$("#whkd1").val($("#wkd1").val());});$("#wkc1").change(function(){$("#whkc1").val($("#wkc1").val());});$("#wkt1").change(function(){$("#whkt1").val($("#wkt1").val());});$("#wkdp1").change(function(){$("#whkdp1").val($("#wkdp1").val());});


            $("#csm").click(function () {
                $("#cadd1").val($("#chadd1").val());$("#crt1").val($("#chrt1").val());$("#crw1").val($("#chrw1").val());$("#ckd1").val($("#chkd1").val());$("#ckc1").val($("#chkc1").val());$("#ckt1").val($("#chkt1").val());$("#ckdp1").val($("#chkdp1").val());
                if($("#csm:checked").length===1){
                    $("#cadd1").val($("#cadd").val());$("#crt1").val($("#crt").val());$("#crw1").val($("#crw").val());$("#ckd1").val($("#ckd").val());$("#ckc1").val($("#ckc").val());    
                    $("#ckt1").val($("#ckt").val());$("#ckdp1").val($("#ckdp").val());    
                }              
            });

            $("#wsmm").click(function(){
                $("#wadd").val($("#whadd").val());$("#wrt").val($("#whrt").val());$("#wrw").val($("#whrw").val());$("#wkd").val($("#whkd").val());$("#wkc").val($("#whkc").val());$("#wkt").val($("#whkt").val());$("#wkdp").val($("#whkdp").val());
                
                if($("#wsmm:checked").length===1){
                    $("#wadd").val($("#cadd").val());$("#wrt").val($("#crt").val());$("#wrw").val($("#crw").val());$("#wkd").val($("#ckd").val());$("#wkc").val($("#ckc").val());    
                    $("#wkt").val($("#ckt").val());
                    $("#wkdp").val($("#ckdp").val());
                    $("#wpr").val($("#cpr").val());
                    $("#wng").val($("#cng").val());
                }              
            });

            $("#wsm").click(function(){
                $("#wadd1").val($("#whadd1").val());$("#wrt1").val($("#whrt1").val());$("#wrw1").val($("#whrw1").val());$("#wkd1").val($("#whkd1").val());$("#wkc1").val($("#whkc1").val());$("#wkt1").val($("#whkt1").val());$("#wkdp1").val($("#whkdp1").val());
                if($("#wsm:checked").length===1){
                    $("#wadd1").val($("#wadd").val());$("#wrt1").val($("#wrt").val());
                    $("#wrw1").val($("#wrw").val());
                    $("#wkd1").val($("#wkd").val());
                    $("#wkc1").val($("#wkc").val());    
                    $("#wkt1").val($("#wkt").val());
                    $("#wkdp1").val($("#wkdp").val());    
                }              
            });

        }
    );
    
    
    
'
);

?>