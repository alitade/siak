<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JadwalTmp;

/**
 * JadwalTmpSearch represents the model behind the search form about `app\models\JadwalTmp`.
 */
class JadwalTmpSearch extends JadwalTmp
{
    public function rules()
    {
        return [
            [['id', 'jdwl_id'], 'integer'],
            [['ds_nidn', 'rg_kode', 'jdwl_hari', 'jdwl_keluar', 'jdwl_masuk'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = JadwalTmp::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jdwl_id' => $this->jdwl_id,
        ]);

        $query->andFilterWhere(['like', 'ds_nidn', $this->ds_nidn])
            ->andFilterWhere(['like', 'rg_kode', $this->rg_kode])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk]);

        return $dataProvider;
    }
}
