<?php
use Yii;
use yii\helpers\Html;

$this->title = 'Input KRS';
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

#echo "KR : ".$mKr->kr_kode;
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title" ><b> INPUT KRS <?= ($mKr->kr->kr_nama ? " Semester ".$mKr->kr->kr_nama:"")?></b> </span>
    </div>

    <div class="panel-body">

        <?php
        if(!$mKr->kr_kode){?>
            <div class="alert alert-info text-center">
                <span style="font-size: 18px;font-weight: bold;color: #000">Jadwal Perwalian tidak ada</span>
            </div>
        <?php
        }else{if($mReg->id){

            echo Html::a('<i class="fa fa-th-list"></i> Input Jadwal',['/t-krs-head'],['class'=>'btn btn-primary']);

            ?>
            <p></p>
                <table class="table table-bordered">
                <thead>
                <tr>
                    <th> No </th>
                    <th> Dosen Wali </th>
                    <th> &sum;Mk. </th>
                    <th> &sum;SKS </th>
                    <th> Status </th>
                    <th> </th>
                </tr>
                </thead>
            </table>
            <?php
            }else{
            echo'
            <div class="alert alert-info text-center">
                <span style="font-size: 18px;font-weight: bold;color: #000">
                    Data anda tidak ditemukan dalam daftar registrasi perwalian<br>
                    Silahkan hubungi bagian keuangan untuk melakukan registrasi perwalian
                </span>
            </div>';
            }

            ?>

        <?php
        }
        ?>

    </div>
</div>


