<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nilai".
 *
 * @property integer $ID
 * @property string $Tgl
 * @property string $NPM
 * @property string $Jenis
 * @property string $Semester
 * @property string $KodeMk
 * @property integer $SKS
 * @property string $Grade
 * @property string $NGrade
 * @property string $KrKode
 * @property string $MkNm_
 */
class Nilai extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nilai';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Tgl'], 'safe'],
            [['NPM', 'KodeMk', 'Grade'], 'required'],
            [['NPM', 'Jenis', 'Semester', 'KodeMk', 'Grade', 'KrKode', 'MkNm_'], 'string'],
            [['SKS'], 'integer'],
            [['NGrade'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Tgl' => 'Tgl',
            'NPM' => 'Npm',
            'Jenis' => 'Jenis',
            'Semester' => 'Semester',
            'KodeMk' => 'Kode Mk',
            'SKS' => 'Sks',
            'Grade' => 'Grade',
            'NGrade' => 'Ngrade',
            'KrKode' => 'Kr Kode',
            'MkNm_' => 'Mk Nm',
        ];
    }
}
