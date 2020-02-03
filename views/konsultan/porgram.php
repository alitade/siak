<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use app\models\Funct;
use kartik\tabs\TabsX;

/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 */

$this->title = "Konsultan ".$model->vendor;
$this->params['breadcrumbs'][] = ['label' => 'Konsultan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Konsultan <?= $model->vendor." ($model->kode)" ?></span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">
            PIC: -  <?= "" ?>, Email:<?= ($model->email?:"-") ?>, Tlp:<?= ($model->tlp?:"-") ?>
        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        asd
    </div>
</div>
