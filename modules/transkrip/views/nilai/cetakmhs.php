<?php
use yii\helpers\Html;
use yii\helpers\Url;

$no1=0;$Smster=1;$Kod=[];$TotSks=0;$SumSks=0;$Row1="";$Row="";$no=0;

$Row2="";
$pembagi=0;
if($c>0){
    $c+=3;
    if($ModMhs->jr->jr_jenjang=='S1'){
        $c+=8;
    }else if($ModMhs->jr->jr_jenjang=='D3'){
        $c+=6;
    }
    $pembagi=$c/2;
    if($c%2!=0){$pembagi=($c+1)/2;}
}

$Sks=0;
$Nil=0;
// var_dump($ModNil);
foreach($ModNil as $data){
    $Sum = $data['sks']*(int)$data['nilai'];
    $SumSks+=$Sum;

    $TotSks+=$data['sks'];

    $Kod[$data['kode_mk'].$data['huruf']]='';
    $no1++;
    $no++;

    $a=abs(filter_var($data['kode_mk'], FILTER_SANITIZE_NUMBER_INT));
    $SmsterSub=substr($a,0,1);

    if($Smster!=$SmsterSub){
        $Smster=$SmsterSub;
        if($no<=$pembagi){$Row.='
			<tr>
				<td valign="top" colspan="3"><b>&nbsp;Jumlah&nbsp;SKS&nbsp;=&nbsp;'.$Sks.'</b></td>
				<td valign="top" colspan="2"><b><center>IP&nbsp;=&nbsp;'.number_format(($SumNil/$Sks),2).'</center></b></td>
			</tr>		
			<tr>
				<th colspan="5" align="center">&nbsp;SEMESTER&nbsp;'.$Smster.'</th>
			</tr>';
        }else{
            $Row2.='
			<tr>
				<td valign="top" colspan="3"><b>&nbsp;Jumlah&nbsp;SKS&nbsp;=&nbsp;'.$Sks.'</b></td>
				<td valign="top" colspan="2"><b><center>IP&nbsp;=&nbsp;'.number_format(($SumNil/$Sks),2).'</center></b></td>
			</tr>		
			<tr>
				<th colspan="5" align="center">&nbsp;SEMESTER&nbsp;'.$Smster.'</th>
			</tr>';
        }
        $no++;
        $Sks=0;
        $SumNil=0;
    }
    $Nil = $data['sks']*(int)$data['nilai'];
    $SumNil+=$Sum;
    $Sks+=$data['sks'];

    if($no<=$pembagi){
        $Row.="
		<tr align='left' valign='top'> 
			<td valign='top'><center>$no1</center></td>
			<td valign='top'> $data[kode_mk]".($data['kat']==1?'*':"")." </td>
			<td valign='top'> ".Html::decode($data[nama_mk])."</td>
			<td valign='top'><center>$data[sks]</center></td>
			<td valign='top'><center>$data[huruf]</center></td>
		</tr>";
    }else{
        $Row2.="
		<tr align='left'> 
			<td valign='top'><center>$no1</center></td>
			<td valign='top'> $data[kode_mk]".($data['kat']==1?'*':"")." </td>
			<td valign='top'> ".Html::decode($data[nama_mk])."</td>
			<td valign='top'><center>$data[sks]</center></td>
			<td valign='top'><center>$data[huruf]</center></td>
		</tr>
		";
    }
}
$DataSks='
<tr>
	<td valign="top" colspan="3"><b> &nbsp;Jumlah SKS = '.$Sks.'</b></td>
	<td valign="top" colspan="2"><b><center>IP = '.number_format(($SumNil/$Sks),2).'</center></b></td>
</tr>
';
$Row1.=$DataSks;
$Row2.=$DataSks;
?>
<table width="100%">
    <tr>
        <td><center><div style="border-bottom:solid 1pt #000;font-weight:bold">DAFTAR NILAI SEMENTARA</div></center></td>
    </tr>
</table>
<table width="100%" style="font-size:11px;" cellspacing="0">
    <tr>
        <td width="49%" valign="top" >
            <table>
                <tr>
                    <td width="1px">Nama</td>
                    <td>:</td>
                    <td><?= $MHS['Nama'];?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?= $MHS['alamat'];?></td>
                </tr>
            </table>
        </td>
        <td>&nbsp;</td>
        <td width="49%" valign="top" align="right">
            <table style="text-align:left">
                <tr>
                    <td width="1px">NPM</td>
                    <td width="1px">:</td>
                    <td><?= $ModMhs->mhs_nim;?></td>
                </tr>
                <tr>
                    <td>Program&nbsp;Studi</td>
                    <td>:</td>
                    <td><?= \app\models\Funct::JURUSAN()[$ModMhs->jr_id];?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" cellspacing="0" >
    <tr>
        <td width="49%" valign="top">
            <table width="100%" border='1' class="data"  cellspacing="0">
                <tr>
                    <th>NO</th>
                    <th>Kode</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Nilai</th>
                </tr>
                <tr>
                    <th colspan="5" align="center"> SEMESTER 1</th>
                </tr>
                <tr>
                    <?= $Row ?>
                </tr>
            </table>
        </td>
        <td>&nbsp;</td>
        <td width="49%" valign="top">
            <table  width="100%" border='1' class="data" cellspacing="0">
                <tr>
                    <th>NO</th>
                    <th>Kode</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Nilai</th>
                </tr>
                <tr>
                    <?= $Row2 ?>
                </tr>
            </table>
            <table border="0" style="border:solid 1px #000;border-collapse:collapse;margin-top:5px" width="100%" class="data" >
                <tr>
                    <th colspan="3" style="border-bottom:solid 1px #000;">Rangkuman&nbsp;Nilai</th>
                </tr>
                <tr>
                    <th width="1px">Total&nbsp;SKS</th>
                    <th width="1px">:</th>
                    <th><?= $TotSks ?> SKS</th>
                </tr>
                <tr>
                    <th>Total&nbsp;Matakuliah</th>
                    <th>:</th>
                    <th><?= count($ModNil) ?></th>
                </tr>
                <tr>
                    <th>IPK</th>
                    <th>:</th>
                    <th><?= number_format(($SumSks/$TotSks),2)?></th>
                </tr>
            </table>

        </td>
    </tr>
</table>
