<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogFingers;

/**
 * LogFingersSearch represents the model behind the search form about `app\models\LogFingers`.
 */
class LogFingersSearch extends LogFingers
{
    public function rules()
    {
        return [
            [['Id', 'fid'], 'integer'],
            [['tgl', 'cat', 'ket'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LogFingers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'fid' => $this->fid,
            'tgl' => $this->tgl,
        ]);

        $query->andFilterWhere(['like', 'cat', $this->cat])
            ->andFilterWhere(['like', 'ket', $this->ket]);

        return $dataProvider;
    }
}
