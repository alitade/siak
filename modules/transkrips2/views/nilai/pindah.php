<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Export Nilai';
$this->params['breadcrumbs'][] = ['label' => 'Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$no1=0;
$Smster=1;
$Kod=[];
$TotSks=0;
$SumSks=0;
$Row1="";

foreach($ModNil as $data){
	$Sum = $data['sks']*(int)$data['nilai'];
	$SumSks+=$Sum;
	
	$TotSks+=$data['sks'];
	$Kod[$data['kode_mk'].$data['huruf']]='';
	$no1++;
	$SmsterSub=substr($data['kode_mk'],2,1);
	if($Smster!=$SmsterSub){
		$Smster++;
		$Row1.='<tr><th colspan="6">Semester '.$Smster.'</th></tr>';
	}
	
	
	$Row1.="
	<tr class='".($no1%2==0?"active":"info")."' >
		<td> $no1 </td>
		<td> $data[tahun] </td>
		<th> ".($data['kat']==1?'*':"")."$data[kode_mk] </th>
		<td> $data[nama_mk] </td>
		<td> $data[sks] </td>
		<td> $data[huruf] </td>
		<td> ".
		Html::a('',['delete', 'id'=>$data['id']], [
            'class' => 'glyphicon glyphicon-remove pull-right',
            'data' => [
                'confirm' => "Are you sure you want to delete profile?",
                'method' => 'post',
            ],
        ])." </td>
	</tr>";
}

?>

<div class="panel panel-primary">
    <div class="panel-body">
        <table class="table">
            <tr>
                <th> Nama </th>
                <td> : <?= $ModMhs->mhs->people->Nama;?></td>
                <td>&nbsp;  </td>
                <th> Jurusan </th>
                <td> : <?= \app\models\Funct::JURUSAN()[$ModMhs->jr_id];?></td>
            </tr>
            <tr>
                <th> NPM </th>
                <td> : <?= $ModMhs->mhs_nim;?></td>
                <td>&nbsp;  </td>
                <th> Program </th>
                <td> : <?= $ModMhs->pr->pr_nama;?></td>
            </tr>
            <tr>
                <th> Angkatan / Kurikulum</th>
                <td> : <?= $ModMhs->mhs_angkatan." / ".$ModMhs->mhs->kurikulum?></td>
                <td>&nbsp;  </td>
                <th>Total </th>
                <th> : <?= $no1." Matakuliah / ".$TotSks." SKS"?>, IPK =<?= number_format(($SumSks/$TotSks),2)?></th>
            </tr>
        </table>
		<?php if($ModMhs): ?>
		<div class="col-sm-12">
        <h2 style="text-align:center">Export Nilai</h2>
        	<div class="col-sm-6">
            <table class="table table-hover">
            <thead>
            	<tr><th colspan="6" style="text-align:center"> Data Transkrip</th></tr>
            	<tr>
                	<th>#</th>
                	<th> Tahun</th>
                	<th> Kode</th>
                	<th> Matakuliah</th>
                	<th> SKS </th>
                	<th> Grade </th>
                	<th> </th>
                </tr>
            </thead>
            <tbody>
            <tr>
            	<th colspan="6">Semester 1</th>
            </tr>
            <?= $Row1 ?>
            </tbody>
			</table>
            </div>
        	<div class="col-sm-6">
		    <?php $form = ActiveForm::begin(); ?>
            <table class="table table-border">
            <thead>
            	<tr><th colspan="6" style="text-align:center"> Data KRS Mahasiswa</th></tr>
            	<tr>
                	<th>#</th>
                	<th> Tahun </th>
                	<th> Kode </th>
                	<th> Matakuliah </th>
                	<th> SKS </th>
                	<th> Grade </th>
                </tr>
            </thead>
            <tbody>
            <tr>
            	<th colspan="6">Semester 1</th>
            </tr>
			<?php
			$no=0;
			$Smster=1;
			$Show=0;
			$a=0;
			foreach($ModKrs as $data){
				$no++;
				$SmsterSub=substr($data['mtk_kode'],2,1);
				if(!isset($Kod[$data['mtk_kode'].$data['krs_grade']])){
					//if(array_search($data['krs_grade'],['A','B','C','D','E'])){$Show++;}
					if($Smster!=$SmsterSub){
						$Smster=$SmsterSub;
						echo'<tr><th colspan="6">Semester '.$Smster.'</th></tr>';
					}
					$a++;
					echo"
					<tr class='".($a%2==0?"active":"info")."' >
						<td> $a </td>
						<td> $data[kr_kode] </td>
						<td> $data[mtk_kode] </td>
						<td> $data[mtk_nama] </td>
						<td> $data[sks_] </td>
						<td> $data[krs_grade]</td>
					</tr>";
				}
			}
			?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="6" align="right">
                <?= 
				(true?Html::submitButton('Export Nilai', ['class' => 'btn btn-primary','name'=>'ok','value'=>'1']):
				'<span class="btn btn-warning">Tidak Ada Data Yang Bisa Di Export</span>') 
				?>
                </th>
            </tr>
            </tfoot>
			</table>
        
            <?php ActiveForm::end(); ?>
            </div>
		</div>
        <?php endif; ?>
	</div>
</div>
