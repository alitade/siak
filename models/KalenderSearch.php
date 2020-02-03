<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kalender;

/**
 * KalenderSearch represents the model behind the search form about `app\models\Kalender`.
 */
class KalenderSearch extends Kalender
{
    public function rules()
    {
        return [
            [['kln_id', 'kln_krs_lama', 'kln_uts_lama', 'kln_uas_lama'], 'integer'],
            [['kr_kode', 'jr_id', 'pr_kode', 'kln_krs', 'kln_masuk', 'kln_uts', 'kln_uas', 'kln_stat', 'kln_sesi'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='')
    {
        $query = Kalender::find()->where(" (RStat='0' or RStat is null) ");
		$query->orderBy(['substring(kr_kode,2,4)'=>SORT_DESC,'substring(kr_kode,1,1)'=>SORT_DESC,]);
		if($kon!=''){
			$query ->andWhere($kon);
		}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kln_id' => $this->kln_id,
            'kln_krs' => $this->kln_krs,
            'kln_masuk' => $this->kln_masuk,
            'kln_uts' => $this->kln_uts,
            'kln_uas' => $this->kln_uas,
            'kln_krs_lama' => $this->kln_krs_lama,
            'kln_uts_lama' => $this->kln_uts_lama,
            'kln_uas_lama' => $this->kln_uas_lama,
        ]);

        $query->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'kln_stat', $this->kln_stat])
            ->andFilterWhere(['like', 'kln_sesi', $this->kln_sesi]);

        return $dataProvider;
    }
}
