<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenPengganti $model
 */
$this->title = 'Dosen Pengganti';
?>
<div class="dosen-pengganti-create">
    
    <?= $this->render('_form', [
    	'tanggals' => $tanggals,
    	'jadwal' => $jadwal,
        'model' => $model,
        'pengganti' => $pengganti,
    ]) ?>
</div>