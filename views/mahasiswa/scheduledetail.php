<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use app\models\Funct;
?>

<div class="panel panel-primary">
<div class="panel-body">
<?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_VERTICAL,'method'=>'get',
        'action' => Url::to(['mahasiswa/schedule-detail'])
        ]); 
		
        $jenis = null;
		$kr = null;
		
        if(isset($_GET['Jadwal']['jenis']) && !empty($_GET['Jadwal']['jenis']) && !empty($_GET['Jadwal']['kr_kode']) && isset($_GET['Jadwal']['kr_kode']) ){
		 $jenis=$_GET['Jadwal']['jenis'];
		 $kr   = $_GET['Jadwal']['kr_kode'];
		}
    echo $form->field($model, 'jenis')
        ->dropDownList(
           	['empty'=>'Jenis', 'kuliah'=>'Jadwal Kuliah', 'UTS'=>'Jadwal UTS', 'UAS'=>'Jadwal UAS'],
			['options' =>[$jenis => ['selected ' => true]]]
        );

    echo $form->field($model, 'kr_kode')
        ->dropDownList(Funct::Kalender2(),['options' =>[$kr => ['selected ' => true]]]);

    echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Cari'), ['class' => 'btn btn-primary']);   
    ActiveForm::end(); 
?>
    </div>
</div>


<?php
use yii\base\view;
if($jn=="UTS"){
	echo Yii::$app->controller->renderPartial('uts', array('dataProvider'=>$dataProvider,'mhs'=>$mhs,'model'=>$model,'jn'=>$jn,'kr'=>$kr,));
}else if($jn=="UAS"){
	echo Yii::$app->controller->renderPartial('Uas', array('dataProvider'=>$dataProvider,'MHS'=>$mhs,'model'=>$model,'jn'=>$jn,'kr'=>$kr,'lunas'=>$lunas));
}else if($jn=="kuliah"){
    echo Yii::$app->controller->renderPartial('kuliah', array('dataProvider'=>$dataProvider,'MHS'=>$mhs,'model'=>$model,'jn'=>$jn,'kr'=>$kr,)); 
}else{
	return $this->redirect(array('schedule'));
}
?>