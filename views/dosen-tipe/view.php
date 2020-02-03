<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var app\models\DosenTipe $model
 */

$this->title = $model->tipe;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!--
<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Kategori Dosen : <?= $this->title ?></span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">

        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <h4> Beban SKS </h4>
        <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'formConfig'=>['labelSpan'=>1]
        ]);

        echo Form::widget([
                'model'=>$modSks,
                'form'=>$form,
                'columns'=>1,
                'attributes'=>[
                    'tahun'=>['type'=>Form::INPUT_TEXT],
                    'maxsks'=>['type'=>Form::INPUT_TEXT],
                ]
            ]);
        ActiveForm::end();
        ?>


    </div>


</div>
-->

<!--
<div class="dosen-tipe-view">

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'tipe',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
-->

<div class="dosen-index">
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
                '{toggleData}',
                '{export}'
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'ds_nidn',
            'ds_nm',
            'ds_email:email',
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Daftar Dosen '.$model->tipe,
        ]
    ]);
    ?>

</div>

