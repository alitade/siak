<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tarifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Tarif : <?= $model->id ?> </span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">

        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <?php
        if($qKriteria){
            foreach($qKriteria as $d){
                if($d['n']==1){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Konsultan: ".($d[item]?:"-")." </span> ";}
                if($d['n']==2){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Fakultas: ".($d[item]?:"-")." </span> ";}
                if($d['n']==3){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Jurusan: ".($d[item]?:"-")." </span> ";}
                if($d['n']==4){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Program: ".($d[item]?:"-")." </span> ";}
                if($d['n']==5){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Angkatan: ".($d[item]?:"-")." </span> ";}
                if($d['n']==6){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Kurikulum: ".($d[item]?:"-")." </span> ";}
                if($d['n']==7){echo "<span class='badge' style='background: #d9edf7;color:#000;'> Mahasiswa ".($d[item]?:"-")." </span> ";}
            }
            echo"<p></p>";
        }
        ?>
        <?php if($mDetail): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="1%"> No </th>
                <th> Tarif </th>
                <th> Urutan Pembayaran </th>
            </tr>
            </thead>
            <tbody>
            <?php $n=0; foreach ($mDetail as $d): $n++;?>
                <tr>
                    <td><?= $n?></td>
                    <td style="text-align: right">Rp.<?= number_format($d['dpp'])?></td>
                    <td><?= $d['urutan']?></td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
       <?php endif; ?>
    </div>


</div>


