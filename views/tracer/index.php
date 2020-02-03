<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use app\models\TracerJawaban;
use app\models\TracerKuisioner;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

$this->title = 'Tracer Study';
?>
<div class="panel panel-primary">
    <div class="panel-heading">Data Mahasiswa</div>
    <div class="panel-body">
    <table class='table table-hover table-condensed'>
        <tr>
            <td>NIM</td>
            <td><?=Yii::$app->user->identity->username?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td><?= Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><?= $jr->jr_id."-".$jr->jr_nama;?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td><?= $pr->pr_nama;?></td>
        </tr>
    </table>
</div>
</div>
<?php
if(isset($model)){
    echo Html::a(
            "<i class='glyphicon glyphicon-print'></i> Cetak",
            ['tracer/add'],
            ['class'=>' btn btn-primary']
    );
?>
    <br/><br/>
    <div class="panel panel-primary">
        <div class="panel-heading">Data Tracer</div>
        <div class="panel-body">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>Tanggal Input</th>
                    <td><?= $model->tanggal ?></td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td><?= $model->no_telepon ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= $model->email ?></td>
                </tr>
            </table>
            <br/>
            <table class="table table-striped">
                <tr>
                    <th width="50%">Pertanyaan</th>
                    <th width="50%">Jawaban</th>
                </tr>
            <?php
                $jawaban = TracerJawaban::getJawaban($model->id);
                if(!empty($jawaban)){
                    foreach ($jawaban as $row) {
                        echo "<tr>
                                <td>".$row['pertanyaan']."</td>
                                <td>";
                        if(isset($row['ket'])){
                            if($row['pertanyaan_kode']=='f3' || $row['pertanyaan_kode']=='f5'){
                                echo $row['ket'].' Bulan '.$row['jawaban'];
                            }else{
                                echo $row['ket'].' '.$row['jawaban'];
                            }
                        }else{
                            echo $row['jawaban'];
                        }
                        echo "</td></tr>";
                    }
                }
            ?>
            </table>
            <br/>
            <h3>Kuesioner</h3>
            <table class="table table-striped">
                <tr>
                    <th width="25%">A. Pada saat lulus, pada tingkat mana kompetensi di bawah ini anda kuasai?</th>
                    <th width="50%"><center>Pertanyaan</center></th>
                    <th width="25%">B. Pada saat lulus, bagaimana kontribusi perguruan tinggi dalam hal kompetensi di bawah ini?</th>
                </tr>
                <?php
                    $kuisioner = TracerKuisioner::getJawaban($model->id);
                    if(!empty($kuisioner)){
                        foreach ($kuisioner as $row) {
                            echo "<tr>
                                    <td><center>".$row['A']."</center></td>
                                    <td><center>".$row['pertanyaan']."<center></td>
                                    <td><center>".$row['B']."</center></td>
                                </tr>";
                        }
                    }
                ?>
            </table>
        </div>
    </div>
<?php
}else{
    echo Html::a(
            "<i class='glyphicon glyphicon-plus glyphicon-white'></i> Masukan Tracer",
            ['tracer/add'],
            ['class'=>' btn btn-success']
    );
}
?>