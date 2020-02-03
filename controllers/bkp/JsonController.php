<?php

namespace app\controllers;

use app\models\Funct;
use Yii;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * WaliController implements the CRUD actions for Wali model.
 */
class JsonController extends Controller{

    public function actionJrpr(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id     = end($_POST['depdrop_parents']);
            $list   = \app\models\JurusanProgram::find()
                ->select(['pr.pr_nama','pr.pr_kode'])
                ->innerJoin('tbl_program pr','(jurusan_program.program_id=pr.pr_kode)')
                ->where(['jurusan_program.jr_id'=>$id])
                ->asArray()->all();
            $selected  = null;
            if ($id != null && count($list)>0) {
                $selected = '';
                //$out[0] = ['id' => NULL, 'name' => 'Pilih Matakuliah' ];
                foreach ($list as $i => $kota) {
                    $out[] = ['id' => $kota['pr_kode'], 'name' => $kota['pr_nama']];
                    #   if ($i == 0) {$selected = $kota['pr_kode'];}
                }
                $selected = '';
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionFkjr(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id     = end($_POST['depdrop_parents']);
            $list   = \app\models\Jurusan::find()
                ->where(['fk_id'=>$id])
                ->asArray()->all();
            $selected  = null;
            if ($id != null && count($list)>0) {
                $selected = '';
                //$out[0] = ['id' => NULL, 'name' => 'Pilih Matakuliah' ];
                foreach ($list as $i => $kota) {
                    $out[] = ['id' => $kota['jr_id'], 'name' => $kota['jr_jenjang']." ". $kota['jr_nama']];
                    #   if ($i == 0) {$selected = $kota['pr_kode'];}
                }
                $selected = '';
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    public function actionNpm(){
        if (Yii::$app->getRequest()->isAjax) {
            $Params = $_POST;
            $gNPM=Yii::$app->db->createCommand(" SELECT dbo.generateNPM('$Params[nr]','$Params[s]','$Params[kr]') npm ")->queryOne()['npm'];
            if($gNPM){
                echo json_encode(['message'=>'','npm' =>$gNPM]);
            }else{
                echo json_encode(['message'=>'Terjadi Kesalahan, tidak dapat menyimpan. Hubungi IT Support.']);
            }

        }
    }

    #generate Kode Tarif
    public function actionGetPr(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id     = end($_POST['depdrop_parents']);
            $list   = \app\models\ProgramDaftar::find()
                ->where(['party'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list)>0) {
                $selected = '';
                //$out[0] = ['id' => NULL, 'name' => 'Pilih Matakuliah' ];
                foreach ($list as $i => $d) {
                    $out[] = ['id' => $d['kode'], 'name' => $d['nama_program']];
                    #   if ($i == 0) {$selected = $kota['pr_kode'];}
                }
                $selected = '';
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    // data jurusan berdasarkan program
    public function actionPrJr(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents=$_POST['depdrop_parents'];
            $id = $parents[0];
            $param1 = null;
            $param2 = null;

            if (!empty($_POST['depdrop_params'])) {
                $params = $_POST['depdrop_params'];
                $param1 = $params[0]; // get the value of input-type-1
                $param2 = $params[1]; // get the value of input-type-2
            }

            $list   = \app\models\JurusanProgram::find()
                ->select(['jr.jr_id','jr.jr_jenjang','jr.jr_nama','fk.fk_nama'])
                ->innerJoin('tbl_jurusan jr','(jr.jr_id=jurusan_program.jr_id)')
                ->innerJoin('tbl_fakultas fk','(fk.fk_id=jr.fk_id)')
                ->where(['jurusan_program.program_id'=>$id])
                ->asArray()->all();
            $selected  = null;
            if ($id != null && count($list)>0) {
                $selected = '';
                //$out[0] = ['id' => NULL, 'name' => 'Pilih Matakuliah' ];
                foreach ($list as $i => $d) {
                    $out[$d['fk_nama']][] = ['id' => $d['jr_id'], 'name' => $d['jr_jenjang'].' '.$d['jr_nama']];
                    #   if ($i == 0) {$selected = $kota['pr_kode'];}
                }
                $selected = '';
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }


        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    #end generate Kode Tarif




}
