<?php

namespace app\modules\transkrip\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transkrip\models\Vakasi;

/**
 * VakasiSearch represents the model behind the search form about `app\modules\transkrip\models\Vakasi`.
 */
class VakasiSearch extends Vakasi
{

    public function rules()
    {
        return [
            [['id', 'jdwl_id', 'tgs1', 'tgs2', 'tgs3', 'quis', 'uts', 'uas'], 'integer'],
            [[
			'tgl',
			'jdwl_kls','jdwl_hari','jdwl_masuk','jdwl_keluar',"mtk_nama",
			'ds_nidn','mtk_kode',
			'jr_id','pr_kode','kr_kode','jr_id','jr_jenjang','jr_nama','pr_nama','ds_nm','jadwal',			
			], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = Vakasi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jdwl_id' => $this->jdwl_id,
            'tgs1' => $this->tgs1,
            'tgs2' => $this->tgs2,
            'tgs3' => $this->tgs3,
            'quis' => $this->quis,
            'uts' => $this->uts,
            'uas' => $this->uas,
            'tgl' => $this->tgl,
        ]);

        $query->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }


    public function detail($params,$kon='',$order=''){
        $query = Vakasi::find()
		->select([
			'tbl_vakasi.*',
			
			'jd.jdwl_kls','jd.jdwl_hari','jd.jdwl_masuk','jd.jdwl_keluar',"concat('[',bn.mtk_kode,'] ',mtk_nama) mtk_nama",
			'bn.ds_nidn','bn.mtk_kode',
			'kl.jr_id','kl.pr_kode','kl.kr_kode','jr.jr_id','jr.jr_jenjang','jr_nama','pr_nama','ds_nm',
			'jadwal'=>"concat(jd.jdwl_masuk,'-',jd.jdwl_keluar)",
		])
		->innerJoin('tbl_jadwal jd','jd.jdwl_id=tbl_vakasi.jdwl_id')
		->innerJoin('tbl_bobot_nilai bn','bn.id=jd.bn_id')
		->innerJoin('tbl_dosen ds','ds.ds_id=bn.ds_nidn')
		->innerJoin('tbl_kalender kl','bn.kln_id=kl.kln_id')
		->innerJoin('tbl_jurusan jr','jr.jr_id=kl.jr_id')
		->innerJoin('tbl_program pr','pr.pr_kode=kl.pr_kode')
        ->innerJoin('tbl_matkul mk','mk.mtk_kode = bn.mtk_kode')                                 		
		->where("(
				(kl.RStat is null or kl.RStat='0') or
				(bn.RStat is null or bn.RStat='0') or
				(ds.RStat is null or ds.RStat='0') or
				(jr.RStat is null or jr.RStat='0') or
				(pr.RStat is null or pr.RStat='0') or
				(jd.RStat is null or jd.RStat='0')
			)");
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy([ 
				'jr.jr_jenjang'=>SORT_DESC,
				'jr.jr_nama'=>SORT_ASC,
				'pr.pr_kode'=>SORT_ASC,
				'jdwl_hari'=>SORT_ASC,
				'jdwl_masuk'=>SORT_ASC,
			]);	
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'jdwl_id' => $this->jdwl_id,
            'tgs1' => $this->tgs1,
            'tgs2' => $this->tgs2,
            'tgs3' => $this->tgs3,
            'quis' => $this->quis,
            'uts' => $this->uts,
            'uas' => $this->uas,
            'tgl' => $this->tgl,

			'jr.jr_id'=>$this->jr_id,
			'pr.pr_kode'=>$this->pr_kode,
			'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query->andFilterWhere(['like', 'RStat', $this->RStat])
		->andFilterWhere(['like', "concat('[',bn.mtk_kode,'] ',mtk_nama)", $this->mtk_nama])
		->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
		->andFilterWhere(['like', 'jadwal', $this->jadwal])
		->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
		->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
		->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
		->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
		->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
		->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls])

		
		;

        return $dataProvider;
    }


}
