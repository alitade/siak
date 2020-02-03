<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\BobotNilai;

class BobotNilaiSearch extends BobotNilai{

	public $kr_kode;
	public $pr_kode;
	public $jr_id;
	public $tot;

    public function rules(){
        return [
            [['id', 'kln_id', 'nb_tgs1', 'nb_tgs2', 'nb_tgs3', 'nb_tambahan', 'nb_quis', 'nb_uts', 'nb_uas'], 'integer'],
            [['mtk_kode', 'ds_nidn','kr_kode','pr_kode','jr_id','tot'], 'safe'],
            [['B', 'C', 'D', 'E'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'kr_kode' => 'Kurikulum',
            'pr_kode' => 'Program',
            'jr_id' => 'Jurusan',
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon=''){

        $query = BobotNilai::find()
		->select(
		['tbl_bobot_nilai.*','kr_kode','kn.jr_id','kn.pr_kode',
			'tot'=>'id',
		])
        ->innerJoin('tbl_kalender kn','kn.kln_id = tbl_bobot_nilai.kln_id')
		->where("isnull(tbl_bobot_nilai.RStat,0)=0 ");
		
		//->orderBy("tbl_bobot_nilai.kln_id desc");
		
		$query->orderBy([
			'substring(kn.kr_kode,2,4)'=>SORT_DESC,
			'substring(kn.kr_kode,1,1)'=>SORT_DESC,
			//'(select count(*) from tbl_jadwal where bn_id=tbl_bobot_nilai.id)'=>SORT_ASC,
		]);		
		

		if($kon!=''){
			$query->andWhere($kon);	
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $dataProvider->setSort([
        'attributes' => [
            'tot' => [
                'asc' => ['tot' => SORT_ASC],
                'desc' => ['tot' => SORT_DESC],
                'label' => 'Jadwal',
                'default' => SORT_ASC
            ],
        ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kr_kode' => $this->kr_kode	,
            'id' => $this->id,
            'kln_id' => $this->kln_id,

            'kn.pr_kode' => $this->pr_kode,
            'kn.kr_kode' => $this->kr_kode,
            'kn.jr_id' => $this->jr_id,

            'nb_tgs1' => $this->nb_tgs1,
            'nb_tgs2' => $this->nb_tgs2,
            'nb_tgs3' => $this->nb_tgs3,
            'nb_tambahan' => $this->nb_tambahan,
            'nb_quis' => $this->nb_quis,
            'nb_uts' => $this->nb_uts,
            'nb_uas' => $this->nb_uas,
            'B' => $this->B,
            'C' => $this->C,
            'D' => $this->D,
            'E' => $this->E,
        ]);

        $query->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['=', '(select count(*) from tbl_jadwal where bn_id=tbl_bobot_nilai.id)', $this->tot])
            ->andFilterWhere(['=', 'ds_nidn', $this->ds_nidn]);

       //die($query->createCommand()->getRawSql());
        //var_dump($dataProvider->getModels());die();
        return $dataProvider;
    }




    public function vakasi($params,$kon=''){
        $query = BobotNilai::find()
		->select(
		[
			'tbl_bobot_nilai.ds_nidn','kr_kode',
			'id'=>'max(tbl_bobot_nilai.id)'
		])->distinct(true)
        ->innerJoin("tbl_kalender kn","kn.kln_id=tbl_bobot_nilai.kln_id and isnull(kn.RStat,0)=0")
        ->innerJoin("tbl_jadwal jd","jd.bn_id=tbl_bobot_nilai.id and isnull(jd.RStat,0)=0")
        ->innerJoin("tbl_krs krs","krs.jdwl_id=jd.jdwl_id and isnull(krs.RStat,0)=0")
        //->innerJoin("tbl_dosen kn','kn.kln_id = tbl_bobot_nilai.kln_id and isnull(kn.RStat,0)=0")
		->where("isnull(tbl_bobot_nilai.RStat,0)=0 ")
		->groupBy("tbl_bobot_nilai.ds_nidn,kr_kode");
		$query->orderBy([
			'ds_nidn'=>SORT_DESC,
			//'(select count(*) from tbl_jadwal where bn_id=tbl_bobot_nilai.id)'=>SORT_ASC,
		]);		
		

		if($kon!=''){
			$query->andWhere($kon);	
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $dataProvider->setSort([
        'attributes' => [
            'tot' => [
                'asc' => ['tot' => SORT_ASC],
                'desc' => ['tot' => SORT_DESC],
                'label' => 'Jadwal',
                'default' => SORT_ASC
            ],
        ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kr_kode' => $this->kr_kode	,
            'id' => $this->id,
            'kln_id' => $this->kln_id,

            'kn.pr_kode' => $this->pr_kode,
            'kn.kr_kode' => $this->kr_kode,
            'kn.jr_id' => $this->jr_id,

            'nb_tgs1' => $this->nb_tgs1,
            'nb_tgs2' => $this->nb_tgs2,
            'nb_tgs3' => $this->nb_tgs3,
            'nb_tambahan' => $this->nb_tambahan,
            'nb_quis' => $this->nb_quis,
            'nb_uts' => $this->nb_uts,
            'nb_uas' => $this->nb_uas,
            'B' => $this->B,
            'C' => $this->C,
            'D' => $this->D,
            'E' => $this->E,
        ]);

        $query->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['=', '(select count(*) from tbl_jadwal where bn_id=tbl_bobot_nilai.id)', $this->tot])
            ->andFilterWhere(['=', 'ds_nidn', $this->ds_nidn]);

       //die($query->createCommand()->getRawSql());
        //var_dump($dataProvider->getModels());die();
        return $dataProvider;
    }



}
