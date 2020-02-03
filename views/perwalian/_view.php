<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

$this->title = 'Kartu Rancangan Studi (KRS)';
//$this->params['breadcrumbs'][] = $this->title;
?>


<?php 
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_VERTICAL,
    'method'=>'get',
    // 'enableAjaxValidation' => false,
    // 'enableClientValidation'=>true
    ]);
?>
<div class="panel panel-primary">
    <div class="panel-heading">Kartu Rancangan Studi (KRS)</div>
    <div class="panel-body">
    <table class='table table-hover table-condensed'>
        <tr>
            <td style="vertical-align:middle">Tahun Akademik</td>
            <td style="vertical-align:middle">
                <?php 
				$dataKR=[];
				foreach(\app\models\Kurikulum::find()->all() as $d){$dataKR[$d->kr_kode]=$d->kr_kode."-".$d->kr_nama;}
				
				/*
				$dataMHS=[];
				foreach(\app\models\Mahasiswa::find()->all() as $d){$dataMHS[$d->mhs_nim]=$d->mhs_nim."-".$d->mhs->people->Nama;}
				*/
                $krkd = null; 
                if(isset($_GET['Krs']['kurikulum'])){$krkd=$_GET['Krs']['kurikulum'];}
				
                echo $form->field($krs, 'kurikulum')->widget(Select2::classname(), [
					'data' => ($dataKR?$dataKR:[""]),
					'options' => ['placeholder' => 'Tahun Akademik'],
					'pluginOptions' => [
						'allowClear' => true
					],
				])->label(false);
				
                ?>
            </td>
            <td style="vertical-align:middle">
                <?php echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Search'), ['class' => 'btn btn-primary']); ?>
            </td>
            <td rowspan="4"><center><br><!--?= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?>--></center></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><input type="text" name="nim" class="form-control" id="nim" value="<?php echo @$_GET['nim']?>" /></td>
        </tr>
		<?php if(isset($_GET['Krs']['kurikulum']) and isset($_GET['nim'])  ):?>        
        <tr>
            <td>Nama</td>
            <td><?= Funct::profMhs(@$_GET['nim'],"Nama",$J);?></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><?= @$jr->jr_jenjang."-".@$jr->jr_nama;?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td><?= @$pr->pr_nama;?></td>
        </tr>
        <tr>
            <td>Pembimbing</td>
            <td><?= Funct::nameWaliK(@$mhs->ds_wali,"ds_nm",$J);?></td>
        </tr>        
        <?php endif;?>
    </table>
</div>
</div>
<?php ActiveForm::end();?>
