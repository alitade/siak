<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

use app\models\Funct;
use app\models\Jurusan;
use app\models\Mahasiswa;

use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

use miloschuman\highcharts\Highcharts;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = 'Grafik Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-primary">
	<div class="panel-body">	
		<div class="col-sm-12">
        <?php
		$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]);
		?>
        <?php
            echo Form::widget([
                'formName' =>'thn',
                'form' => $form,
                'columns' =>3,
                'attributes' => [
					'_1'=>[
                        'label'=>'Tahun Awal',
                        'type'=>Form::INPUT_TEXT,
                    ],
                    '_2'=>[
                        'label'=>'Tahun Akhir',
                        'type'=>Form::INPUT_TEXT,
                    ],
                    't'=>[
                        'label'=>'Tipe',
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' =>[
                                    1=>'Kurikulum (Gabung)','Kurikulum (Terpisah)'

                            ],
                            'options' => ['fullSpan'=>6,'placeholder' => 'Tampilan'],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_STATIC,
                        'staticValue'=> Html::submitButton('OK',['class' =>'btn btn-primary','style'=>'text-align:right']
                        )
                    ],
                ]
            ]);
		
		?>
		<?php
		ActiveForm::end();
		?>
        
        
		</div>

	<!--div class="col-sm-3">
    
    </div-->
	<div class="col-sm-12">
	<?php
    echo Highcharts::widget([
        
        'options' => [
    
            'tooltip' => [
               'formatter' => new JsExpression(
               "
                function () {
                    return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
                    }		   
               "
               
               //'function(){ return this.series.name; }'
               
               )
            ],
            'chart'=>[
                'type'=>'column',
                'height'=>800,
            ],
            'title' => ['text' =>'Pendaftaran mahasiswa '.$TITLE],
            'xAxis' => [
                 'categories' =>$cat,
            ],
            'yAxis' => [
                'title' => ['text' => 'Jumlah Mahasiswa']
            ],
            'series' =>$series
            ,'plotOptions'=>[
                'column'=>[
                    'stacking'=>'normal'
                ]
            ]
        ]
    ]);
        
    ?>    
    </div>

	</div>
</div>


