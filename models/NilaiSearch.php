<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Nilai;

/**
 * NilaiSearch represents the model behind the search form about `app\models\Nilai`.
 */
class NilaiSearch extends Nilai
{
    public function rules()
    {
        return [
            [['ID', 'SKS'], 'integer'],
            [['Tgl', 'NPM', 'Jenis', 'Semester', 'KodeMk', 'Grade', 'KrKode', 'MkNm_'], 'safe'],
            [['NGrade'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Nilai::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'Tgl' => $this->Tgl,
            'SKS' => $this->SKS,
            'NGrade' => $this->NGrade,
        ]);

        $query->andFilterWhere(['like', 'NPM', $this->NPM])
            ->andFilterWhere(['like', 'Jenis', $this->Jenis])
            ->andFilterWhere(['like', 'Semester', $this->Semester])
            ->andFilterWhere(['like', 'KodeMk', $this->KodeMk])
            ->andFilterWhere(['like', 'Grade', $this->Grade])
            ->andFilterWhere(['like', 'KrKode', $this->KrKode])
            ->andFilterWhere(['like', 'MkNm_', $this->MkNm_]);

        return $dataProvider;
    }
}
