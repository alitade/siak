<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Wisuda $model
 */

$this->title = $model->npm;
$this->params['breadcrumbs'][] = ['label' => 'Transkrip','url'=>['nilai/mhs']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-primary">
    <div class="panel->body">
        <table class="table">
            <tr>
                <td> NPM / Nama </td>
                <td> : </td>
                <td> <?= $model->npm." / ".$model->nama?> </td>
            </tr>
            <tr>
                <td> Program Studi </td>
                <td> : </td>
                <td> <?= Funct::JURUSAN()[$model->jr_id]?> </td>
            </tr>
        </table>
    </div>
</div>

<div class="panel-primary">
    <div class="panel-heading" ><font size="+1"><b>Transkrip : <?= strtoupper($model->kode_)?></b></font></div>
    <div class="panel-body">
	<?= 
        Html::a('Cetak Transkip Mahasiswa',['cetak/cetak', 'id'=>$model->npm], ['class' => 'btn btn-success',])." ".
        Html::a('Cetak Transkip Akhir',['cetak/cetak', 'id' => $model->npm,'mode'=>3], ['class' => 'btn btn-success',])
    ?><p></p>
        <?php
        echo'
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> No </th>
                <th> Kode </th>
                <th> Matakuliah </th>
                <th> SKS </th>
                <th> Nilai </th>
            </tr>
        </thead>
        <tbody>
        ';
        $n=0;$Hs=0;
		$Tsks=0;$Tnil=0;
		$_Tsks=0;$_Tnil=0;
        foreach($ModNil as $data){
			if($Hs!=$data->semester){
				$Hs=$data->semester;
				if($n>0){
					echo '
					<tr>
						<th colspan="5">
						<span class="col-sm-6">Jumlah SKS = '.$Tsks.'</span><span class="col-sm-6"> IP='.number_format(($Tnil/$Tsks),2).'</span>
						</th>
					</tr>';	
					$Tsks=0;$Tnil=0;
				}
				echo '
				<tr><th colspan="5" style="background:black"></th></tr>
				<tr><th colspan="5"><center><h4>Semester '.$Hs.'</h4></center></th></tr>';
			}
			$Tsks+=$data->sks;
			$Tnil+=($data->sks*$data->nilai);

			$_Tsks+=$data->sks;
			$_Tnil+=($data->sks*$data->nilai);
			$n++;
            echo '
            <tr>
                <td> '.$n.' </td>
                <td> '.$data->kode_mk.($data->kat==1?"*":"").' </td>
                <td> '.$data->nama_mk.' </td>
                <td> '.$data->sks.' </td>
                <td> '.$data->huruf.' </td>
            </tr>
            ';
        }
        echo'
		<tr>
			<th colspan="5">
			<span class="col-sm-6">Jumlah SKS = '.$Tsks.'</span><span class="col-sm-6"> IP='.number_format(($Tnil/$Tsks),2).'</span>
			</th>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<th colspan="5">
			<span class="col-sm-6">Total SKS = '.$_Tsks.'</span><span class="col-sm-6"> IPK='.number_format(($_Tnil/$_Tsks),2).'</span>
			</th>
		</tr>
		</tfoot>
		<table>';
        ?>
    </div>
</div>
