<?php 

echo "nama = $dosen->ds_user";
//$model->penanggungjawab = $dosen->ds_id; 
?>

<div class="matkul-create">
    
    <?= $this->render('jdw__form', [
        'model' => $model,
    ]) ?>



</div>