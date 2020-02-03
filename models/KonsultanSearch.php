<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Konsultan;

/**
 * KonsultanSearch represents the model behind the search form about `app\models\Konsultan`.
 */
class KonsultanSearch extends Konsultan
{
    public function rules()
    {
        return [
            [['kode', 'vendor', 'email', 'tlp', 'pic'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Konsultan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'tlp', $this->tlp])
            ->andFilterWhere(['like', 'pic', $this->pic]);

        return $dataProvider;
    }
}
