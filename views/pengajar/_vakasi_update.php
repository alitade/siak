<?php

use app\models\Funct;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;


$prod		= \app\models\Produk::find();//->where(['isnull(Lock,0)'=>0]);
//$produk		= $prod->all();
$prodUas	= $prod->where(['kategori'=>3,'isnull(Lock,0)'=>0])->all();
$prodUts	= $prod->where(['kategori'=>'1','isnull(Lock,0)'=>0])->all();

$Jurusan=\app\models\Jurusan::find()->where($subAkses?["jr_id"=>$subAkses]:"")->all();
/*
echo"<pre>";
print_r($subAkses);
print_r($Jurusan);
echo"</pre>";
*/
//print_r($Jurusan);
$LP=ArrayHelper::map($produk,'kode','produk');
$LKajur=ArrayHelper::map($Jurusan,'jr_head','jr_head');

?>
    <?php
    //echo $anv;

    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
    ]);

    ?>
    <fieldset style="border-bottom:solid 1px #000000;margin-bottom:2px;">
        <!-- legend style="font-size:12px;"><b>UTS SUSULAN</b></legend -->
        <?php
        if(Funct::acc('/pengajar/vksbaa')){
            echo Form::widget([
                    'model' =>$model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' => [
                        'mengetahui1'=>['label'=>'Kabag. Keuangan' ,'type'=>Form::INPUT_TEXT,],
                        'mengetahui2'=>['label'=>'Kabag. BAAK' ,'type'=>Form::INPUT_TEXT,],
                        'kat'=>['label'=>false,'type'=>Form::INPUT_HIDDEN,'options'=>['value'=>1]],
                    ],
                ]).(
                $anv==0?
                    Form::widget([
                        'model' =>$model,
                        'form' => $form,
                        'columns' => 1,
                        'attributes' => [
                            'pph'=>['label'=>'PPH' ,'type'=>Form::INPUT_TEXT],
                        ],
                    ]):"");

        }else{
            echo Form::widget([
                    'model' =>$model,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' => [
                        'mengetahui1'=>['label'=>'Kabag. Keuangan' ,'type'=>Form::INPUT_TEXT,],
                        'mengetahui2'=>[
                            'label'=>'Ketua Jurusan' ,
                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                            'options'=>[
                                'data' =>$LKajur,
                                'options' => [
                                    'fullSpan'=>6,
                                    'placeholder' => '... ',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ],
                        ],
                    ],
                ]).(
                $anv==0?
                    Form::widget([
                        'model' =>$model,
                        'form' => $form,
                        'columns' => 1,
                        'attributes' => ['pph'=>['label'=>'PPH' ,'type'=>Form::INPUT_TEXT],],
                    ]):"")
            ;

        }

        ?>
    </fieldset>
    <?php
    $NUTS=0;$NUAS=0;$fUTS="";$fUTS_="";$fUAS="";$fUAS_="";
    $f="";
    foreach($modelTransDet as $dt){
        if($dt->kd_prod=='TGS1'||$dt->kd_prod=='UTS'||$dt->kd_prod=='TGS2'||$dt->kd_prod=='UAS'){
            $f.="<tr><td>".$dt->produk->produk."</td><td>".$dt->qty."</td><td></td></tr>";
        }else{
            $f.="<tr><td>".$dt->produk->produk."</td><td>".Html::textInput("vakasi[$dt->kd_prod]",$dt->qty)."</td><td>".Html::checkbox("vakasi_[$dt->kd_prod]",false)."</td></tr>";
        }


        /*
        if($dt->kd_prod=='TGS1'||$dt->kd_prod=='UTS'||$dt->kd_prod=='NUTS'){
            if($dt->kd_prod=='NUTS'){$NUTS+=1;}
            if($dt->kd_prod=='TGS1'||$dt->kd_prod=='UTS'){
                $fUTS.="<tr><td>".$dt->produk->produk."</td><td>".$dt->qty."</td><td> </td></tr>";
            }else{$fUTS.="<tr><td>".$dt->produk->produk."</td><td>".Html::textInput("vakasi[$dt->kd_prod]",$dt->qty)."</td><td>".Html::checkbox("vakasi_[$dt->kd_prod]",false)."</td></tr>";}

        }
        if($dt->kd_prod=='UTS1'||$dt->kd_prod=='NUTS1'){$fUTS_.="<tr><td>".$dt->produk->produk."</td><td>".Html::textInput("vakasi[$dt->kd_prod]",$dt->qty)."</td><td>".Html::checkbox("vakasi_[$dt->kd_prod]",false)."</td></tr>";}

        if($dt->kd_prod=='TGS2'||$dt->kd_prod=='UAS'||$dt->kd_prod=='NUAS'){
            if($dt->kd_prod=='NUAS'){$NUAS+=1;}
            if($dt->kd_prod=='TGS2'||$dt->kd_prod=='UAS'){
                $fUAS.="<tr><td>".$dt->produk->produk."</td><td>".$dt->qty."</td><td> </td></tr>";
            }else{
                $fUAS.= "<tr><td>" . $dt->produk->produk . "</td><td>" . Html::textInput("vakasi[$dt->kd_prod]", $dt->qty) . "</td><td>" . Html::checkbox("vakasi_[$dt->kd_prod]", false) . "</td></tr>";
            }
        }

        if($dt->kd_prod=='UAS1'||$dt->kd_prod=='NUAS1'){$fUAS_.="<tr><td>".$dt->produk->produk."</td><td>".Html::textInput("vakasi[$dt->kd_prod]",$dt->qty)."</td><td>".Html::checkbox("vakasi_[$dt->kd_prod]",false)."</td></tr>";}
        */
    }

    if($NUTS==0){$fUTS.="<tr><td>Naskah Soal UTS</td><td>".Html::textInput("vakasi[NUTS]")."</td><td></td></tr>";}
    if($NUAS==0){$fUAS.="<tr><td>Naskah Soal UAS</td><td>".Html::textInput("vakasi[NUAS]")."</td><td></td></tr>";}
    ?>
        <?=
        '<div class="col-sm-6"><table class="table table-bordered"><thead>
        <tr>
            <th>Vakasi</th>
            <th>Qty.</th>
            <th>Delete</th>
        </tr></thead><tbody>'
        .$f.'</tbody></table></div>'
        .

        (!$fUTS||!$fUTS_?"":
            '<div class="col-sm-6"><table class="table table-bordered"><thead>
			<tr>
				<th>Vakasi</th>
				<th>Qty.</th>
				<th>Delete</th>
			</tr></thead><tbody>'
            .(!$fUTS?:"<tr><th colspan='3' ><center>UTS</center></th></tr>".$fUTS).(!$fUTS_?:"<tr><th colspan='3' ><center>UTS Susulan</center></th></tr>".$fUTS_)
            .$f.'</tbody></table></div>'
        ).
        (!$fUAS||!$fUAS_?"":
            '<div class="col-sm-6"><table class="table table-bordered"><thead>
			<tr>
				<th>Vakasi</th>
				<th>Qty.</th>
				<th>Delete</th>
			</tr></thead><tbody>'.
            (!$fUAS?:"<tr><th colspan='3' ><center>UAS</center></th></tr>".$fUAS).(!$fUAS_?:"<tr><th colspan='3' ><center>UAS Susulan</center></th></tr>".$fUAS_)
            .'</tbody></table></div>'
        )
        ?>

        <?php
		//foreach($sql as $d):
		?>
        <?= Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan'), ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>

