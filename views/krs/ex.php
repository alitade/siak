<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\grid\GridView;
use app\models\Funct;
?>
    <style>
        :target {
            color: #00C !important;
            background:#000 !important;
            font-weight:bold;
        }

        /*
        tr[id^=tr] {
        color: red;

        }*/
    </style>

<?php
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'method'=>'get','action'=>['ex-perwalian']]);
echo Form::widget([
        'model' => $kr,
        'form' => $form,
        'columns' =>1,
        'attributes' => [
            [
                'label'=>'Kode Kurikulum',
                'columns'=>2,
                'attributes'=>[
                    'kr_kode'=>[
                        'label'=>false,
                        'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Masukkan Kode Kurikulum']
                    ],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_RAW,
                        'value'=> Html::submitButton('<i class="fa fa-search"></i> Cari Data',['class' => 'btn btn-primary','style'=>'text-align:right']
                        )
                    ],

                ]

            ],
        ]
    ]);
ActiveForm::end();
?>

<div class="box box-info">
    <div class="box-body" style="font-weight: bold">
        Halaman ini berfungsi untuk memindahkan hasil data perwalian ke data perkuliahan.<br>
        Data perwalian yang bisa dipindahkan hanya data yang sudah disetujui oleh dosen wali.
        Data yang telah dipindahkan tidak bisa dikembalikan.<br>
        Jika proses pemindahan data telah dilakukan, dosen wali tidak bisa membatalkan/merubah hasil perwalian mahasiswa
    </div>

</div>

<?php if($dataProvider){ ?>
<div class="box box-primary">
    <div class="box-body">
        <?php
        if(Funct::acc('/krs/ex-procs')){ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'action'=>['ex-procs']]);}


        ?>
        <div class="pull-right">
        <?php if(Funct::acc('/krs/ex-procs')) { ?>
        <?= Html::submitButton('Approve / Pindahkan Data Terpilih', ['class' => 'btn btn-success']) ?><p></p>
        <?php } ?>
        </div>
        <table class="table table-condensed table-bordered table-striped">
            <thead>
                <tr valign="center">
                    <th rowspan="2">NO</th>
                    <th rowspan="2">Jurusan</th>
                    <th rowspan="2">Program</th>
                    <th rowspan="2">Awal KRS</th>
                    <th rowspan="2">Akhir KRS</th>
                    <th rowspan="2"><i class="fa fa-users"></i></th>
                    <th colspan="4">Persetujuan</th>
                    <th colspan="4">Pemindahan Data</th>
                </tr>
                <tr>
                    <th><i class="fa fa-check" style="color:green;"></i></th>
                    <th><i class="fa fa-remove" style="color:red;"></i></th>
                    <?php if(Funct::acc('/krs/ex-procs')) {?>
                    <th><?= Html::checkbox('app_all', false, ['id' => 'app_all']) ?></th>
                    <?php }?>
                    <th><i class="fa fa-percent"></i></th>
                    <th><i class="fa fa-check" style="color:green;"></i></th>
                    <th><i class="fa fa-remove" style="color:red;"></i></th>
                    <?php if(Funct::acc('/krs/ex-procs')) {?>
                    <th><?= Html::checkbox("ex_all",false,['id'=>'ex_all']) ?></th>
                    <?php }?>
                    <th><i class="fa fa-percent"></i></th>
                </tr>
            </thead>
            <tbody>
            <?php

            $KRST=0;$KRSY=0;$KRSN=0;
            $PT=0;$PY=0;$PN=0;

            $n=0; foreach ($dataProvider->getModels() as $d){ $n++;

                $krst=$d['t']?:0; $krsy=$d['app']?:0;$krsn=($d['t']-$d['app']);$krsp=($d['app']/$d['t'] * 100 );
                $KRST+=$krst;$KRSY+=$krsy;$KRSN+=$krsn;

                $pt=$d['y']?:0;$py=($krsy-$d['y']);
                $PT+=$pt;$PY+=$py;

            ?>
                <tr id="<?= "NO".$d['kln_id']?>">
                    <td><?= $n ?> </td>
                    <td><?= $d['jr_jenjang'].' '.$d['jr_nama'] ?> </td>
                    <td><?= $d['pr_nama'] ?> </td>
                    <td><?= Funct::TANGGAL($d['kln_krs']); ?> </td>
                    <td><?= Funct::TANGGAL($d['krs_akhir']); ?> </td>
                    <td class="text-right"><?= number_format($krst) ?></td>
                    <td class="text-right"><?= number_format($krsy) ?></td>
                    <td class="text-right">
                        <?php
                        if(Funct::acc('/krs/ex-proc-app')){
                            echo ($krsn > 0? Html::a(number_format($krsn),['ex-proc-app','id'=>$d['kln_id']],['title'=>'Approve  KRS','class'=>'btn btn-danger btn-xs']):number_format($krsn));
                        }else{ echo number_format($krsn);}
                        ?>
                    </td>
                    <?php if(Funct::acc('/krs/ex-procs')) {?><td><?=($d['t']-$d['app'])<=0?"":Html::checkbox("app[]",false,['value' => $d['kln_id']])?> </td> <?php }?>
                    <td class="text-right"><?= number_format($krsp,0).'%' ?> </td>

                    <td class="text-right"><?= $pt ?> </td>
                    <td class="text-right"><?php
                    if(Funct::acc('/krs/ex-proc')){
                        echo $py <=0 ?"0": Html::a(number_format($py),['ex-proc','id'=>$d['kln_id']],['title'=>'Pindahkan Data','class'=>'btn btn-xs btn-danger']);
                    }else{echo number_format($py);}
                    ?></td>
                    <?php if(Funct::acc('/krs/ex-procs')) {?>
                    <td><?=($d['app']-$d['y'])<=0?"":Html::checkbox("ex[]",false,['value' => $d['kln_id']])?>
                    <?php }?>
                    <td class="text-right"><?= number_format( ($pt / $krsy*100) ,0).'%' ?> </td>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right"> Total </th>
                    <th class="text-right"><?= number_format($KRST)?> </th>
                    <th class="text-right"><?= number_format($KRSY)?> </th>
                    <th class="text-right"><?= number_format($KRSN)?> </th>
                    <?php if(Funct::acc('/krs/ex-procs')) {?>
                        <th><?= Html::checkbox('app_all', false, ['id' => 'app_all']) ?></th>
                    <?php }?>
                    <th class="text-right"><?= number_format(($KRSY/$KRST*100))?>%</th>
                    <th class="text-right"><?= number_format($PT)?> </th>
                    <th class="text-right"><?= number_format($PY)?> </th>
                    <?php if(Funct::acc('/krs/ex-procs')) {?>
                        <th><?= Html::checkbox("ex_all",false,['id'=>'ex_all']) ?></th>
                    <?php }?>
                    <th class="text-right"><?= number_format(($PT/$KRSY*100),0)?>%</th>
                </tr>

            </tfoot>
        </table>
        <?php if(Funct::acc('/krs/ex-procs')){?>
        <p></p>
        <div class="pull-right"><?= Html::submitButton('Approve / Pindahkan Data Terpilih', ['class' => 'btn btn-success']) ?></div>
        <?php } ?>
        <?php
        if(Funct::acc('/krs/ex-procs')){ActiveForm::end();}

        ?>
    </div>

</div>
<?php } ?>
<?php
$this->registerJs("
$('#app_all').click(function (e) {
    $(this).closest('table').find('td input[name=\"app[]\"]:checkbox').prop('checked', this.checked);
});

$('#ex_all').click(function (e) {
    $(this).closest('table').find('td input[name=\"ex[]\"]:checkbox').prop('checked', this.checked);
});


   
");