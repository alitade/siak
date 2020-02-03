<?php

namespace app\modules\transkrip\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transkrip\models\Nilai;

/**
 * NilaiSearch represents the model behind the search form about `app\modules\transkrip\models\Nilai`.
 */
class NilaiSearch extends Nilai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'semester', 'sks'], 'integer'],
            [[
				'npm', 'kode_mk', 'nama_mk', 'huruf', 'tahun', 'tgl_input', 'stat', 'kat',
				'NAMA','ANGKATAN','jr_id','MATKUL'
			], 'safe'],
			
            [['nilai'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params){
        $query = Nilai::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'semester' => $this->semester,
            'sks' => $this->sks,
            'nilai' => $this->nilai,
            'tgl_input' => $this->tgl_input,
        ]);

        $query->andFilterWhere(['like', 'npm', $this->npm])
            ->andFilterWhere(['like', 'kode_mk', $this->kode_mk])
            ->andFilterWhere(['like', 'nama_mk', $this->nama_mk])
            ->andFilterWhere(['like', 'huruf', $this->huruf])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'stat', $this->stat])
            ->andFilterWhere(['like', 'kat', $this->kat]);

        return $dataProvider;
    }

    public function searchDetail($params){
		$siak	= \app\models\Funct::getDsnAttribute('dbname',yii::$app->db->dsn);
		$uang	= \app\models\Funct::getDsnAttribute('dbname',yii::$app->db1->dsn);
        $query = Nilai::find()
		->select([
			't_nilai.npm',
			'mhs.jr_id',
			"t_nilai.kode_mk","t_nilai.nama_mk","t_nilai.semester","t_nilai.sks","t_nilai.huruf",
			'MATKUL'=>"concat(kode_mk,' | ',nama_mk)",
			"NAMA"=>'p.Nama',
			'ANGKATAN'=>'mhs.mhs_angkatan'
		])
		->innerJoin("$siak.dbo.tbl_mahasiswa mhs",("mhs.mhs_nim=t_nilai.npm and isnull(mhs.RStat,0)=0"))
		->innerJoin("$uang.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = mhs.mhs_nim ")
		->innerJoin("$uang.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
		->where("isnull(t_nilai.stat,0)=0");
		
        // add conditions that should always apply here

		
        
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'semester' => $this->semester,
            'sks' => $this->sks,
            'nilai' => $this->nilai,
            'mhs.jr_id' => $this->jr_id,
            'tgl_input' => $this->tgl_input,
        ]);

        $query->andFilterWhere(['like', 'npm', $this->npm])
            ->andFilterWhere(['like', "concat(kode_mk,' | ',nama_mk)", $this->MATKUL])
            ->andFilterWhere(['like', 'p.Nama', $this->NAMA])
            ->andFilterWhere(['like', 'mhs.mhs_angkatan', $this->ANGKATAN]);

        return $dataProvider;
    }


}
