<style>
	:target {
		color: #00C !important;
		background:#000 !important;
		font-weight:bold;
	}

	/*
	tr[id^=tr] {
    color: red;
    
	}*/
</style>
<?php

use yii\helpers\Html;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;

$this->title = 'Konversi Nilai';
$this->params['breadcrumbs'][] = $this->title;

$no1=0;
$Smster=1;
$Kod=[];
$TotSks=0;
$SumSks=0;
$Row1="";

// var_dump($ModNil);
foreach($ModNil as $data){
	$Sum = $data['sks']*(int)$data['nilai'];
	$SumSks+=$Sum;
	
	$TotSks+=$data['sks'];
	$Kod[$data['kode_mk'].$data['huruf']]='';
	$no1++;
	$a=filter_var($data['kode_mk'], FILTER_SANITIZE_NUMBER_INT);
	$SmsterSub=substr($a,0,1);
	if($Smster!=$SmsterSub){
		$Smster=$SmsterSub;
		$Row1.='<tr><th colspan="9">Semester '.$Smster.'</th></tr>';
	}
	
	
	$Row1.="
	<tr class='".($no1%2==0?"active":"info")."' id='tr_$data[id]' >
		<td>$no1</td>
		<th> $data[kode_mk]".($data['kat']==1?'*':"")." </th>
		<td> $data[nama_mk] </td>
		<td> $data[sks] </td>
		<td> $data[huruf] </td>
		<td> $data[nilai] </td>
		<td> $Sum </td>
		<td> </td>
		<td> ".
		Html::a('',['konversi', 'id'=>$data['npm'],'idn'=>$data['id'], ], [
            'class' => 'glyphicon glyphicon-pencil',
        ])." ".
			Html::a('',['delete', 'id'=>$data['id']], [
            'class' => 'glyphicon glyphicon-remove',
            'data' => [
                'confirm' => "Are you sure you want to delete profile?",
                'method' => 'post',
            ],
        ])
." </td>
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
                <th> </th>
                <th> </th>
            </tr>
            <!--
            <tr>

            	<td colspan="6">
                <?= 
					Html::a('Cetak Transkip Mahasiswa',['cetak', 'id'=>$ModMhs->mhs_nim], ['class' => 'btn btn-primary',])." ".
                    Html::a('Cetak Transkip Demo',['cetak', 'id' => $ModMhs->mhs_nim], ['class' => 'btn btn-primary',])				
					." ".Html::a('Cetak Transkip Akhir',['cetak', 'id'=>$ModMhs->mhs_nim], ['class' => 'btn btn-primary',])				
				?>
                </td>
            </tr>
            -->
        </table>
	<?php if($ModMhs): ?>
    <div class="panel-heading">Konversi Nilai</div>
    <div class="panel-body">
    <?php 
	$db2=Yii::$app->db2;
	$Trnaskrip=\app\models\Funct::getDsnAttribute('dbname', $db2->dsn);

	$FieldMk=[
		'label'=>false,
		'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
		'options'=>[
			'data' => app\models\Funct::MTK(1,"
				jr_id='$ModMhs->jr_id' and mtk_kode not in(select kode_mk from $Trnaskrip.dbo.t_nilai where npm='$ModMhs->mhs_nim' and (stat='0' or stat is null))
			"),
			'options' => [
				'fullSpan'=>6,
				'placeholder' => 'Matakuliah',
			],
			'pluginOptions' => [
				'allowClear' => true
			],
		]	
	];
	if(!$add){
		unset($FieldMk);
		$FieldMk=[
			'label'=>false,
			'type'=>Form::INPUT_STATIC,
			'staticValue'=>	($model->kat==1?
			"<font color='red'><b>*"
			.\app\models\Funct::MTK(1,['jr_id'=>$ModMhs->jr_id])[$model->kode_mk]."</b></font>" :
			\app\models\Funct::MTK(1,['jr_id'=>$ModMhs->jr_id])[$model->kode_mk]),
		];
		
	}
	
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 3,
    'attributes' => [
		'kode_mk'=>$FieldMk, 
		'huruf'=>[
			'label'=>false,
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => ['A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E',],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Nilai',
                ],
				'pluginOptions' => ['allowClear' => true],
            ],
		], 
		[
			'label'=>false,
            'type'=>Form::INPUT_RAW,
			'value'=>Html::submitButton('Simpan', ['class' => 'btn btn-primary'])
		]
    ]
    ]);
	ActiveForm::end();
?>
    </div>
    <div class="col-sm-12">
    <table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th> Kode</th>
        <th> Matakuliah</th>
        <th> SKS </th>
        <th> Grade </th>
        <th> Nilai </th>
        <th> SKS x Nilai </th>
        <th> </th>
        <th width="5%"> </th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <th colspan="9">Semester 1</th>
    </tr>
    <?= $Row1 ?>
    </tbody>
	<tfoot>
    <tr>
        <th colspan="3"><span style="float:right">Total :</span></th>
        <th><?= $TotSks ?> SKS</th>
        <th></th>
        <th></th>
        <th><?= $SumSks ?></th>
        <th>IPK =<?= number_format(($SumSks/$TotSks),2)?></th>
        <th></th>
    </tr>
    
    </tfoot>
    </table>
    </div>
 	<?php endif; ?>
 	</div>
</div>
