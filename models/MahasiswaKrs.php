<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\Mahasiswa;

/**
 * MahasiswaSearch represents the model behind the search form about `app\models\Mahasiswa`.
 */
class MahasiswaKrs extends Mahasiswa
{
	public $Nama;	
	
    public function rules()
    {
        return [
            [['mhs_nim', 'mhs_pass', 'mhs_pass_kode', 'mhs_angkatan', 'jr_id', 'pr_kode', 'mhs_stat', 'ds_wali','Nama'], 'safe'],
            [['mhs_tipe'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon=''){
		$db = Yii::$app->db1;
		$keuangan = Funct::getDsnAttribute('dbname', $db->dsn);

        $query = "  SELECT DISTINCT kln.kr_kode, krs.mhs_nim
                    from tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk
                    where krs.jdwl_id = jd.jdwl_id 
                                    and bn.id=jd.bn_id
                                    and bn.mtk_kode=mk.mtk_kode
                                    and bn.kln_id=kln.kln_id
                                    and (krs.RStat='0' or krs.RStat is null )
                              and  kln.kr_kode ='11516'
                    ";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'totalCount' => (int)$count,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }


}