<?php

namespace app\modules\test\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\test\models\THead;

/**
 * THeadSearch represents the model behind the search form about `app\modules\test\models\THead`.
 */
class THeadSearch extends THead
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'no_urut'], 'integer'],
            [['kode', 'npm', 'tgl_cetak', 'tgl_lulus', 'predikat', 'pejabat1', 'pejabat2', 'skripsi_indo', 'skripsi_end'], 'safe'],
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
        $query = THead::find();

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
            'no_urut' => $this->no_urut,
            'tgl_cetak' => $this->tgl_cetak,
            'tgl_lulus' => $this->tgl_lulus,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'npm', $this->npm])
            ->andFilterWhere(['like', 'predikat', $this->predikat])
            ->andFilterWhere(['like', 'pejabat1', $this->pejabat1])
            ->andFilterWhere(['like', 'pejabat2', $this->pejabat2])
            ->andFilterWhere(['like', 'skripsi_indo', $this->skripsi_indo])
            ->andFilterWhere(['like', 'skripsi_end', $this->skripsi_end]);

        return $dataProvider;
    }
}
