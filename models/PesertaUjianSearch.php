<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PesertaUjian;

/**
 * PesertaUjianSearch represents the model behind the search form about `app\models\PesertaUjian`.
 */
class PesertaUjianSearch extends PesertaUjian
{
    public function rules()
    {
        return [
            [['Id', 'IdUjian', 'Krs_id', 'jdwl_id_'], 'integer'],
            [['RStat'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PesertaUjian::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'IdUjian' => $this->IdUjian,
            'Krs_id' => $this->Krs_id,
            'jdwl_id_' => $this->jdwl_id_,
        ]);

        $query->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }
}
