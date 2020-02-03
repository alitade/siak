<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
 
?>


<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%">
  <tr>
    <th style="font-size: 18px" colspan="13" align="center" >DAFTAR HADIR <?=$jenis?> </th>
  </tr>
  <tr>
    <td style="padding-left: 5px;height: 35px" colspan="2"><b>Mata Kuliah<b></td>
    <td style="padding-left: 5px" colspan="5"><?=$header['matakuliah']?></td>
    <td style="padding-left: 5px" colspan="2"><b>Ruangan</b></td>
    <td style="padding-left: 5px" colspan="4"><?=$header['ruang']?></td>
  </tr>
  <tr>
    <td style="padding-left: 5px;height: 35px" colspan="2"><b>Nama Dosen<b></td>
    <td style="padding-left: 5px" colspan="5"><?=$header['dosen']?></td>
    <td style="padding-left: 5px" colspan="2"><b>Program</b></td>
    <td style="padding-left: 5px" colspan="4"><?=$header['kode'].":".$header['program']." (".$header['kelas'].") "?></td>
  </tr>
  <tr>
    <td style="padding-left: 5px;height: 35px" colspan="2"><b>Hari, Tanggal / Jam<b></td>
    <td style="padding-left: 5px" colspan="5"><?= Funct::HARI()[date('w',strtotime($header['tanggal']))]  ?>, <?=Funct::TANGGAL($header['tanggal'])?> / <?= substr($header['jam'],0,5)?></td>
    <td style="padding-left: 5px" colspan="2"><b>Semester</b></td>
    <td style="padding-left: 5px" colspan="4"><?=$header['semester']?></td>
  </tr>
  <tr>
    <td align="center"><b>NO</b></td>
    <td align="center"><b>NPM</b></td>
    <td align="center"><b>NAMA MAHASISWA</b></td>
    <td align="center"><b>JML HDR</b></td>
    <td align="center"><b>%</b></td>
    <td align="center"><b>Nilai Huruf</b></td>
    <td align="center"><b>Total</b></td>
    <td align="center"><b>TGS1</b></td>
    <td align="center"><b>TGS2</b></td>
    <td align="center"><b>UTS</b></td>
    <td align="center"><b>UAS</b></td>
    <td align="center" colspan="2"><b>TANDA TANGAN</b></td>
  </tr>