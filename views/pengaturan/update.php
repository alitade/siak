<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\DepDrop;
use yii\helpers\Url;

$this->title = "Update ".$mod->ket;
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail ', 'url' => ['/pengaturan/view','id'=>($mod->parent?:$mod->id)]];
//$this->params['breadcrumbs'][] = $this->title;


?>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Pengaturan Sistem</span></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
        echo Form::widget([
            'model' => $model,
            'form'  => $form,
            'columns' =>1,
            'attributes' => [
                $mod->kd=>[
                    'type'=> Form::INPUT_TEXT,'options'=>['value'=>$mod->nil],
                    'hint'=>$mod->ket,
                ],
                'action'=>[
                    'type'=> Form::INPUT_RAW,
                    'value'=>
                        Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn btn-primary'])
                    ,
                ],
            ]


        ]);

        ?>
    </div>
</div>
