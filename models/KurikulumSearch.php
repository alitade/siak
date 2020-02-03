<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kurikulum;

/**
 * KurikulumSearch represents the model behind the search form about `app\models\Kurikulum`.
 */
class KurikulumSearch extends Kurikulum
{
    public function rules()
    {
        return [
            [['kr_kode', 'kr_nama'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Kurikulum::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'kr_nama', $this->kr_nama]);

        return $dataProvider;
    }
}
