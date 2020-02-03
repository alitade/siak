<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\tabs\TabsX;


?>
<?php if($Detail):?>
    <table class="table">
        <thead>
        <tr>
            <th> Sesi</th>
            <th> Normal </th>
            <th> Pelaksanaan </th>
            <th> Pergantian </th>
            <th> Ket </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($Detail as $data):?>
        <tr <?= !isset($SESI[$data['sesi']])&&$data['sesi']<=max($SESI)?"class='danger'":""?>>
            <td><?= "".$data['sesi']//$data->bn->mtk->mtk_sks ?></td>
            <td><?= Funct::TANGGAL($data[tgl]).'<br />'.$data['masuk'].'-'.$data['keluar'] ?></td>
            <td><?= Funct::TANGGAL($data[pelaksanaan]).'<br />'.$data['pelaksanaan_masuk'].'-'.$data['pelaksanaan_keluar'] ?></td>
            <td><?= Funct::TANGGAL($data[pergantian]).'<br />'.$data['pergantian_masuk'].'-'.$data['pergantian_keluar'] ?></td>
            <td><?php
                $ket='<i class="glyphicon glyphicon-remove-circle" style="color:red;"></i>';


                $btn='';
                if(Funct::StatAbsDsn($data['jdwl_id'],$data['sesi'])){$ket='<i class="glyphicon glyphicon-ok-circle" style="color:green;"></i>';}

                if($data['pergantian']){$btn=Html::a('Hapus',['dirit/pergantian-del',"id"=>$model->jdwl_id,'s'=>$data['sesi'],'d'=>1],['class'=>'btn btn-success']);}
                if(!isset($SESI[$data['sesi']])&&$data['sesi']<=max($SESI)){
                    $ket="";
                    if(Funct::acc('/jadwal/exp-abs')){
                        $btn=Html::a('<i class="fa fa-upload"></i> ',['/jadwal/view',"id"=>$model->jdwl_id,'s'=>$data['sesi']],['class'=>'btn btn-success']);
                    }

                }
                echo $ket;

            ?></td>
            <td><?= $btn?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif; ?>
