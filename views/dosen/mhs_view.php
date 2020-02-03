<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use  app\models\Funct;

$this->title = @$model->mhs->people->Nama;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['mhs']];
$this->params['breadcrumbs'][] = $this->title;
$agama=[1=>'Islam','Protestan','Katolik','Hindu','Budha'];

?>

<div class="mahasiswa-view">
    <?= $this->render('/mhs/_view', ['model' => $model,'dataMatkul'=>$dataMatkul]) ?>


</div>
