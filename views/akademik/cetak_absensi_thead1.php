<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
 
?>


<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%">
<thead> 
 <tr>
   <th height="35" style="align-content: center; text-align: center; font-size: 20pt;"
    colspan="12" align="center" valign="middle" scope="row">DAFTAR HADIR <?=$jenis?></th>  
  </tr>
 	<tr>
 	<td width="300" colspan="2" style="font-size:14pt; padding-left: 9px;"><b>Mata Kuliah</b></td><td  colspan="5" style="font-size:14pt; padding-left: 9px;"><?=$header['matakuliah']?></td>
 	<td colspan="2" style="font-size:14pt;  padding-left: 9px;"><b>Ruangan</b></td><td colspan="3" style="font-size:14pt; padding-left: 9px;"><?=$header['ruang']?></td></tr>
 	<tr><td width="187" colspan="2" style="font-size:14pt;  padding-left: 9px;"><b>Nama Dosen</b></td><td  colspan="5" style="font-size:15pt; padding-left: 9px;"><?=$header['dosen']?></td>
 	<td colspan="2" style=" padding-left: 9px;font-size:14pt; "><b>Program</b></td><td colspan="3" style="font-size:14pt; padding-left: 9px;"><?=$header['kode'].":".$header['program']." (".$header['kelas'].") "?></td></tr>
 	<tr><td width="187" colspan="2" style=" padding-left: 9px;font-size:14pt; "><b>Hari, Tanggal / Jam</b></td><td  colspan="5" style="font-size:15pt; padding-left: 9px;"><?=trim($waktu);?></td>
 	<td colspan="2" style=" padding-left: 9px;font-size:14pt; "><b>Semester</b></td><td colspan="3" style="font-size:14pt; padding-left: 9px;"><?=$header['semester']?></td></tr>
  <tr>
    <th width="35" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col">NO</th>
    <th width="150" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">NPM</th>
    <th width="250" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col">NAMA MAHASISWA</th>
    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">JML HDR</th>
    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">%</th>
    <th width="60" style="align-content: center; text-align: center;" align="center" valign="middle" nowrap="nowrap" scope="col">Nilai Huruf</th>
    <th width="60" style="align-content: center; text-align: center;" align="center" valign="middle" nowrap="nowrap" scope="col">Total</th>
    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">TGS1</th>
    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">TGS2</th>
    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">UTS</th>
    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">UAS</th>
    <th width="100"  style="align-content: center; text-align: center;width:150px" align="center" valign="middle" nowrap="nowrap" scope="col">TANDA TANGAN</th>
  </tr>
</thead>