<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GroupMatkul;

/**
 * MatkulSearch represents the model behind the search form about `app\models\Matkul`.
 */
class GroupMatkulSearch extends GroupMatkul
{

    public function rules()
    {
        return [
            [['kode', 'nama', 'ska',], 'required'],
            [['kode', 'nama',], 'string'],
            [['kode', 'nama',], 'filter','filter'=>'trim'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='')
    {
        $query = GroupMatkul::find()
		->select([
				"groupmk.kode",
				"groupmk.nama",
				"groupmk.sks",
				'total'=>'count(distinct gd.KodeMk)'
			]
		)
		->leftJoin('groupmkdetail gd','gd.kodeG=groupmk.kode')
		->groupBy(['groupmk.kode','groupmk.nama','groupmk.sks']);
		;
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
            'sks' => $this->sks,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'nama', $this->nama]);
        return $dataProvider;
    }
}
