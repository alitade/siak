<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jurusan;

/**
 * JurusanSearch represents the model behind the search form about `app\models\Jurusan`.
 */
class JurusanSearch extends Jurusan
{
    public function rules()
    {
        return [
            [['jr_id', 'fk_id', 'jr_kode_nim', 'jr_nama', 'jr_jenjang', 'jr_stat','jr_head'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Jurusan::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'fk_id', $this->fk_id])
            ->andFilterWhere(['like', 'jr_kode_nim', $this->jr_kode_nim])
            ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
            ->andFilterWhere(['like', 'jr_head', $this->jr_head])
            ->andFilterWhere(['like', 'jr_jenjang', $this->jr_jenjang])
            ->andFilterWhere(['like', 'jr_stat', $this->jr_stat]);

        return $dataProvider;
    }
}
