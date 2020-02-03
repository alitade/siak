<?php

namespace app\models;

use Yii;

class DosenWali extends \yii\db\ActiveRecord{
    public  $dosen;

    public static function tableName(){return 'dosen_wali';}
    public static $ID=32;
    public function rules()
    {
        return [
            [['jr_id', 'ds_id'], 'required'],
            [['jr_id', 'ds_id'], 'unique','targetAttribute'=>['jr_id', 'ds_id']],
            [['jr_id', 'aktif','dosen'], 'string'],
            [['ds_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jr_id' => 'Jurusan',
            'ds_id' => 'Dosen',
            'dosen'=>'Dosen Wali',
            'aktif' => 'Aktif',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
        ];
    }

    public function getJr(){
        return $this->hasOne(Jurusan::className(), ['jr_id' => 'jr_id']);
    }

    public function getDs(){
        return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_id']);
    }

    public function getMhs(){
        $db = Yii::$app->db1;
        $db2 = Yii::$app->db2;
        //print_r($db);
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $transkrip = Funct::getDsnAttribute('dbname', $db2->dsn);
        if(!$transkrip){$transkrip= Funct::getDsnAttribute('Database', $db2->dsn);}

        return $this->hasMany(Mahasiswa::className(), ['ds_wali' => 'ds_id','jr_id'=>'jr_id'])
            #->leftJoin("$transkrip.dbo.t_head hd"," (hd.npm=tbl_mahasiswa.mhs_nim)")
            #->where("isnull(tbl_mahasiswa.RStat,0)=0 and tbl_mahasiswa.mhs_nim is not null")
            ;
    }


}
