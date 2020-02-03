<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title" style="text-transform: uppercase;font-weight: bold;font-size: 14px"> Ploating Dosen <?= $TITLE ?></span>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' =>2, 'deviceSize' => ActiveForm::SIZE_SMALL],
            ]
        );
        echo Form::widget([
                #'model' => $model,
                'form' => $form,
                'formName'=>'ploat',
                'columns' => 3,
                'attributes' => [
                    'kr_kode'=>[
                        'label'=>false,
                        'type'=>Form::INPUT_WIDGET,
                        'widgetClass'=>'\kartik\widgets\Select2',
                        'value'=>$ploat['kr_kode'],
                        'options'=>[
                            'data' =>Funct::AKADEMIK(),
                            'options' => ['placeholder' =>'Tahun Akademik',],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    'tipe'=>[
                        'label'=>false,
                        'type'=>Form::INPUT_DROPDOWN_LIST,
                        'value'=>$ploat['tipe'],
                        'items'=>[0=>'Pagi','Sore','Semua']
                    ],
                    [
                        'type'=>Form::INPUT_RAW,
                        'value'=>'<div class="pull-right">'.
                            Html::submitButton('<i class="fa fa-eye"></i> Pratinjau', ['class' =>'btn btn-primary','name'=>'pr'])
                            .' '.Html::submitButton('<i class="fa fa-download"></i>Unduh Data', ['class' =>'btn btn-primary','name'=>'ex'])
                            .'</div>'
                    ]

                ]
            ]
        );

        ActiveForm::end();

        ?>
        <p> </p>
        <div class="raw">
            <style>
                #c {
                    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }

                #c td, #c th {
                    border: 1px solid #ddd;
                    padding: 8px;
                }

                #c tr:nth-child(even){background-color: #f2f2f2;}

                #c tr:hover {background-color: #ddd;}

                #c th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #4CAF50;
                    color: white;
                }
            </style>
            <div style="overflow:auto;height:500px" >
                <table style=" white-space: nowrap;" id="c" >
                    <thead>
                    <tr>
                        <th>NO.</th>
                        <!-- 2 --> <th>TIPE DOSEN</th>
                        <!-- 3 --><th>MAKS. SKS.</th>
                        <!-- 4 --><th>DOSEN</th>
                        <!-- 8,9 --><th>MTK.</th>
                        <!-- 10 --><th>SKS</th>
                        <!-- 5,6, --><th>JADWAL</th>
                        <!-- 7 --><th>KLS.</th>
                        <!-- 11--><th>JML. MHS.</th>
                    </tr>

                    </thead>
                    <tbody>
                    <?php
                    $GKode=$def['GKode'];
                    #$GKode='';
                    $n=1;$KLS="";$pKLS="";$MK="";$pMK="";$td="";$totMhs=0;$SKS=0;
                    foreach($q as $d){
                        if($GKode!=$d[0]){
                            echo $td;
                            $GKode=$d[0];$pKLS="";$KLS="";$n++;
                            $MK="";$pMK="";$totMhs=0;$SKS=0;
                        }
                        if($SKS<$d[10]){$SKS=$d[10];}

                        if($pKLS!=$d[7]){$pKLS=$d[7];$KLS.=",$pKLS";}
                        if($pMK!=$d[8]){$pMK=$d[8];$MK[$pMK]="( $d[8] ) $d[9] ";}
                        $totMhs+=$d[11];
                        $jdwl=explode("|",$d[6]);
                        $jd = "";

                        foreach($jdwl as $k=>$v){
                            $Info=explode('#',$v);
                            $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";
                            $jd .=$ket."<br />";
                        }


                        #$KLS.=",$d[9]";
                        $td ="<tr>
                                <td>$n</td><td> $d[2]</td><td> $d[3] </td>
                                <td> $d[4] </td>
                                <td>".implode("<br>",$MK)." </td>
                                <td> $SKS </td>
                                <td> $jd</td>
                                <td> ".substr($KLS,1)."  </td>
                                <td> $totMhs </td>";
                    }
                    echo $td;

                    ?>



                    </tbody>

                </table>

            </div>

        </div>


    </div>

</div>
