<?php
  
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>

<h6 align="center">DAFTAR KEHADIRAN MAHASISWA - SEMESTER <?=$header['semester']?></h6>
<table border="0" width="100%" style="font-size:10pt;text-align:left;margin:2px" cellpadding="0" cellspacing="0">
    <tr style="padding-left: 9px;">
        <td width="7%">&nbsp;Jurusan&nbsp;</td>
        <td width="1%">&nbsp;:&nbsp;</td>
        <td width="37%"><?=$header['jurusan']?></td>
        
        <td width="10%">&nbsp;</td>
        
        <td width="7%">Ruangan&nbsp;</th>
        <td width="1%">:&nbsp;</td>
        <td width="37%"><?=$header['ruang']." ( $header[kelas] ) "?></td>
    </tr>
    <tr style="padding-left: 9px;">
        <td>&nbsp;Dosen&nbsp;</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?=$header['dosen']?></td>
        <td>&nbsp;</td>
        <td> Hari/Jam</th>
        <td> : </td>
        <td><?= app\models\Funct::HARI()[$header['hari']]." / ".$header['jam']?></td>
    </tr>
    <tr style="padding-left: 9px;">
        <td>&nbsp;Kode/Matakuliah&nbsp;</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?=$header['matakuliah']?></td>
        <td>&nbsp;</td>
        <td></th>
        <td></td>
        <td> </td>
    </tr>
</table>
<?php if($header): ?>

<table width="100%" border="1" class="" cellpadding="0" cellspacing="0" style="margin-top:-20px">
<thead> 
  <tr style="font-size: 12px;">
    <th rowspan="3" width="5%" align="center" valign="middle">NO</th>
    <th rowspan="3" colspan="2" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">Nama dan NPM</th>
    <th colspan="4" style="font-size:12px;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">Tanda Tangan Tgl-Bln-Thn / Pertemuan Ke.</th>
   </tr>
   <tr style="font-size:12px;">
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 1 </th>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 2 </th>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 3 </th>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 4 </th>
  </tr>
  <tr>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"><br></th>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
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
                <?=$m['nama']?><br />
                <?=$m['mhs_nim']?>
            </td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
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
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
        </tr>
        <tr height="40px">
            <th colspan="3" style=" padding-left: 8px;padding-right: 8px;font-size:10pt;"  nowrap="nowrap" align="right" valign="middle">
            JUMLAH MAHASISWA
            </th>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>