
<div style="text-align:center;text-transform:uppercase;"><b>
        DAFTAR NILAI MAHASISWA <br /><?= "$header[jr_jenjang] $header[jr_nama], Semester ".$header['kr_nama'] ?></b>
</div><br /><br />
<table width="100%">
    <tr style="text-align:center">
        <td><b><?= $header['ds_nm']?></b></td>
    </tr>
    <tr>
        <td width="50%"><b><?= $header['mtk_kode'].': '.$header['mtk_nama']."($header[jdwl_kls])"?>
                [<?= "$header[hari] $header[jdwl_masuk]-$header[jdwl_keluar]"?>]</b>
        </td>
    </tr>
</table>
<table class="table table-bordered" style="font-size:10px;">
    <thead>
    <tr>
        <th colspan="6" style="text-align:center"> Persentase Nilai </th>
        <th colspan="5" style="text-align:center"> Grade Nilai </th>
    </tr>
    <tr>
        <th style="text-align:center">Tugas1</th>
        <th style="text-align:center">Tugas2</th>
        <th style="text-align:center">Tugas3</th>
        <th style="text-align:center">Quiz</th>
        <th style="text-align:center">UTS</th>
        <th style="text-align:center">UAS</th>

        <th style="text-align:center">A</th>
        <th style="text-align:center">B</th>
        <th style="text-align:center">C</th>
        <th style="text-align:center">D</th>
        <th style="text-align:center">E</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="text-align:center"><?= $header['nb_tgs1'] ?>% </td>
        <td style="text-align:center"><?= $header['nb_tgs2'] ?>% </td>
        <td style="text-align:center"><?= $header['nb_tgs3'] ?>% </td>
        <td style="text-align:center"><?= $header['nb_quis'] ?>% </td>
        <td style="text-align:center"><?= $header['nb_uts'] ?>% </td>
        <td style="text-align:center"><?= $header['nb_uas'] ?>% </td>

        <td style="text-align:center"><?= "100.00 - ".number_format(($header['B'] + 0.01),2) ?> </td>
        <td style="text-align:center"><?= number_format($header['B'],2)." - ".number_format(($header['C']+ 0.01),2) ?> </td>
        <td style="text-align:center"><?= number_format($header['C'],2)." - ".number_format(($header['D']+ 0.01),2) ?> </td>
        <td style="text-align:center"><?= number_format($header['D'],2)." - ".number_format(($header['E']+ 0.01),2) ?> </td>
        <td><?= number_format($header['E'],2)." - 00.00" ?>% </td>
    </tr>

    </tbody>
</table>


<table class="table table-bordered">
    <thead>
    <tr>
        <th>No</th>
        <th>NPM</th>
        <th>Nama</th>
        <th>Absen</th>
        <th>Tgs1</th>
        <th>Tgs2</th>
        <th>Tgs3</th>
        <th>Quiz</th>
        <th>UTS</th>
        <th>UAS</th>
        <th>TOTAL</th>
        <th>GRADE</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $n=0;
    foreach($sql as $d):
        $n++;
        ?>
        <tr>
            <td><?= $n ?></td>
            <td><?= $d['mhs_nim'] ?></td>
            <td><?= $d['Nama'] ?></td>
            <td>
                <?php
                #$abs = round(\app\models\Funct::absen3($d['jdwl_id'],$d['krs_id'])['persen']);
                #if(!$abs){$abs=0;}
                $abs =Yii::$app->db->createCommand(" SELECT dbo.persensiMhs($d[krs_id]) absen ")->queryOne();
                if($abs){echo number_format($abs['absen'],0)."%";}
                ?>
                <?= '' ?></td>
            <td><?= $d['krs_tgs1'] ?></td>
            <td><?= $d['krs_tgs2'] ?></td>
            <td><?= $d['krs_tgs3'] ?></td>
            <td><?= $d['krs_quis'] ?></td>
            <td><?= $d['krs_uts'] ?></td>
            <td><?= $d['krs_uas'] ?></td>
            <td><?= number_format($d['total'],2) ?></td>
            <td><?= $d['krs_grade'] ?></td>
        </tr>
        <?php
    endforeach;

    ?>
    </tbody>
</table>

<div style="width: 90%;text-align: right; position: absolute">
    <br>
    <br>
    TTD Dosen Pengampu
</div>

<div style="width: 90%;text-align: right; position: absolute">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?= $header['ds_nm']?>
</div>
