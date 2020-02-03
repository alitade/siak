<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProgramDaftar;

/**
 * ProgramDaftarSearch represents the model behind the search form about `app\models\ProgramDaftar`.
 */
class ProgramDaftarSearch extends ProgramDaftar
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['program_id', 'nama_program', 'identitas_id', 'aktif', 'kode_nim', 'group', 'party', 'kode'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProgramDaftar::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'program_id', $this->program_id])
            ->andFilterWhere(['like', 'nama_program', $this->nama_program])
            ->andFilterWhere(['like', 'identitas_id', $this->identitas_id])
            ->andFilterWhere(['like', 'aktif', $this->aktif])
            ->andFilterWhere(['like', 'kode_nim', $this->kode_nim])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'party', $this->party])
            ->andFilterWhere(['like', 'kode', $this->kode]);

        return $dataProvider;
    }
}
