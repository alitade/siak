<?php
  
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Segoe UI;" moznomarginboxes mozdisallowselectionprint>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DAFTAR HADIR</title>
<style>
@page { margin:0; margin-top: 0.3cm;margin-left: 1.2cm;margin-bottom: 1cm;margin-right: 0.5cm }
div.page { page-break-before: always }
</style>
</head>
<body >

<div class="page">
 <table width="100%"  border="0" class="" style="margin-bottom: 0px;margin-top: -20px;" cellpadding="0" cellspacing="0" >  
  <tr>
    <td width="10%"><img style="margin-bottom: -15px; margin-top: 2px; margin-left: 10px;" src="<?php echo Url::to('@web/ypkp.png'); ?>" width="40px"></td>
    <td width="100%">
      <div>
        <h4><b style ="font-size: 10pt;">UNIVERSITAS SANGGA BUANA YPKP</b></h4>
        <p style="margin-bottom: 10px;margin-top: -20px;font-size:8pt; "><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 </small></p>
        <p style="margin-bottom: 0px; margin-top: -12px;font-size:8pt; "><small>E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
      </div>
    </td>
  </tr>
</table>
<br> 
<table width="100%" border="1" class="" cellpadding="0" cellspacing="0" style="margin-top:-20px">
<thead> 
 <tr>
   <th height="20" style="align-content: center; text-align: center; font-size:10pt;text-transform:uppercase"
    colspan="17" align="center" valign="middle" scope="row">DAFTAR KEHADIRAN MAHASISWA - SEMESTER <?=$header['semester']?>  </th>  
  </tr>
 <tr>
   <td height="35" style="align-content: center; text-align: center; font-size: 10pt;"
    colspan="17" valign="middle" scope="row">
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
    </td>  
  </tr>
  <tr style="font-size: 12px;">
    <th rowspan="3" width="20" style="align-content: center; text-align: center;" align="center" valign="middle">NO</th>
    <th rowspan="3" colspan="2" width="150" style="align-content: center; text-align: center;width: 20%;" align="center" valign="middle"  nowrap="nowrap" scope="col">Nama dan NPM</th>
    <th colspan="14" style="font-size:12px;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">Tanda Tangan Tgl-Bln-Thn / Pertemuan Ke.</th>
   </tr>
   <tr style="font-size:12px;">
    <!-- sesi -->
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 1 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 2 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 3 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 4 </th>

    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 5 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 6 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 7 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 8 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 9 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 10 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 11 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 12 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 13 </th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 14 </th>

    <!-- -->
  </tr>
  <tr>
     <!-- sesi -->
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"><br></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>

    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
    <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>

  </tr>
  
</thead>
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
         if ($num % 20==0): ?>
            </table>
            </div>
            <br>
            <div class="page">
             <table width="100%"  border="0" class="" style="margin-bottom: 0px;margin-top: -20px;" cellpadding="0" cellspacing="0" >  
              <tr>
                <td width="10%"><img style="margin-bottom: -15px; margin-top: 2px;margin-left: 10px;" src="<?php echo Url::to('@web/ypkp.png'); ?>" width="40px"></td>
                <td width="100%">
                  <div>
                    <h4><b style ="font-size: 10pt;">UNIVERSITAS SANGGA BUANA YPKP</b></h4>
                    <p style="margin-bottom: 10px;margin-top: -20px;font-size:8pt; "><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 </small></p>
                    <p style="margin-bottom: 0px; margin-top: -12px;font-size:8pt; "><small>E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
                  </div>
                </td>
              </tr>
            </table>
            <br> 
            <table width="100%" border="1" class="" cellpadding="0" cellspacing="0" style="margin-top:-20px">
            <thead> 
             <tr>
               <th height="20" style="align-content: center; text-align: center; font-size:10pt;text-transform:uppercase"
                colspan="17" align="center" valign="middle" scope="row">DAFTAR KEHADIRAN MAHASISWA - SEMESTER <?=$header['semester']?>  </th>  
              </tr>
             <tr>
               <td height="35" style="align-content: center; text-align: center; font-size: 10pt;"
                colspan="17" valign="middle" scope="row">
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
                </td>  
              </tr>
           <tr style="font-size: 12px;">
            <th rowspan="3" width="20" style="align-content: center; text-align: center;" align="center" valign="middle">NO</th>
            <th rowspan="3" colspan="2" width="150" style="align-content: center; text-align: center;width: 20%;" align="center" valign="middle"  nowrap="nowrap" scope="col">Nama dan NPM</th>
            <th colspan="14" style="font-size:12px;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">Tanda Tangan Tgl-Bln-Thn / Pertemuan Ke.</th>
           </tr>
           <tr style="font-size:12px;">
            <!-- sesi -->
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 1 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 2 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 3 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 4 </th>

            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 5 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 6 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 7 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 8 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 9 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 10 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 11 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 12 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 13 </th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"> 14 </th>

          </tr>
          <tr>
             <!-- sesi -->
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"><br></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>

            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>
            <th width="20" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col"></th>

          </tr>
            </thead>
        <?php endif ?>
        <tr height="40px">
        <th style=" padding-left:4px;font-size:10pt;padding-right: 4px" align="center" height="30" valign="middle" nowrap="nowrap" scope="row"><?=$num?></th>
        <td colspan="2" style=" padding-left:4px;padding-right: 4px;font-size:10pt;width:100px"  nowrap="nowrap" align="left" valign="middle">
            <?=$m['nama']?><br />
            <?=$m['mhs_nim']?>
        </td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>

        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>
        <td nowrap="nowrap"></td>

      </tr>
    <?php 
    $num ++;
    endforeach ?>
    <tfoot>
        <tr height="40px">
            <th style=" padding-left: 9px;font-size:14pt;padding-right:8px" align="center" height="30" valign="middle" nowrap="nowrap" scope="row"> </th>
            <td colspan="2" style=" padding-left: 8px;padding-right: 8px;font-size:10pt;width:100px" nowrap="nowrap" align="left" valign="middle">
            PARAF DOSEN
            </td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>

            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>            

        </tr>
        <tr height="40px">
            <th style=" padding-left: 9px;font-size:10pt;padding-right: 8px" align="center" height="30" valign="middle" nowrap="nowrap" scope="row"> </th>
            <td colspan="2" style=" padding-left: 8px;padding-right: 8px;font-size:10pt;width:100px"  nowrap="nowrap" align="left" valign="middle">
            JUMLAH MAHASISWA
            </td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>

            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>

        </tr>
    </tfoot>
</table>
</div>

</div>
</body>

<script type="text/javascript">
window.print();
</script>
 
</html>
