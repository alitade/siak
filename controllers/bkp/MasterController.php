<?php

namespace app\controllers;

use Yii;
use app\models\Funct;
use yii\data\ActiveDataProvider;
use app\models\Fakultas;
use app\models\FakultasSearch;

use app\models\Jurusan;
use app\models\JurusanSearch;

use app\models\Dosen;
use app\models\DosenSearch;

use app\models\Ruang;
use app\models\RuangSearch;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\db\Expression;
class MasterController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(){return $this->render('@app/views/site/index');}
	/*Fakultas*/
    public function actionFk(){
        $searchModel = new FakultasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('fk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFkView($id){
        $model = Fakultas::findOne($id);
		return $this->render('fk_view', ['model' => $model]);
    }

    public function actionFkCreate(){
        $model = new Fakultas;
        if ($model->load(Yii::$app->request->post())){
			$model->cuid	= Yii::$app->user->identity->id;
			$model->ctgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS('Menambah Data Fakultas',$model,$model->fk_id,'c');
				return $this->redirect(['fk-view', 'id' => $model->fk_id]);
			}
        }
		return $this->render('fk_create', [
			'model' => $model,
		]);
	
    }

    public function actionFkUpdate($id){
        $model =Fakultas::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
			$model->uuid	= Yii::$app->user->identity->id;
			$model->utgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Merubah Data Fakultas ($model->fk_id)",new Fakultas(),$id,'u');
				return $this->redirect(['fk-view', 'id' => $model->fk_id]);
			}
        }
		return $this->render('fk_update', [
			'model' => $model,
		]);
	
    }

    public function actionFkDelete($id){
        $model=Fakultas::findOne($id);
		$model->RStat=1;
		$model->duid= Yii::$app->user->identity->id;
		$model->dtgl= new  Expression("getdate()");
		$model->save();
		\app\models\Funct::LOGS("Menghapus Data Fakultas $model->fk_id ",new Fakultas,$id,'d');
        return $this->redirect(['/master/fk']);
    }
	/* End Fakultas */

	/* Jurusan */
    public function actionJr(){
        $searchModel = new JurusanSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('jr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionJrView($id){
        $model = Jurusan::findOne($id);
		return $this->render('jr_view', ['model' => $model]);
    }

    public function actionJrCreate(){
        $model = new Jurusan;
        if ($model->load(Yii::$app->request->post()) ) {
			$model->jr_stat = '1';
			$model->cuid	= Yii::$app->user->identity->id;
			$model->ctgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Menambah Data Jurusan $model->jr_id ",$model,$model->jr_id,'c');
				return $this->redirect(['jr-view', 'id' => $model->jr_id]);
			}
        } 
		
		return $this->render('jr_create', [
			'model' => $model,
		]);
	
    }

    public function actionJrUpdate($id){
        $model = Jurusan::findOne($id);
        if ( $model->jr_id && $model->load(Yii::$app->request->post())) {
			$model->uuid	= Yii::$app->user->identity->id;
			$model->utgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Mengubah Data Jurusan ($model->jr_id) ",new Jurusan,$id,'u');
				return $this->redirect(['jr-view', 'id' => $model->jr_id]);
			}
        }
		
		return $this->render('jr_update', [
			'model' => $model,
		]);

    }

    public function actionJrDelete($id){
        $model= Jurusan::findOne($id);
		$model->RStat=1;
		$model->duid	= Yii::$app->user->identity->id;
		$model->dtgl 	= new  Expression("getdate()");
		$model->save();
		\app\models\Funct::LOGS("Menghapus Data Jurusan ($model->jr_id) ",new Jurusan(),$id,'d');
        return $this->redirect(['/master/jr']);
    }
	/* end jurusan */

	/* Ruang */
    public function actionRg()
    {
        $searchModel = new RuangSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('rg_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionRgView($id)
    {
        $model = Ruang::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['rg-view', 'id' => $model->rg_kode]);
        } else {
        	return $this->render('rg_view', ['model' => $model]);
		}
    }

    public function actionRgCreate(){
        $model = new Ruang;
       if ($model->load(Yii::$app->request->post())){
			$model->cuid	= Yii::$app->user->identity->id;
			$model->ctgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Menamah Data Ruangan ($model->rg_kode) ",$model,$model->rg_kode,'c');
				return $this->redirect(['/master/rg-view', 'id' => $model->rg_kode]);
			}
        } 
		return $this->render('/master/rg_create', [
			'model' => $model,
		]);
    }

    public function actionRgUpdate($id){
        $model = Ruang::findOne($id);
       if ($model->load(Yii::$app->request->post())){
			$model->uuid	= Yii::$app->user->identity->id;
			$model->utgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Mengubah Data Ruangan ($model->rg_kode) ",new Ruang,$id,'u');
				return $this->redirect(['rg-view', 'id' => $model->rg_kode]);
			}
        }
		return $this->render('rg_update', [
			'model' => $model,
		]);
    }

    public function actionRgDelete($id){
        $model= Ruang::findOne($id);
		$model->RStat=1;
		$model->duid	= Yii::$app->user->identity->id;
		$model->dtgl 	= new  Expression("getdate()");
		$model->save();
		\app\models\Funct::LOGS("Mengubah Data Ruangan ($model->rg_kode) ",new Ruang,$id,'d');
        return $this->redirect(['rg']);
    }
	/* End Ruang */

	/* Dosen */
    public function actionDs()
    {
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('ds_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDsView($id)
    {
        $model= Dosen::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['ds-view', 'id' => $model->ds_id]);
        } else {
        	return $this->render('ds_view', ['model' => $model]);
		}
    }

    public function actionDsCreate(){
		$model = new Dosen;
        if($model->load(Yii::$app->request->post())){
			$kode = Funct::acak(10);
			$pass=md5('ypkp@#1234'.$kode);
			$model->ds_pass=$pass;
			$model->ds_pass_kode=$kode;
			$model->ds_tipe='3';

			if($model->save()){
				\app\models\Funct::LOGS("Menambah Data Dosen ($model->ds_id) ",$model,$model->ds_id,'c');
				return $this->redirect(['ds-view', 'id' => $model->ds_id]);				
			}
			
			else(die(print_r($model->getErrors())));
			
        } else {
            return $this->render('ds_create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDsUpdate($id){
        $model= Dosen::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Dosen ($id) ",new Dosen,$id,'u');
            return $this->redirect(['ds-view', 'id' => $model->ds_id]);
        } else {
            return $this->render('ds_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDsDelete($id){
        $model= Dosen::findOne($id);
		$model->RStat='1';
		$model->save(false);
		\app\models\Funct::LOGS("Menghapus Data Dosen ($model->ds_id) ",new Dosen,$id,'u');
        return $this->redirect(['ds']);
    }
	/* End Dosen */


    public function actionReportFakultas() {
 
        $searchModel = Fakultas::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Fakultas</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID Fakultas</th>
                                <th>Nama Fakultas</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= ' 
                    <tbody>
                        <tr>
                            <td>'.$value->fk_id.'</td>
                            <td>'.$value->fk_nama.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Fakultas - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:s').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportJurusan() {
 
        $searchModel = Jurusan::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Jurusan</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID Jurusan</th>
                                <th>Nama Jurusan</th>
                                <th>Kode NIM Jurusan</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= ' 
                    <tbody>
                        <tr>
                            <td>'.$value->jr_id.'</td>
                            <td>'.$value->jr_nama.'</td>
                            <td>'.$value->jr_kode_nim.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Jurusan - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportRuangan() {
 
        $searchModel = Ruang::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Ruangan</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Ruangan</th>
                                <th>Kapasitas</th>
                                <th>Gedung</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= ' 
                    <tbody>
                        <tr>
                            <td>'.$value->rg_kode.'</td>
                            <td>'.$value->rg_nama.'</td>
                            <td>'.$value->kapasitas.'</td>
                            <td>'.$value->gedung->Name.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Ruangan - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportDosen() {
 
        $searchModel = Dosen::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 2,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Dosen</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NIDN</th>
                                <th>Nama Dosen</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= ' 
                    <tbody>
                        <tr>
                            <td>'.$value->ds_nidn.'</td>
                            <td>'.$value->ds_nm.'</td>
                            <td>'.$value->ds_email.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Dosen - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }
}
