
<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;



$no1=0;$Smster=1;$Kod=[];$TotSks=0;$SumSks=0;$Row1="";$Row="";$no=0;

$Row2="";
$pembagi=0;
if($c>0){
	$c+=4;
	if($ModMhs->jr->jr_jenjang=='S1'){
		$c+=8;
	}else if($ModMhs->jr->jr_jenjang=='D3'){
		$c+=6;
	}
	$pembagi=$c/2;
	if($c%2!=0){$pembagi=($c+1)/2;}
	if($pembagi<48){
		$pembagi=48;
	}
}

$Sks=0;
$Nil=0;
$smes=[1=>'First','Second','Third','Fourth'];
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
		$Row.='
			<tr>
				<th colspan="5" align="left" style="border:solid 1px #000;">'.$smes[$Smster].' Semester</th>
			</tr>';
		$no++;
		$Sks=0;
		$SumNil=0;
		$Sks=0;
		$SumNil=0;
	}
	$Nil = $data['sks']*(int)$data['nilai'];
	$SumNil+=$Sum;	
	$Sks+=$data['sks'];
	$Row.="
	<tr align='left'> 
		<td valign='top' align='center' class='bdr' style='padding:2px'>$no1</td>
		<td valign='top' class='bdr' nowrap='nowrap' style='padding:2px'>$data[kode_mk]".($data['kat']==1?'*':"")."</td>
		<td valign='top' class='bdr' nowrap='nowrap' style='padding:2px'>".Html::decode($data[nama_mk])."</td>
		<td valign='top' align='center' class='bdr'  style='padding:2px'>$data[sks]</td>
		<td valign='top' align='center'  style='padding:2px'>$data[huruf]</td>
	</tr>";
}

?>

<table width="100%" >
    <tr><td align="center">
    	<div style="font-weight:bold;font-size:14pt;font-family:'Times New Roman'; margin-bottom:2px;">ACADEMIC TRANSCRIPT<br />TRANSKRIP AKADEMIK</div>
		<p></p>        
        <div style="width:100%;font-size:10pt"><?=(!empty($NoTrans)?"No : ".strtoupper($NoTrans):"")?></div>
    </td></tr>
</table>
<p></p>
<table width="100%" style="font-size:9pt" cellpadding="1" style="font-family:'Times New Roman'">
	<tr>
    <td width="50%" valign="top" >
    	<table nowrap='nowrap'  width="100%" >
        	<tr>
                <td width="1px">Name</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs->people->Nama;?></td>
            </tr>
        	<tr>
                <td width="1px">Nama</td>
                <td width="1px">&nbsp;&nbsp;</td>
                <td></td>
            </tr>
            <tr><td width="5px">&nbsp;</td></tr>
        	<tr>
                <td width="1px" valign="top">Concentration</td>
                <td width="1px" valign="top" nowrap='nowrap'>&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->pr->pr_en;?></td>
            </tr>
        	<tr>
                <td width="1px" valign="top">Konsentrasi</td>
                <td width="1px" valign="top">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->pr->pr_nama;?></td>
            </tr>
            <tr><td width="5px">&nbsp;</td></tr>
        	<tr>
                <td width="1px">Date&nbsp;of&nbsp;Graduation</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= app\models\Funct::TANGGAL($ModHead->tgl_lulus,3)?></td>
            </tr>	
        	<tr>
                <td width="1px">Tanggal&nbsp;Kelulusan</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= app\models\Funct::TANGGAL($ModHead->tgl_lulus,2)?></td>
            </tr>
        </table>
    </td>
    <td></td>
    <td width="49%" valign="top">
    	<table style="text-align:left" width="100%">
        	<tr>
                <td width="1px">Student&nbsp;Identification</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs_nim;?></td>
            </tr>
        	<tr>
                <td width="1px">Nomor&nbsp;Pokok&nbsp;Mahasiswa</td>
                <td width="1px">&nbsp;&nbsp;</td>
                <td></td>
            </tr>
            <tr><td width="5px">&nbsp;</td></tr>
        	<tr>
                <td width="1px">Degree</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->jr->jr_nama ?></td>
            </tr>
        	<tr>
                <td width="1px">Gelar&nbsp;Kesarjanaan</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->jr->jr_gelar ?></td>
            </tr>
            <tr><td width="5px">&nbsp;</td></tr>
        	<tr>
                <td width="1px">Date&nbsp;and&nbsp;Place&nbsp;of&nbsp;Birth</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs->people->tempat_lahir.', '.app\models\Funct::TANGGAL($ModMhs->mhs->people->tanggal_lahir,3);?></td>
            </tr>
        	<tr>
                <td width="1px">Tempat&nbsp;&amp;&nbsp;Tanggal&nbsp;Lahir</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs->people->tempat_lahir.', '.app\models\Funct::TANGGAL($ModMhs->mhs->people->tanggal_lahir,2);?></td>
            </tr>
        </table>
    </td>
    </tr>
</table>
<p></p>
<table id="data" width="100%" cellspacing="1" cellpadding="0" border="1" style="font-family:'Times New Roman';font-size:10pt">
    <tr>
        <th width="1px" align="center" class="bd">Number</th>
        <th width="1px" align="center" class="bd">Code</th>
        <th align="center" class="bd">Subject</th>
        <th width="1px" align="center" class="bd">SKS</th>
        <th width="1px" align="center" class="bd">Value</th>
    </tr>
    <tr>
        <th colspan="5" align="left"> First Semester</th>
    </tr>
    <tr><?= $Row ?></tr>	
    <tr>
    	<td colspan="5" style="border:solid 1px #000;">
        <!-- -->
        <table width="100%" cellspacing="0" cellpadding="0" style="padding-left:-1mm;padding-right:-1mm;padding-top:-1mmm;">
            <tr>
                <th width="50%"  style="border-bottom:solid 1px #000;border-right:solid 1px #000">
                	Grade Point Acccumulation / IPK :<?= number_format(($SumSks/$TotSks),2)?>
                </th>
                <th style="border-bottom:solid 1px #000">Judicium / Yudisium : <?= $ModHead->predikat?></th>
            </tr>
            <tr>
                <th colspan="2" style="border-bottom:solid 1px #000" align="center">THESIS</th>
            </tr>
            <tr>
            	<td colspan="2" style="border-bottom:solid 1px #000;padding-bottom:2mm;padding-top:2mm;padding:2px"><?= $Ta ?></td>
            </tr>
            <tr>
            	<td colspan="2" style="padding-bottom:2mm;padding-top:2mm;padding:2px">
                <?= $Ta1 ?>
                </td>
            </tr>
        </table>
        </td>
    </tr>

</table>
<p></p>
<table width="100% !important" border="0" style="margin-top:2px;font-size:12px;height:650mm;font-family:'Times New Roman'">
	<tr style="">
    <td valign="top" style="width:72mm;">
    	<table border="0">
        	<tr><td>&nbsp;<br />&nbsp;<br />&nbsp;</td></tr>
        	<tr><td align="center"><br />Director,</td></tr>
        	<tr><td height="10mm">&nbsp;</td></tr>
        	<tr><td align="left"><?= $Pejabat->nama ?></td></tr>
        </table>
    </td>
    <td style="width:32mm;height:40mm;border:solid 1px #000" align="center">
    	[4x6]
    </td>
    <td align="right"  valign="top">
    	<table border="0" class="data">
        	<?php 
			$tglCetak = date('Y-m-d');
			if($ModHead->tgl_cetak){
				$tglCetak=date('Y-m-d',strtotime($ModHead->tgl_cetak));
			}
			?>
        	<tr><td align="left">
            	<br />Bandung, <?= app\models\Funct::TANGGAL($tglCetak,2)?>
            	<br />Bandung, <?= app\models\Funct::TANGGAL($tglCetak,3)?>
            </td></tr>
        	<tr><td align="center"><br />Rector,</td></tr>
        	<tr><td height="10mm"></td>&nbsp;</tr>
        	<tr><td align="left"><?=$Rektor->nama  ?></td></tr>
        </table>
    </td>
    </tr>
</table>
