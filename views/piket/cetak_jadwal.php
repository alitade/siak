<?php
  
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Segoe UI;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JADWAL KULIAH</title>
<style>
@page {  margin-top: 0.3cm;margin-left: 1.5cm;margin-bottom: 1cm;margin-right: 1cm }
div.page { page-break-before: always }
</style>
</head>
<body >

<div class="page">
    <table width="100%" border="1" cellpadding="0" cellspacing="0">
      <tbody>
          <tr>
            <th width="100" style="padding: 4px;">Hari</th>
            <th width="110">Jam</th>
            <th width="80">Kode</th>
            <th>Matakuliah</th>
            <th width="300">Dosen</th>
            <th width="50">Kelas</th>
            <th width="150">Ruang</th>
          </tr>
          <?php foreach ($data as $k): ?>
              <tr>
                <td style="padding-left: 6px;"><?=Funct::HARI()[$k['jdwl_hari']]?></td>
                <td style="padding-left: 6px;"><?=$k['jam']?></td>
                <td style="padding-left: 6px;"><?=$k['mtk_kode']?></td>
                <td style="padding-left: 6px;"><?=$k['mtk_nama']?></td>
                <td style="padding-left: 6px;"><?=$k['ds_nm']?></td>
                <td style="padding-left: 6px;"><?=$k['jdwl_kls']?></td>
                <td style="padding-left: 6px;"><?=$k['rg_nama']?></td>
              </tr>
          <?php endforeach ?>
      </tbody>
    </table>
</div>

</body>

<script type="text/javascript">
    //window.print();
</script>
 
</html>
