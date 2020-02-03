<?php

namespace app\models;

use Yii;
use Yii\db\Query;

/**
 * This is the model class for table "tracer_jawaban".
 *
 * @property integer $tracer_id
 * @property integer $pertanyaan_id
 * @property integer $jawaban_id
 * @property string $ket
 * @property integer $id
 */
class TracerJawaban extends \yii\db\ActiveRecord
{
    public $pertanyaan_kode, $pertanyaan, $jawaban, $ket;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracer_jawaban';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tracer_id', 'pertanyaan_id'], 'required'],
            [['tracer_id', 'jawaban_id'], 'integer'],
            [['ket','jawaban_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tracer_id' => 'Tracer ID',
            'pertanyaan_id' => 'Pertanyaan ID',
            'jawaban_id' => 'Jawaban ID',
            'ket' => 'Ket',
            'id' => 'ID',
        ];
    }

    public static function getJawaban($id){
        $query = Yii::$app->getDb();
        $command = $query->createCommand("exec dbo.testJawaban ".$id);
        return $command->queryAll();
    }
}