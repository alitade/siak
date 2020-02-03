<style type="text/css">
    .center-block {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
input:required,
textarea:required {
  border-color: red !important;
}
</style>
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

//use yii\helpers\Html;
//use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use kartik\builder\Form;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact center-block">    
 <div class="panel panel-default">
 <div class="panel-heading" style="color:#009;font-size:16px;text-align:center">
 	<?php
	if(app\models\Funct::cekPassDef()){		
		echo "Segera ganti kata sandi anda jika masih menggunakan kata sandi default (ypkp@#1234)";
	}else{
		echo "Ganti Kata Sandi";	
	}
	?>
</div>
 <div class="panel-body">
    <?php
    echo Html::beginForm('', '', ['class'=>'form-horizontal']);
    echo Form::widget([
    // formName is mandatory for non active forms
    // you can get all attributes in your controller 
    // using $_POST['kvform']
    'formName'=>'Reset',
    
    // default grid columns
    'columns'=>1,
    
    // set global attribute defaults
    'attributeDefaults'=>[
        'type'=>Form::INPUT_TEXT,
        'options' => ['required'=>'required'],
        'labelOptions'=>['class'=>'col-md-3'], 
        'inputContainer'=>['class'=>'col-md-9'], 
        'container'=>['class'=>'form-group'],
    ],
    
    'attributes'=>[
        'password1'=>['label'=>'Password Baru', 'type'=>Form::INPUT_PASSWORD, 'value'=>''],
        'password2'=>['label'=>'Konfirmasi Password Baru','type'=>Form::INPUT_PASSWORD, 'value'=>''],
        'password3'=>['label'=>'Password Lama', 'type'=>Form::INPUT_PASSWORD, 'value'=>''],
        
    ]
]);



    ?>
   </div>
   </div>
    <div class="pull-right">
        <?= Html::submitButton('<i class="glyphicon glyphicon-save"></i> Simpan Kata Sandi', ['class' => 'btn btn-success', 'name' => 'contact-button']) ." " . Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Reset', ['class'=>'btn btn-danger']) ?>
    </div>
</div>

<?php echo Html::endForm(); ?>