<?php

namespace app\models;

use Yii;
use Yii\db\Query;

/**
 * This is the model class for table "tracer_kuisioner".
 *
 * @property integer $id
 * @property integer $tracer_id
 * @property string $kuisioner_id
 * @property integer $jawaban
 */
class TracerKuisioner extends \yii\db\ActiveRecord
{
    public $kode_pertanyaan, $pertanyaan, $A, $B;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracer_kuisioner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tracer_id', 'kuisioner_id'], 'required'],
            [['tracer_id', 'jawaban'], 'integer'],
            [['kuisioner_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tracer_id' => 'Tracer ID',
            'kuisioner_id' => 'Kuisioner ID',
            'jawaban' => 'Jawaban',
        ];
    }

    public static function getJawaban($id){
        $query = Yii::$app->getDb();
        $command = $query->createCommand("select * from (
                                            select tk.tracer_id, tjk.kode_pertanyaan, tjk.kode_kuisioner, tjk.pertanyaan, tk.jawaban
                                            from tracer_kuisioner tk join tracerjenis_kuisioner tjk on (tk.kuisioner_id=tjk.pemodelan)
                                            where tk.tracer_id=".$id."
                                        ) as T
                                        PIVOT
                                        (
                                            sum(jawaban) for kode_kuisioner IN ([A],[B])
                                        ) as PVT");   
        return $command->queryAll();
    }
}
