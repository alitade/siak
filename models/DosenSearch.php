<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dosen;

/**
 * DosenSearch represents the model behind the search form about `app\models\Dosen`.
 */
class DosenSearch extends Dosen
{

	public $jr_id;
	public $pr_kode;
	public $tot;
	public $mhs_nim;
	public $mhs_angkatan;


    public function rules()
    {
        return [
            [['ds_id', 'ds_tipe','id_tipe'], 'integer'],
            [['jr_id','pr_kode','tot','ds_nidn', 'ds_user', 'ds_pass', 'ds_pass_kode', 'ds_nm', 'ds_kat', 'ds_email'
			, 'RStat'
			, 'mhs_angkatan'
			], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='')
    {
        $query = Dosen::find()
		->where("(RStat is null or RStat='0')");
		
		if($kon!=''){
			$query->andWhere($kon);
		}


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ds_id' => $this->ds_id,
            'ds_tipe' => $this->ds_tipe,
            'id_tipe' => $this->id_tipe,
        ]);

        $query->andFilterWhere(['like', 'ds_nidn', $this->ds_nidn])
            ->andFilterWhere(['like', 'ds_user', $this->ds_user])
            ->andFilterWhere(['like', 'ds_pass', $this->ds_pass])
            ->andFilterWhere(['like', 'ds_pass_kode', $this->ds_pass_kode])
            ->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
            ->andFilterWhere(['like', 'ds_kat', $this->ds_kat])
            ->andFilterWhere(['like', 'ds_email', $this->ds_email])
            ->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }

    public function cari($params,$kon='')
    {
        $params = Yii::$app->request->getQueryParams();

        $query =  DosenSearch::find()->select(
			' ds_id,ds_nidn, count(mhs.mhs_nim) tot ,mhs.jr_id,mhs.pr_kode,mhs_angkatan'
		)
        ->innerJoin('tbl_mahasiswa mhs','mhs.ds_wali = tbl_dosen.ds_id')
		->groupBy('ds_id,ds_nidn,mhs.pr_kode,mhs.jr_id,mhs_angkatan')
        //->where(" (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null) and tbl_bobot_nilai.ds_nidn = '".Yii::$app->user->identity->idku."' ")
		;
		if($kon!=''){
			$query->andWhere($kon);
		}



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

     
         $dataProvider->setSort([
        'attributes' => [
            
            'tot' => [
                'asc' => ['tot' => SORT_ASC],
                'desc' => ['tot' => SORT_DESC],
                'label' => 'Total Mahasiswa',
                'default' => SORT_ASC
            ],

             'jr_id' => [
                'asc' => ['jr_id' => SORT_ASC],
                'desc' => ['jr_id' => SORT_DESC],
                'label' => 'jr_id',
                'default' => SORT_ASC
            ],
             

             'pr_kode' => [
                'asc' => ['pr_kode' => SORT_ASC],
                'desc' => ['pr_kode' => SORT_DESC],
                'label' => 'pr_kode',
                'default' => SORT_ASC
            ],
             
        ]
        ]);

        

        if (!($this->load($params) && $this->validate())) {
            //$query->andFilterWhere(['=','jr_id', 'UDIN']);
            return $dataProvider;
        }

        

        $query->andFilterWhere(['=', 'mhs.jr_id', $this->jr_id])
            ->andFilterWhere(['=', 'ds_id', $this->ds_id])
            ->andFilterWhere(['=', 'pr_kode', $this->pr_kode])
            ->andFilterWhere(['=', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'ds_nidn', $this->ds_nidn]);
			
			if(!empty($this->tot)){
				$query->having('count(mhs.mhs_nim)= :tot')
				->addParams([':tot'=>$this->tot]);
			}

			
        return $dataProvider;
    }


}
