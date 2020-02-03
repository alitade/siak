<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KonsultanProgram;

/**
 * KonsultanProgramSearch represents the model behind the search form about `app\models\KonsultanProgram`.
 */
class KonsultanProgramSearch extends KonsultanProgram
{
    public function rules()
    {
        return [
            [['konsultan_id', 'program_id', 'jurusan_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = KonsultanProgram::find();
        $dataProvider = new ActiveDataProvider(['query' => $query,]);
        if (!($this->load($params) && $this->validate())) {return $dataProvider;}

        $query->andFilterWhere([
            'konsultan_id' => $this->konsultan_id,
            'program_id' => $this->program_id,
            'jurusan_id' => $this->jurusan_id,
        ]);

        return $dataProvider;
    }

    public function program($params,$id){
        $query = KonsultanProgram::find()->where(['konsultan_id'=>$id]);
        $dataProvider = new ActiveDataProvider(['query' => $query,]);
        if (!($this->load($params) && $this->validate())) {return $dataProvider;}

        $query->andFilterWhere([
            'konsultan_id' => $this->konsultan_id,
            'program_id' => $this->program_id,
            'jurusan_id' => $this->jurusan_id,
        ]);

        return $dataProvider;
    }

}
