<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use kartik\select2\Select2;
use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Kelola Absensi Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;
?>    
<?php Pjax::begin(['id'=>'pjaxAttendance','enablePushState' => false]); ?>
<?= $this->render('grid_att', [
            'dataProvider' => $dataProvider,
            'columns'    => $columns
]); ?>
<?php Pjax::end(); ?>