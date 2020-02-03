<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogTransaksi;

/**
 * LogTransaksiSearch represents the model behind the search form about `app\models\LogTransaksi`.
 */
class LogTransaksiSearch extends LogTransaksi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'tb'], 'integer'],
            [['ip4', 'ip6', 'user_agent', 'tgl', 'ket', 'kode', 'pk', 'aktifitas'], 'safe'],
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
    public function search($params,$kon=''){

        $query = LogTransaksi::find();
        if($kon!=''){$query->where($kon);}
        $query->orderBy(['tgl'=>SORT_DESC]);


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
            'user_id' => $this->user_id,
            'tgl' => $this->tgl,
            'tb' => $this->tb,
        ]);

        $query->andFilterWhere(['like', 'ip4', $this->ip4])
            ->andFilterWhere(['like', 'ip6', $this->ip6])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'pk', $this->pk])
            ->andFilterWhere(['like', 'aktifitas', $this->aktifitas]);

        return $dataProvider;
    }
}
