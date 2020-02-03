<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ruang;

/**
 * RuangSearch represents the model behind the search form about `app\models\Ruang`.
 */
class RuangSearch extends Ruang
{
    public function rules()
    {
        return [
            [['rg_kode', 'rg_nama'], 'safe'],
            [['kapasitas', 'IdGedung'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Ruang::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kapasitas' => $this->kapasitas,
            'IdGedung' => $this->IdGedung,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])
            ->andFilterWhere(['like', 'rg_nama', $this->rg_nama]);

        return $dataProvider;
    }
}
