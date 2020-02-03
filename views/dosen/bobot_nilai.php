<?php
use kartik\widgets\ActiveForm;
use app\models\Funct;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\builder\Form;
use yii\bootstrap\Modal;

?>

<div class="panel panel-primary">
    <div style="font-weight:bold;padding:5px;margin:5px;font-size:14px;border:solid 1px #000;"><i>
    <ul>
        <li>Penjumlahan Tugas 1 sampai UAS harus berjumlah 100</li>
        <li>Range nilai diisi dengan nilai tertinggi</li>
        <li>*Inputan tidak boleh kosong</li>
        <li> Bobot Nilai Standar:
            <ul>
                <li>Persentase: Tugas 1=10%, Tugas 2=10%, UTS=40%, UAS=40%, </li>
                <li>Range Nilai: B=80.99, C=70.99, D=59.99 , E=34.99</li>
            </ul>
        </li>
    </ul>
    </i></div>
	<div class="panel-heading"><span class="panel-title">Bobot Nilai</span></div>
    <div class="panel-body">
		<div class="col-sm-6">
        
        <h4>Persentase & Grade 
		<?php
		/*
        Modal::begin([
            'header' => 'Keterangan',
            'toggleButton' => [
				'label' => '<i class="fa fa-question"></i>',
				'options' => ['class' => 'btn-info btn-xs'],
				
			],
        ]);
        echo '
		<div style="font-weight:bold;padding:5px;margin:5px;font-size:14px;border:solid 1px #000;"><i>
		<ul>
			<li>Penjumlahan Tugas 1 sampai UAS harus berjumlah 100</li>
			<li>Range nilai diisi dengan nilai tertinggi</li>
			<li>*Inputan tidak boleh kosong</li>
			<li> Bobot Nilai Standar:
				<ul>
					<li>Persentase: Tugas 1=10%, Tugas 2=10%, UTS=40%, UAS=40%, </li>
					<li>Range Nilai: B= 80.99 , C=70.99, D=59.99 , E= <b>34.99</b> - 0</li>
				</ul>
				
			</li>
		</ul>
        </i></div>
		
		';
        Modal::end();
		*/
        ?>        
                
        </h4>
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ],'type'=>ActiveForm::TYPE_HORIZONTAL]);?>
		<?php
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributeDefaults'=>[
                'type'=>Form::INPUT_TEXT,
                //'labelOptions'=>['class'=>'col-md-3'], 
                'inputContainer'=>['class'=>'col-md-3'], 
                'container'=>['class'=>'form-group'],
            ],
            'attributes' => [
                'nb_tgs1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Tugas 1']], 
                'nb_tgs2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Tugas 2']], 
                'nb_tgs3'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Tugas 3']], 
                'nb_tambahan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Absensi']], 
                'nb_quis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Quis...']], 
                'nb_uts'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'UTS']], 
                'nb_uas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'UAS']],
            ]
        ]);
        ?>

		<?php	
		echo Form::widget([
			'model' => $model,
			'form' => $form,
			'columns' => 1,
			'attributeDefaults'=>[
				'type'=>Form::INPUT_TEXT,
				//'labelOptions'=>['class'=>'col-md-3'], 
				'inputContainer'=>['class'=>'col-md-3'], 
				'container'=>['class'=>'form-group'],
			],
			'attributes' => [
				'B'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter B...','class'=> 'col-md-3']], 
				'C'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter C...']], 
				'D'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter D...']], 
				'E'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter E...']], 
			]
		]);
	
		
		?>
		<?php
		echo 
		Html::submitButton('<i class="fa fa-save"></i> Simpan Perubahan Bobot Nilai', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])." "
		.Html::submitButton('<i class="fa fa-save"></i> Gunakan Bobot Nilai Standar', ['class'=>'btn btn-success','name'=>'DF']);        
		ActiveForm::end(); 
		$tbody="";$jam="";
		foreach($modJadwal as $d){
		$jam=Funct::HARI()[$d->jdwl_hari].", $d->jdwl_masuk-$d->jdwl_keluar";
		$tbody.="
			<tr>
				<td>".$d->bn->kln->jr->jr_jenjang.' '.$d->bn->kln->jr->jr_nama."</td>
				<td>".$d->bn->kln->pr->pr_nama."</td>
				<td>".$d->bn->mtk->mtk_kode.': '.$d->bn->mtk->mtk_nama." ($d->jdwl_kls)</td>
				<td>".$d->bn->mtk->mtk_sks."</td>
			</tr>";
		
		}


		
		?>
        
        </div>
		<div class="col-sm-6">
            <h4><?= $jam ?></h4>
            <table class="table table-bordered">
                <thead>
                        <tr>
                        <th>Jurusan</th>
                        <th>Program</th>
                        <th>Matakuliah</th>
                        <th>SKS</th>
                        <!-- th><i class="fa fa-user"></i></th -->
                    </tr>
                </thead>
                <tbody>
				<?= $tbody ?>                
                </tbody>
            </table>
        
        </div>
        
        

    </div>
    


</div>