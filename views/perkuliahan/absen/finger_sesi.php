<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFinger */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Fingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-finger-view">
    <h4 class="header"><?= $mJdwl->jdwl->bn->ds->ds_nm." ".app\models\Funct::HARI()[$mJdwl->jdwl_hari].','.substr($mJdwl->jdwl_masuk,0,5)."-".substr($mJdwl->jdwl_keluar,0,5)." (sesi $Sesi)" ?></h4>
    <div class="col-sm-12">
        <div class="col-sm-6">
            <?php
            $qDosen="select ds.ds_nm,u.Fid from tbl_dosen ds inner join user_ u on(u.username=ds.ds_user and u.tipe=3 and u.fid is not null) where isnull(ds.RStat,0)=0";
            $qDosen=Yii::$app->db->createCommand($qDosen)->queryAll();
            $ListDosen=[];
            foreach($qDosen as $d){$ListDosen[$d['Fid']]=$d['ds_nm'];}
            $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
            echo Form::widget([
                'form' => $form,
                'formName' => 'tf',
                'columns' => 1,
                'attributes' => [
                    'ds'=>[
                        'label'=>'Pengajar',
                        'type'=>Form::INPUT_WIDGET,
                        'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' => $ListDosen,
                            'options' => [
                                'fullSpan'=>6,
                                'placeholder' => '... ',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                    ],
                    'st'=>[
                        'label'=>'Hadir','type'=>Form::INPUT_RADIO_LIST,'items'=>['T','Y'],
                        'value'=>'1'
                    ],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_RAW,
                        'value'=>Html::submitButton('Update Kehadiran Dosen', ['class' => 'btn btn-primary','name'=>'dsn_update']),
                    ]
                ]
            ]);
            ?>

            <?php ActiveForm::end();?>
            <? #\app\models\Funct::v($mJdwl) ?>
        </div>

        <div class="col-sm-6">
            <table class="table">
                <tr><th>Pengajar</th><td><?= $mJdwl->pengajar->ds_nm?></td></tr>
                <tr><th>Waktu Kehadiran</th><td><?=
                        \app\models\Funct::TANGGAL($mJdwl['tgl_perkuliahan']).", ".substr($mJdwl[ds_masuk],0,5)."-".substr($mJdwl[ds_keluar],0,5)
                        ?></td></tr>
                <tr><td></td></tr>
                <tr><th>Status</th><td><?=($mJdwl[ds_stat]==1?'Hadir':'Tidak Hadir')?></td></tr>
            </table>
        </div>

    </div>
    <?php if($mJdwl->ds_stat==1):
        $form = ActiveForm::begin();
        ?>
        <div class="col-sm-12">
            <center><span style="font-size:18px;text-align:center"><b>Absensi Mahasiswa</b></span></center>
            <?= (
            $u==0?
                Html::a('Tampilkan Form Kehadiran',Url::to()."&u=1",['class'=>'btn btn-success']):
                Html::a('Close Update',['detail-perkuliahan','id'=>$id,'s'=>$Sesi],['class'=>'btn btn-primary'])
            )

            ?>
            <hr />
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>NPM | NAMA</th>
                    <th>JAM</th>
                    <th>KET.</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $kodeMk="";
                $n=0; foreach($mhs as $d):
                    $hadir='<i class="glyphicon glyphicon-'.($d['stat']==1?'ok':'remove').'-circle" style="color:'.($d['stat']==1?'green':'red').'"></i>';
                    $n++;
                    if($kodeMk!=$d['jdwl_id']){
                        echo"<tr><th colspan='7'>$d[mtk_kode]: $d[mtk_nama] ($d[jdwl_kls]) </th></tr>";
                        $kodeMk=$d['jdwl_id'];
                    }
                    $bg="background:".($d['stat']==1? "green":"red").";";
                    $attribute = 'data-nim="'.$d['mhs_nim'].'" data-id="'.$d['id'].'"';
                    #$bg="";

                    ?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= "<b>$d[mhs_nim] | </b> $d[Nama]"?></td>
                        <td><?= "$d[mhs_masuk]-$d[mhs_keluar]"?></td>
                        <td><?= ($u==1?Html::textinput("hadir[ket][$d[krs_id]]",(!$d['ket']?'-':$d['ket'])):(!$d['ket']?'-':$d['ket']))?></td>
                        <td>
                            <?= ""#($u==1?Html::radiolist("hadir[abs][$d[krs_id]]",[$d['stat']],[0=>'Tidak',1=>'Hadir']):$hadir)?>
                            <?='<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="N" data-k="0" '.$attribute.' style="font-weight:normal;'.($d['kode']=='0'?$bg:'').'" >N</a>'?>
                            <?='<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="S" data-k="4" '.$attribute.' style="font-weight:normal;'.($d['kode']=='4'?$bg:'').'" >S</a>'?>
                            <?='<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="I" data-k="3" '.$attribute.' style="font-weight:normal;'.($d['kode']=='3'?$bg:'').'" >I</a>'?>
                            <?='<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="D" data-k="5" '.$attribute.' style="font-weight:normal;'.($d['kode']=='5'?$bg:'').'" >D</a>'?>
                            <?='<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="E" data-k="6" '.$attribute.' style="font-weight:normal;'.($d['kode']=='6'?$bg:'').'" >E</a>'?>
                            <?= $d['mhs_masuk']!=''&&$d['mhs_keluar']!=''?'':'<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="A" data-k="1" '.$attribute.' style="font-weight:normal;'.($d['kode']=='1'?$bg:'').'" >A</a>'?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?= ($u==1?Html::submitButton('<i class="glyphicon glyphicon-search"></i> Ok', ['class' => 'btn btn-primary']):'')?>
        </div>
        <?php ActiveForm::end(); endif; ?>
</div>
<?php
$this->registerJs("$('.do_attendance').click(function () {
						var href = $(this);
						$.ajax({
						    url : 'save-absensi-sesi',
						    type: 'POST',
						    data : $(this).data(),
						    success: function(data, textStatus, jqXHR)
						    {
						    	data = jQuery.parseJSON(data);
					    		if (data.message !='') {
					    		 	alert(data.message);
					    		 	window.location.reload();
					    		}

					    		//$(href.parent()+' > a ').css({'background':data.bg2})
					    		href.parent().find('a').css({'background':'#777'});
					    		//href.parent().find('a').attr('class', data.class);
					    		href.css({'background':data.background});
					    		href.attr('class', data.class);
					    		//href.text(''+data.nilai);
						    },
						    error: function (jqXHR, textStatus, errorThrown)
						    {
						 	    alert('Error : ' + textStatus);
						 	    window.location.reload();
						    }
						});
				  });", View::POS_END, 'AttendanceFunction');

?>
