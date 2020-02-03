<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Program;

/**
 * ProgramSearch represents the model behind the search form about `app\models\Program`.
 */
class ProgramSearch extends Program
{
    public function rules()
    {
        return [
            [['pr_kode', 'pr_nama', 'pr_nim', 'pr_stat'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Program::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
            ->andFilterWhere(['like', 'pr_nim', $this->pr_nim])
            ->andFilterWhere(['like', 'pr_stat', $this->pr_stat]);

        return $dataProvider;
    }
}
