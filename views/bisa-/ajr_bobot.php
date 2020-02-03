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

$this->title = 'Bobot Nilai';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php Pjax::begin(['id'=>'pjaxNilai']); ?>
<?= $this->render('grid_bn', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'ID' => $ID,
        ]); ?>
<?php Pjax::end(); ?>