<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Funct;


$this->title = 'Split Peserta Perwalian';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="panel-title">
            <?= $model->bn->ds->ds_nm ?>
            <div class="pull-right">
            <?= $model->bn->kln->kr_kode.' / '. $model->bn->kln->jr->jr_jenjang.' '.$model->bn->kln->jr->jr_nama.' / '.$model->bn->kln->pr->pr_nama ?>
            </div>

        </span>

    </div>
    <div class="panel-body">

        <table class="table table-bordered table-condenced">
            <tr>
                <th><?= $model->bn->ds->ds_nm ?></th>
                <th><?= $model->bn->mtk->mtk_kode.': '.$model->bn->mtk->mtk_nama." ($model->jdwl_kls) " ?></th>
                <th><?= Funct::getHari()[$model->jdwl_hari].', '.substr($model->jdwl_masuk,0,5).' - '.substr($model->jdwl_keluar,0,5) ?></th>
                <th>
                    <i class="fa fa-user"></i> : <?= $model->peserta ?> /
                    <?= Html::a('<i class="fa fa-users"></i> '. $gab['master']['tot'],['#'],['class'=>'']); ?>

                </th>
            </tr>
            <tr><th colspan="3" class="text-center"><i class="fa fa-arrow-down"></i></th></tr>
            <tr>
                <th><?= $modelPilih->bn->ds->ds_nm ?></th>
                <th><?= $modelPilih->bn->mtk->mtk_kode.': '.$modelPilih->bn->mtk->mtk_nama." ($modelPilih->jdwl_kls) " ?></th>
                <th><?= Funct::getHari()[$modelPilih->jdwl_hari].', '.substr($modelPilih->jdwl_masuk,0,5).' - '.substr($modelPilih->jdwl_keluar,0,5) ?></th>
                <th>
                    <i class="fa fa-user"></i> : <?= $modelPilih->peserta?:0 ?> /
                    <?= Html::a('<i class="fa fa-users"></i> '. $gab['sub']['tot'],['#'],['class'=>'']); ?>
                </th>
            </tr>

        </table>
        <hr>
        <?php

        $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_VERTICAL,
        'id'=>'create-krs',
        'action' => Url::to(['krs/splitm-proc','id'=>$model->jdwl_id,'split'=>$modelPilih->jdwl_id]),
        'method'=>'post'
        ]);
        ?>
        <?=
        GridView::widget([
            'dataProvider' => $data,
            'rowOptions'=>function($data) use($JD){
                $dis = false;
                $jdwl=explode("|",$data['subJadwal']);$jd="";
                $sisa = $data['kapasitas']-$data['mhs'];
                if($data['ig']==0){
                    foreach($jdwl as $k=>$v){
                        $Info=explode('#',$v);
                        $ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";
                        if(isset($JD['JD'][$Info[1]])){
                            foreach($JD['JD'][$Info[1]] as $d){
                                $M = date('H:i',strtotime($d['m']));$K=date('H:i',strtotime($d['k']));
                                $m = date('H:i',strtotime($Info[2]));
                                $k = date('H:i',strtotime($Info[3]));
                                #Perbandingan KRS Dengan Jadwal
                                if($m >= $M && $m <$K){$dis=true;}if($k > $M && $k <$K){$dis=true;}
                                #Perbandingan KRS Dengan Jadwal
                                if($M >= $m && $M <$k){$dis=true;}if($K > $m && $K <$k){$dis=true;}
                            }
                        }
                    }
                    if($data['kapasitas']>0 && $sisa<=0){$dis=true;}

                }

                if(isset($JD['MK'][$data['mtk_kode']])){$dis=true;}
                if(isset($JD['ID'][$data['jdwl_id']])){
                    if($JD['ID'][$data['jdwl_id']]==2){return ['class' => 'success','style'=>'font-weight:bold;text-decoration: line-through;'];}
                    return ['class' => 'success','style'=>'font-weight:bold'];
                }else{if($dis){return ['class' => 'danger','style'=>'font-weight:bold'];}}

            },
            'id'=>'krs-grid',
            #'filterModel' => '',
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'checkboxOptions' => function ($data, $key, $index, $column) {
                        return ['value' => $data['mhs_nim']];
                    }
                ],
                [
                    'header'  => 'NPM',
                    'value' => function($data) {
                        return $data[mhs_nim];

                    },
                    #'visible'=>$kuota==0?false:true,
                    'format'  => 'raw',
                ],
                [
                    'header'  => 'NPM',
                    'value' => function($data) {
                        return $data[Nama];

                    },
                    #'visible'=>$kuota==0?false:true,
                    'format'  => 'raw',
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'layout' =>false,
            'panel'=>[
                'type'=>GridView::TYPE_DEFAULT,
                'heading'=>'Peserta'
                #.Html::a("<i class='fa fa-question-circle' style='font-size: 20px'></i>",["#"],['class'=>'','id'=>'popupModal'])

                ,
                'before'=>' ' ,
                'footer'=>false,
                'after'=>Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Simpan', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave']),
            ],
            'toolbar'=>false
        ]);
        ?>
        <?php ActiveForm::end();?>

    </div>

</div>
