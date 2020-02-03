<?php

namespace app\modules\transkrip\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transkrip\models\Nilai;

/**
 * NilaiSearch represents the model behind the search form about `app\modules\transkrip\models\Nilai`.
 */
class NilaiSearch extends Nilai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'semester', 'sks'], 'integer'],
            [['npm', 'kode_mk', 'nama_mk', 'huruf', 'tahun', 'tgl_input', 'stat', 'kat'], 'safe'],
            [['nilai'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Nilai::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'semester' => $this->semester,
            'sks' => $this->sks,
            'nilai' => $this->nilai,
            'tgl_input' => $this->tgl_input,
        ]);

        $query->andFilterWhere(['like', 'npm', $this->npm])
            ->andFilterWhere(['like', 'kode_mk', $this->kode_mk])
            ->andFilterWhere(['like', 'nama_mk', $this->nama_mk])
            ->andFilterWhere(['like', 'huruf', $this->huruf])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'stat', $this->stat])
            ->andFilterWhere(['like', 'kat', $this->kat]);

        return $dataProvider;
    }
}
