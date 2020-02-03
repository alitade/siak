<?php
  
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<h6 align="center">DAFTAR KEHADIRAN MAHASISWA - SEMESTER <?=$header['semester']?></h6>
<table border="0" width="100%" style="font-size:10pt;text-align:left;margin:2px" cellpadding="0" cellspacing="0">
    <tr style="padding-left: 9px;">
        <td width="10%">&nbsp;Jurusan</td>
        <td width="1%">&nbsp;:</td>
        <td width="19%">&nbsp;<?=$header['jurusan']?></td>
        
        <td width="10%">&nbsp;</td>        
        
        <td width="10%">&nbsp;Ruangan</td>
        <td width="1%">&nbsp;:</td>
        <td width="19%"><?=$header['ruang']." ( $header[kelas] ) "?></td>
    </tr>
    <tr style="padding-left: 9px;">
        <td>&nbsp;Dosen</td>
        <td>&nbsp;:</td>
        <td>&nbsp;<?=$header['dosen']?></td>
        <td>&nbsp;</td>
        <td>&nbsp;Hari/Jam</td>
        <td>&nbsp;:</td>
        <td><?= app\models\Funct::HARI()[$header['hari']]." / ".$header['jam']?></td>
    </tr>
    <tr style="padding-left: 9px;">
        <td>&nbsp;Kode/Matakuliah</td>
        <td>&nbsp;:</td>
        <td>&nbsp;<?=$header['matakuliah']?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr><td colspan="7">&nbsp;</td></tr>
	<tr style="padding-left:9px;">
        <td>&nbsp;Tanggal</td>
        <td>&nbsp;:</td>
        <td>&nbsp;____________</td>
        <td>&nbsp;</td>
        <td>&nbsp;Sesi Ke.</td>
        <td>&nbsp;:</td>
        <td>&nbsp;____________</td>
    </tr>

	<tr style="padding-left:9px;">
    	<td>&nbsp;Keterangan</td>
        <td>&nbsp;:</td>
        <td colspan="5"><br /><br /><br />________________________________________________________________________</td>
    </tr>
    <tr>
    	<td colspan="7" style="font-size:11px"><br />
        	<b><i>*Form ini berlaku untuk mengganti absen fingerprint jika sistem fingerprint tidak berfungsi</i></b></td>
        </tr>
</table>

<?php if($header): ?>

<table width="100%" border="1" class="" cellpadding="0" cellspacing="0" style="margin-top:-20px">
<thead> 
  <tr style="font-size: 12px;">
    <th width="5%" align="center" valign="middle">NO.</th>
    <th colspan="2" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">(NPM)Nama</th>
    <th colspan="1" style="font-size:12px;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">Tanda tangan</th>
   </tr>
</thead>
<tbody>
    <?php 
    $num = 1;
    foreach ($data as $m): ?>
        <?php
        $absen=$m['absen'];
        $persen =($m['absen']? number_format(($m['absen']/12*100))."%":"0%");
        if($jenis =="UJIAN TENGAH SEMESTER"){
            $absen ="";
            $persen="";
        }
		?>
        <tr height="40px">
            <th style=" padding-left:4px;font-size:10pt;padding-right: 4px" align="center" height="30" valign="middle" nowrap="nowrap" scope="row"><?=$num?></th>
            <td colspan="2" style=" padding-left:4px;padding-right: 4px;font-size:10pt;"  nowrap="nowrap" align="left" valign="middle">
                <b>(<?=$m['mhs_nim']?>)</b>
                <?=$m['nama']?>
            </td>
            <td nowrap="nowrap"></td>
      </tr>
    <?php 
    $num ++;
    endforeach ?>
    </tbody>
    <tfoot>
        <tr height="40px">
            <th colspan="3" style=" padding-left: 8px;padding-right: 8px;font-size:10pt;" nowrap="nowrap" align="right" valign="middle">
            PARAF DOSEN
            </th>
            <td nowrap="nowrap"></td>
        </tr>
        <tr height="40px">
            <th colspan="3" style=" padding-left: 8px;padding-right: 8px;font-size:10pt;"  nowrap="nowrap" align="right" valign="middle">
            JUMLAH MAHASISWA
            </th>
            <td nowrap="nowrap"></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>