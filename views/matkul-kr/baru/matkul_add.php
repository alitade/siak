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
            <span style="font-size:14px;font-weight:bold"> Kurikulum: <?= $model->kode ?></span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">
            ----
        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <?= $this->render("mtk_form",[
            'model' => $ModMtk,
            'KR'=>$model,
        ])
        ?>
    </div>


</div>

