<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vakasi;

/**
 * VakasiSearch represents the model behind the search form about `app\models\Vakasi`.
 */
class VakasiSearch extends Vakasi
{
    public function rules()
    {
        return [
            [['id', 'jdwl_id', 'tgs1', 'tgs2', 'tgs3', 'quis', 'uts', 'uas'], 'integer'],
            [['tgl', 'RStat'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
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
}
