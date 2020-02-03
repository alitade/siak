<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\TracerjenisJawaban;
use app\models\TracerjenisKuisioner;

$this->title = 'Tracer Study';
?>
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Data Tracer</div>
    <div class="panel-body">
    <?php
	    $form = ActiveForm::begin();
	    echo Form::widget([
		    'model'=>$model,
		    'form'=>$form,
		    'columns'=>2,
		    'attributes'=>[       // 2 column layout
		        'no_telepon'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'No Telepon']],
		        'email'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Email']]
		    ]
		]);
	?>
	<br/>
	<strong>Mohon untuk tidak mengisi secara Asal, ini soal terakhir yang diberikan kampus untuk Lulusan. Baca pertanyaan serta tata cara pengisian dengan seksama lalu isi pertanyaan yang diajukan</strong>
	<br/><br/>
	<?
	    echo $form->field($model, 'f3')->radioList(['1'=>'Sebelum Lulus','2'=>'Sesudah Lulus','3'=>'Saya tidak Mencari Pekerjaan <i>(Langsung ke pertanyaan No. 7)</i>'],['id'=>'radiof3','onclick'=>'cekAh();']);
    ?>
    <div class="form-group">
    <strong>Masukan jumlah Bulan untuk menentukan lama sebelum/sesudah lulus dalam memulai pencarian pekerjaan, jika jawaban yang dipilih adalah tidak mencari pekerjaan maka textbox jumlah bulan tidak perlu diisi.</strong>
    <?php echo $form->field($model,'ketf3')->textInput(['placeholder'=>'Jumlah Bulan', 'size'=>'80','id'=>'ketf3']); ?>
    </div><br/>
    <?php 
    	echo $form->field($model, 'f4')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f4'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => true],
		    'pluginOptions' => [
		        
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		])->label('2. Bagaimana anda mencari pekerjaan tersebut? <i>Jawaban bisa lebih dari satu</i>');
    ?>
    <br/>
    <?
	    echo $form->field($model, 'f5')->radioList(['21'=>'Sebelum Lulus','22'=>'Sesudah Lulus']);
    ?>
    <div class="form-group">
    <strong>Masukan jumlah Bulan di dalam Textbox untuk menentukan lama menunggu sampai mendapatkan pekerjaan pertama</strong>
    	<?php echo $form->field($model,'ketf5')->textInput(['placeholder'=>'Jumlah Bulan', 'size'=>'80','id'=>'ketf5']); ?>
    </div>
    <br/>
    <?php
    	echo $form->field($model,'f6');
    	echo $form->field($model,'f7');
    	echo $form->field($model,'f7a');
    ?>
    <?php
	    echo $form->field($model, 'f8')->radioList(['26'=>'Ya <i>(Langsung ke Pertanyaan No.10)</i>','27'=>'Tidak']);
	?>
	<?php
	  	echo $form->field($model, 'f9')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f9'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => true],
		    'pluginOptions' => [
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		])->label('8. Bagaimana anda menggambarkan situasi anda saat ini? <i>Jawaban bisa lebih dari satu</i>');
		echo $form->field($model, 'f10')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f10'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => false],
		    'pluginOptions' => [
		        
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		])->label('9. Apakah anda aktif mencari pekerjaan dalam 4 minggu terakhir?');
		echo $form->field($model, 'f11')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f11'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => false],
		    'pluginOptions' => [
		        
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		])->label('10. Apa jenis perusahaan/instansi/institusi tempat anda bekerja sekarang?');
		echo $form->field($model, 'f12')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f12'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => false],
		    'pluginOptions' => [
		        
		    ],
		])->label('11. Tempat anda bekerja saat ini bergerak di bidang apa?');  
		echo $form->field($model, 'f13')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f13'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => false],
		    'pluginOptions' => [
		        
		    ],
		])->label('12. Kira-kira berapa pendapatan anda setiap bulannya?');
		echo $form->field($model, 'f14')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f14'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => false],
		    'pluginOptions' => [
		        
		    ],
		])->label('13. Seberapa erat hubungan antara bidang studi dengan pekerjaan anda?');
		echo $form->field($model, 'f15')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f15'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => false],
		    'pluginOptions' => [
		        
		    ],
		])->label('14. Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?');
		echo $form->field($model, 'f16')->widget(Select2::classname(), [
		    'data' => TracerjenisJawaban::listJawaban('f16'),
		    'options' => ['placeholder' => 'Pilihan ...', 'multiple' => true],
		    'pluginOptions' => [
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		])->label('15. Jika menurut anda pekerjaan anda saat ini tidak sesuai dengan pendidikan anda, mengapa anda mengambilnya? <i>Jawaban bisa lebih dari satu</i>');
    ?>
	<br/>
	<h3>Kuesioner</h3>
	<strong>Berikan penilaian yang sesuai pada pertanyaan-pertanyaan di bawah ini dimana point A adalah pengukuran kompetensi yang dimiliki oleh setiap mahasiswa. Serta point B adalah kontribusi perguruan tinggi untuk meningkatkan kompetensi yang dimiliki mahasiswa. Dimana nilai 1 paling rendah dan 5 paling tinggi.</strong><br/><br/>
	<strong> A. Pada saat lulus, pada tingkat mana kompetensi di bawah ini anda kuasai?</strong>
	<table class="table table-striped">
  		<tr>
  			<td width="50%">Pertanyaan</td>
  			<td>Jawaban</td>
  		</tr>
  		<?php
  			$data = TracerjenisKuisioner::getPertanyaan('A');
  			foreach ($data as $row) {
  		?>
  		<tr>
  			<td><?php echo $row['pertanyaan']; ?></td>
  			<td><?php echo Html::activeRadioList($model, $row['pemodelan'],['1'=>'1','2'=>'2      ','3'=>'3','4'=>'4','5'=>'5'], ['options'=>['style'=>"margin: 0 10px 0 10px;"]]); ?></td>
  		</tr>
  		<?php
  			}
  		?>
	</table>
	<br/>
	<strong> B. Pada saat lulus, bagaimana kontribusi perguruan tinggi dalam hal kompetensi di bawah ini?</strong>
	<table class="table table-striped">
  		<tr>
  			<td width="50%">Pertanyaan</td>
  			<td>Jawaban</td>
  		</tr>
  		<?php
  			$data = TracerjenisKuisioner::getPertanyaan('B');
  			foreach ($data as $row) {
  		?>
  		<tr>
  			<td><?php echo $row['pertanyaan']; ?></td>
  			<td><?php echo Html::activeRadioList($model, $row['pemodelan'],['1'=>'1','2'=>'2      ','3'=>'3','4'=>'4','5'=>'5'], ['options'=>['style'=>"margin: 0 10px 0 10px;"]]); ?></td>
  		</tr>
  		<?php
  			}
  		?>
  	</table>
  	<br/>
    <?
    echo Html::submitButton('Simpan', ['class' => 'btn btn-primary']);
    ActiveForm::end();
    ?>
</div>
</div>
<script type="text/javascript">
function cekAh(){
	
	alert("ts");
}
</script>