<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "program".
 *
 * @property integer $id
 * @property string $program_id
 * @property string $nama_program
 * @property string $identitas_id
 * @property string $aktif
 * @property string $kode_nim
 * @property string $group
 * @property integer $pr_id
 * @property integer $jr_id
 * @property string $party
 * @property integer $konsultan_id
 */
class ProgramKu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pr_id', 'jr_id', 'konsultan_id'], 'integer'],
            [['program_id', 'nama_program', 'identitas_id', 'aktif', 'kode_nim', 'group', 'party'], 'string'],
            [['aktif', 'pr_id', 'jr_id', 'konsultan_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_id' => 'Program ID',
            'nama_program' => 'Nama Program',
            'identitas_id' => 'Identitas ID',
            'aktif' => 'Aktif',
            'kode_nim' => 'Kode Nim',
            'group' => 'Group',
            'pr_id' => 'Pr ID',
            'jr_id' => 'Jr ID',
            'party' => 'Party',
            'konsultan_id' => 'Konsultan ID',
        ];
    }
}
