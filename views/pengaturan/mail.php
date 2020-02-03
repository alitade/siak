<?php
use Yii;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

#$vd = Yii::$app->vd->vid();
$this->title =' Pengaturan Email';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<p> </p>
<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="panel-title"> <i class="fa fa-envelope"> </i> Pengaturan Email </span>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <?php
                $form = ActiveForm::begin([
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'formConfig'=>['labelSpan'=>'3']
                ]);
                echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' => [
                        'em_name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '', 'maxlength'=>50,'value'=>Yii::$app->vd->vid(95)]],
                        'em_user' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => 'username email ( xx@domain.xx )',
                                'maxlength'=>50,
                                'value'=>Yii::$app->vd->vid(91)
                            ]
                        ],
                        [
                            'label'=>'Password',
                            'attributes'=>[
                                'em_pass' => [
                                    'type' => Form::INPUT_PASSWORD, 'options' => [
                                        'placeholder' => '**********', 'maxlength'=>50,
                                        'value'=>Yii::$app->vd->vid(92)
                                    ]

                                ],
                                [
                                    'type'=>Form::INPUT_RAW,
                                    'value'=>Html::checkbox('reveal-password', false, ['id' => 'reveal-password']).Html::label('Show password', 'reveal-password')
                                ],
                            ],
                        ],
                        'em_port' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '', 'maxlength'=>50,'value'=>Yii::$app->vd->vid(93)]],
                        'em_enc' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => ' ', 'maxlength'=>50,'value'=>Yii::$app->vd->vid(94)]],
                        'em_host' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => '', 'maxlength' => 50,'value'=>Yii::$app->vd->vid(90)]],
                        [
                            'type'=>Form::INPUT_RAW,
                            'value'=>Html::submitButton('<i class="fa fa-save"></i> Simpan ',['class'=>'btn btn-primary pull-right'])
                        ],
                    ]

                ]);
                ActiveForm::end();
                ?>
                <hr>
                <?php
                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>'3']]);
                echo Form::widget([
                    'formName' =>'test',
                    'form' => $form,
                    'columns' => 2,
                    'attributes' => [
                        'email' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'xx@xx.x', 'maxlength'=>50,'value'=>Yii::$app->vd->vid(91)]],
                        [
                            'type'=>Form::INPUT_RAW,
                            'value'=>Html::submitButton('<i class="fa fa-mail-reply"></i> Test Email ',['class'=>'btn btn-primary','name'=>'s'])
                        ],
                    ]

                ]);
                ActiveForm::end();
                ?>


            </div>
        </div>

    </div>
</div>
<?php
$this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#aturan-em_pass').attr('type',this.checked?'text':'password');})");

