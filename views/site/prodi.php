<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

use kartik\tabs\TabsX;

$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Info',
        'content'=>" ",
		'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Perkuliahan',
        'content'=>"",
		'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/prodi/perkuliahan'])],

    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Kehadiran',
        'content'=>"",
		'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/prodi/kehadiran'])],
    ],
];
?>
<br>
<div>
<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_LEFT,
    'encodeLabels'=>false,
	//'sideways'=>TabsX::POS_LEFT,
	
]);
?>
</div>
<div style="clear:both"></div>
