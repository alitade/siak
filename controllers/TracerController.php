<?php
namespace app\controllers;
use Yii;
use app\models\Mahasiswa;
use app\models\Jurusan;
use app\models\Program;
use app\models\Tracer;
use app\models\TracerJawaban;
use app\models\TracerKuisioner;
use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider; 

use yii\helpers\Url;

class TracerController extends Controller
{
	public function actionIndex(){
		$ID = Yii::$app->user->identity->username;
		$mhs = Mahasiswa::findOne($ID);
        $jr = Jurusan::findOne($mhs->jr_id);
    	$pr = Program::findOne($mhs->pr_kode);
    	$tr = Tracer::find()->where(['npm'=>$ID])->one();
        $data = array(
	        'mhs'=>$mhs,
	        'jr'=>$jr,
	        'pr'=>$pr,
	        'model'=>$tr
    	);
    	return $this->render('index',$data);
	}

	public function actionAdd(){
		$tr = new Tracer();
		if ($tr->load(Yii::$app->request->post())){
			$tr->npm = Yii::$app->user->identity->username;
			$tr->tanggal = date('Y-m-d H:i:s');
			if($tr->save()){
				$this->insertJawaban($tr->id, 'f3', $_POST['Tracer']['f3'], $_POST['Tracer']['ketf3']);
				foreach($_POST['Tracer']['f4'] as $val){
					$this->insertJawaban($tr->id,'f4',$val);
				}
				$this->insertJawaban($tr->id, 'f5',$_POST['Tracer']['f5'],$_POST['Tracer']['ketf5']);
				$this->insertJawaban($tr->id, 'f6','23', $_POST['Tracer']['f6']);
				$this->insertJawaban($tr->id, 'f7','24', $_POST['Tracer']['f7']);
				$this->insertJawaban($tr->id, 'f7a','25', $_POST['Tracer']['f7a']);
				$this->insertJawaban($tr->id, 'f8',$_POST['Tracer']['f8']);
				foreach($_POST['Tracer']['f9'] as $val){
					$this->insertJawaban($tr->id,'f9',$val);
				}
				$this->insertJawaban($tr->id, 'f10',$_POST['Tracer']['f10']);
				$this->insertJawaban($tr->id, 'f11',$_POST['Tracer']['f11']);
				$this->insertJawaban($tr->id, 'f12',$_POST['Tracer']['f12']);
				$this->insertJawaban($tr->id, 'f13',$_POST['Tracer']['f13']);
				$this->insertJawaban($tr->id, 'f14',$_POST['Tracer']['f14']);
				$this->insertJawaban($tr->id, 'f15',$_POST['Tracer']['f15']);
				foreach ($_POST['Tracer']['f16'] as $val) {
					$this->insertJawaban($tr->id,'f16',$val);
				}
				$var = 'a';
				for($i=1; $i<=29; $i++){
					$md = $var.(string)$i;
					$this->insertKuisioner($tr->id, $md, $_POST['Tracer'][$md]);
				}
				$var = 'b';
				for($i=1; $i<=29; $i++){
					$md = $var.(string)$i;
					$this->insertKuisioner($tr->id, $md, $_POST['Tracer'][$md]);
				}
				$this->redirect('index');
			}
		}
		return $this->render('add',['model'=>$tr]);
	}

	function insertJawaban($tid, $id, $jawaban="", $ket=""){
		$model = new TracerJawaban();
		$model->tracer_id = $tid;
		$model->pertanyaan_id = $id;
		$model->jawaban_id = $jawaban;
		$model->ket = $ket;
		$model->save();
	}

	function insertKuisioner($tid, $id, $jawaban){
		$model = new TracerKuisioner();
		$model->tracer_id = $tid;
		$model->kuisioner_id = $id;
		$model->jawaban = $jawaban;
		$model->save();
	}
}
?>