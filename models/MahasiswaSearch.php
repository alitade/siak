<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\Mahasiswa;

/**
 * MahasiswaSearch represents the model behind the search form about `app\models\Mahasiswa`.
 */
class MahasiswaSearch extends Mahasiswa{

    public function rules()
    {
        return [
            [[
                'mhs_nim', 'mhs_pass', 'mhs_pass_kode', 'mhs_angkatan', 'jr_id', 'pr_kode', 'mhs_stat',
                'ds_wali','Nama','ws','thn','reg','tipe','krsHeadApp','reg','krs',
            ], 'safe'],
            [['mhs_tipe'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon=''){
        $db = Yii::$app->db1;
        $db2 = Yii::$app->db2;
        //print_r($db);
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $transkrip = Funct::getDsnAttribute('dbname', $db2->dsn);
        if(!$transkrip){$transkrip= Funct::getDsnAttribute('Database', $db2->dsn);}
        $query = Mahasiswa::find()
            ->select([

//			'tbl_mahasiswa.jr_id','tbl_mahasiswa.pr_kode','tbl_mahasiswa.mhs_nim'
//			,'tbl_mahasiswa.mhs_angkatan','tbl_mahasiswa.ds_wali'

                'tbl_mahasiswa.*'
                ,'p.Nama'
                ,'thn'=>'s.kurikulum'
                ,'ws'=>'iif(hd.npm is not null,1,0)'
            ])
            ->innerJoin("tbl_jurusan jr"," jr.jr_id=tbl_mahasiswa.jr_id ")
            ->innerJoin("tbl_program pr"," pr.pr_kode=tbl_mahasiswa.pr_kode")
            ->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = tbl_mahasiswa.mhs_nim ")
            ->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
            ->join('LEFT JOIN',"$transkrip.dbo.t_head hd"," hd.npm=s.nim ")
            ->join('LEFT JOIN',"tbl_dosen ds"," ds.ds_id=tbl_mahasiswa.ds_wali")

            ->where("(tbl_mahasiswa.RStat='0' or tbl_mahasiswa.RStat is null) and (mhs_nim is not null)");
        if($kon!=''){
            $query->andWhere($kon);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Nama' => [
                    'asc' => ['p.Nama' => SORT_ASC],
                    'desc' => ['p.Nama' => SORT_DESC],
                    'label' => 'Nama',
                    'default' => SORT_ASC
                ],
                'mhs_nim' => [
                    'asc' => ['mhs_nim' => SORT_ASC],
                    'desc' => ['mhs_nim' => SORT_DESC],
                    'label' => 'NIM',
                    'default' => SORT_DESC
                ],
                'jr_id' => [
                    'asc' => ['jr.jr_nama' => SORT_ASC],
                    'desc' => ['jr.jr_nama' => SORT_DESC],
                    'label' => 'Jurusan',
                    'default' => SORT_DESC
                ],
                'pr_kode' => [
                    'asc' => ['pr.pr_nama' => SORT_ASC],
                    'desc' => ['pr.pr_nama' => SORT_DESC],
                    'label' => 'Program',
                    'default' => SORT_DESC
                ],
                'mhs_angkatan' => [
                    'asc' => ['mhs_angkatan' => SORT_ASC],
                    'desc' => ['mhs_angkatan' => SORT_DESC],
                    'label' => 'Angkatan',
                    'default' => SORT_DESC
                ],
                'ds_wali' => [
                    'asc' => ['ds.ds_nm' => SORT_ASC],
                    'desc' => ['ds.ds_nm' => SORT_DESC],
                    'label' => 'Dosen Wali',
                    'default' => SORT_DESC
                ],
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mhs_tipe' => $this->mhs_tipe,
            'iif(hd.npm is not null,1,0)' => $this->ws,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'p.Nama', $this->Nama])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'tbl_mahasiswa.jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'tbl_mahasiswa.pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['=', 'tbl_mahasiswa.ds_wali', $this->ds_wali])
            ->andFilterWhere(['like', 's.kurikulum', $this->thn]);
        return $dataProvider;
    }

    public function aktif($params,$kon=''){
        $db = Yii::$app->db1;
        $db2 = Yii::$app->db2;
        //print_r($db);
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $transkrip = Funct::getDsnAttribute('dbname', $db2->dsn);
        if(!$transkrip){$transkrip= Funct::getDsnAttribute('Database', $db2->dsn);}
        $query = Mahasiswa::find()
            ->select([
                'tbl_mahasiswa.*'
                ,'p.Nama'
                ,'thn'=>'s.kurikulum'
                ,'ws'=>'iif(hd.npm is not null,1,0)'

            ])
            ->innerJoin("tbl_jurusan jr"," jr.jr_id=tbl_mahasiswa.jr_id ")
            ->innerJoin("tbl_program pr"," pr.pr_kode=tbl_mahasiswa.pr_kode")
            ->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = tbl_mahasiswa.mhs_nim ")
            ->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
            ->join('LEFT JOIN',"$transkrip.dbo.t_head hd"," hd.npm=s.nim ")
            ->join('LEFT JOIN',"tbl_dosen ds"," ds.ds_id=tbl_mahasiswa.ds_wali");
        if($kon!=''){$query->where($kon);}
        $query->andWhere("isnull(tbl_mahasiswa.RStat,0)=0 and hd.npm is null and tbl_mahasiswa.mhs_nim is not null ");
        $query->orderBy(['jr.jr_id'=>SORT_ASC,'pr.pr_kode'=>SORT_ASC,'mhs_nim'=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30,],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Nama' => [
                    'asc' => ['p.Nama' => SORT_ASC],
                    'desc' => ['p.Nama' => SORT_DESC],
                    'label' => 'Nama',
                    'default' => SORT_ASC
                ],
                'mhs_nim' => [
                    'asc' => ['mhs_nim' => SORT_ASC],
                    'desc' => ['mhs_nim' => SORT_DESC],
                    'label' => 'NIM',
                    'default' => SORT_DESC
                ],
                'jr_id' => [
                    'asc' => ['jr.jr_nama' => SORT_ASC],
                    'desc' => ['jr.jr_nama' => SORT_DESC],
                    'label' => 'Jurusan',
                    'default' => SORT_DESC
                ],
                'pr_kode' => [
                    'asc' => ['pr.pr_nama' => SORT_ASC],
                    'desc' => ['pr.pr_nama' => SORT_DESC],
                    'label' => 'Program',
                    'default' => SORT_DESC
                ],
                'mhs_angkatan' => [
                    'asc' => ['mhs_angkatan' => SORT_ASC],
                    'desc' => ['mhs_angkatan' => SORT_DESC],
                    'label' => 'Angkatan',
                    'default' => SORT_DESC
                ],
                'ds_wali' => [
                    'asc' => ['ds.ds_nm' => SORT_ASC],
                    'desc' => ['ds.ds_nm' => SORT_DESC],
                    'label' => 'Dosen Wali',
                    'default' => SORT_DESC
                ],
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            #$query->where("1=0");
            return $dataProvider;
        }

        $query->andFilterWhere([
            'iif(hd.npm is not null,1,0)' => $this->ws,
            'iif(tkh.nim is null,0,1)'=>$this->krs,
            'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'=>$this->reg,
            "iif(s.keterangan is null or s.keterangan='',1,2)"=>$this->tipe,
            "tbl_mahasiswa.pr_kode"=>$this->pr_kode,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'p.Nama', $this->Nama])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'tbl_mahasiswa.jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'tbl_mahasiswa.pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['=', 'tbl_mahasiswa.ds_wali', $this->ds_wali])
            ->andFilterWhere(['like', 's.kurikulum', $this->thn]);
        return $dataProvider;
    }

    public function cari($params){
 
        $query = Mahasiswa::find()->with('jr')->where(" (RStat='0' or RStat is null) ");

        $query->joinWith(['jr','jr.mhs']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

     
         $dataProvider->setSort([
        'attributes' => [
            
            'fullName' => [
                'asc' => ['mhs.nim' => SORT_ASC],
                'desc' => ['mhs.nim' => SORT_DESC],
                'label' => 'Nama Lengkap',
                'default' => SORT_ASC
            ],
             
        ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mhs_tipe' => $this->mhs_tipe,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'mhs.nim', $this->fullName])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['like', 'ds_wali', $this->ds_wali]);
 

        return $dataProvider;
    }

    public function perwalian($params,$kon='',$kr){
        $db = Yii::$app->db1;
        $db2 = Yii::$app->db2;
        //print_r($db);
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $transkrip = Funct::getDsnAttribute('dbname', $db2->dsn);
        if(!$transkrip){$transkrip= Funct::getDsnAttribute('Database', $db2->dsn);}

        $query = Mahasiswa::find()
            ->select([
//			'tbl_mahasiswa.jr_id','tbl_mahasiswa.pr_kode','tbl_mahasiswa.mhs_nim'
//			,'tbl_mahasiswa.mhs_angkatan','tbl_mahasiswa.ds_wali'
                'tbl_mahasiswa.*'
                ,'p.Nama'
                ,'thn'=>'s.kurikulum'
                ,'ws'=>'iif(hd.npm is not null,1,0)'
                ,'regis'=>'r.tahun'
                ,'reg'=>'iif(r.nim is not null,1,0)'

            ])
            ->innerJoin("tbl_jurusan jr"," jr.jr_id=tbl_mahasiswa.jr_id ")
            ->innerJoin("tbl_program pr"," pr.pr_kode=tbl_mahasiswa.pr_kode")
            ->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = tbl_mahasiswa.mhs_nim ")
            ->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
            ->join('LEFT JOIN',"$keuangan.dbo.regmhs r"," r.nim=s.nim and r.tahun='$kr'")
            ->join('LEFT JOIN',"$transkrip.dbo.t_head hd"," hd.npm=s.nim ")
            ->join('LEFT JOIN',"tbl_dosen ds"," ds.ds_id=tbl_mahasiswa.ds_wali")

            ->where("(tbl_mahasiswa.RStat='0' or tbl_mahasiswa.RStat is null) and (mhs_nim is not null)");
        if($kon!=''){
            $query->andWhere($kon);
        }

        $query->orderBy([
            'tbl_mahasiswa.jr_id'=>SORT_ASC,
            'tbl_mahasiswa.pr_kode'=>SORT_ASC,
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'Nama' => [
                    'asc' => ['p.Nama' => SORT_ASC],
                    'desc' => ['p.Nama' => SORT_DESC],
                    'label' => 'Nama',
                    'default' => SORT_ASC
                ],
                'mhs_nim' => [
                    'asc' => ['mhs_nim' => SORT_ASC],
                    'desc' => ['mhs_nim' => SORT_DESC],
                    'label' => 'NIM',
                    'default' => SORT_DESC
                ],
                'jr_id' => [
                    'asc' => ['jr.jr_nama' => SORT_ASC],
                    'desc' => ['jr.jr_nama' => SORT_DESC],
                    'label' => 'Jurusan',
                    'default' => SORT_DESC
                ],
                'pr_kode' => [
                    'asc' => ['pr.pr_nama' => SORT_ASC],
                    'desc' => ['pr.pr_nama' => SORT_DESC],
                    'label' => 'Program',
                    'default' => SORT_DESC
                ],
                'mhs_angkatan' => [
                    'asc' => ['mhs_angkatan' => SORT_ASC],
                    'desc' => ['mhs_angkatan' => SORT_DESC],
                    'label' => 'Angkatan',
                    'default' => SORT_DESC
                ],
                'ds_wali' => [
                    'asc' => ['ds.ds_nm' => SORT_ASC],
                    'desc' => ['ds.ds_nm' => SORT_DESC],
                    'label' => 'Dosen Wali',
                    'default' => SORT_DESC
                ],
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            #$query->andWhere("1=0");
            return $dataProvider;

        }


        $query->andFilterWhere([
            'mhs_tipe' => $this->mhs_tipe,
            'iif(hd.npm is not null,1,0)' => $this->ws,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'p.Nama', $this->Nama])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'tbl_mahasiswa.jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'tbl_mahasiswa.pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['=', 'tbl_mahasiswa.ds_wali', $this->ds_wali])
            ->andFilterWhere(['like', 's.kurikulum', $this->thn]);
        return $dataProvider;
    }

    public function perwalianaktif($params,$wali='',$kr=''){
        $db = Yii::$app->db1;
        $db2 = Yii::$app->db2;
        //print_r($db);
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $transkrip = Funct::getDsnAttribute('dbname', $db2->dsn);
        if(!$transkrip){$transkrip= Funct::getDsnAttribute('Database', $db2->dsn);}

        #kalender akademik aktif
        $mKr    = Kalender::find()->where(" isnull(RStat,0)=0 and CAST(GETDATE() as date) BETWEEN kln_krs and dateadd(day,2,krs_akhir)")->all();
        #$mKr    = Kalender::find()->where("isnull(RStat,0)=0 and kr_kode='21516'")->all();
        $kln_id=[];
        foreach($mKr as $d){$kln_id[]=$d['kln_id'];}

        $kln_id=implode(',',$kln_id);

        $query = Mahasiswa::find()
            ->select([
                'tbl_mahasiswa.mhs_nim'
                ,'tbl_mahasiswa.mhs_angkatan'
                ,'kl.kr_kode'
                ,'tbl_mahasiswa.mk_kr'
                ,'tbl_mahasiswa.jr_id'
                ,'tbl_mahasiswa.pr_kode'
                ,'mhs_tipe'=>"iif(pd.keterangan is null or pd.keterangan='',1,2)"
                ,'Nama'=>'bd.nama'
                ,'thn'=>'pd.kurikulum'
                ,'regis'=>'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'
                ,'reg'=>'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'
                ,'krs'=>'iif(tkh.nim is null,0,1)'
                ,'krs_head'=>'tkh.id'
                ,'klnId'=>'kl.kln_id'
                ,'krsHeadApp'=>'tkh.app'
            ])
            ->innerJoin("tbl_kalender kl","( kl.jr_id=tbl_mahasiswa.jr_id 
                    and kl.pr_kode=tbl_mahasiswa.pr_kode
                    and isnull(kl.RStat,0)=0 
                    and (CAST(GETDATE() as date) BETWEEN kl.kln_krs and dateadd(day,2, kl.krs_akhir) )
                )
            ")
            ->innerJoin("tbl_jurusan jr"," (jr.jr_id=tbl_mahasiswa.jr_id) ")
            ->innerJoin("$keuangan.dbo.student pd","(pd.nim COLLATE Latin1_General_CI_AS=tbl_mahasiswa.mhs_nim)")
            ->innerJoin("$keuangan.dbo.people bd","(bd.No_Registrasi=pd.No_Registrasi)")
            ->join('LEFT JOIN',"$keuangan.dbo.regmhs r"," r.nim=pd.nim and r.tahun=kl.kr_kode")
            ->leftJoin("t_krs_head tkh","(tkh.nim=tbl_mahasiswa.mhs_nim and tkh.kr_kode=kl.kr_kode)")
            ->leftJoin("$transkrip.dbo.t_head th","(th.npm COLLATE Latin1_General_CI_AS=tbl_mahasiswa.mhs_nim)")
            /*
            ->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = tbl_mahasiswa.mhs_nim ")
            ->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
            */
            ->where("
			isnull(tbl_mahasiswa.RStat,0)=0 -- and th.npm is NULL
			".($wali!=''?" and tbl_mahasiswa.ds_wali=$wali":"")
            );

        $query->orderBy([
            'tbl_mahasiswa.jr_id'=>SORT_ASC,
            'tbl_mahasiswa.pr_kode'=>SORT_ASC,
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            #$query->andWhere("1=0");
            return $dataProvider;

        }


        $query->andFilterWhere([
            'mhs_tipe' => $this->mhs_tipe,
            'iif(tkh.nim is null,0,1)'=>$this->krs,
            'iif(hd.npm is not null,1,0)' => $this->ws,
            'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'=>$this->reg,
            "iif(pd.keterangan is null or pd.keterangan='',1,2)"=>$this->tipe,
            "tbl_mahasiswa.pr_kode"=>$this->pr_kode,
            'isnull(tkh.app,0)'=>$this->krsHeadApp,

        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'bd.Nama', $this->Nama])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'tbl_mahasiswa.mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'tbl_mahasiswa.jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['=', 'tbl_mahasiswa.ds_wali', $this->ds_wali])
            ->andFilterWhere(['like', 'pd.kurikulum', $this->thn]);
        return $dataProvider;
    }

    public function perwalianPaket($params,$kon=''){
        $db = Yii::$app->db1;
        $db2 = Yii::$app->db2;
        //print_r($db);
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $transkrip = Funct::getDsnAttribute('dbname', $db2->dsn);
        if(!$transkrip){$transkrip= Funct::getDsnAttribute('Database', $db2->dsn);}

        #kalender akademik aktif
        #$mKr    = Kalender::find()->where(" isnull(RStat,0)=0 and CAST(GETDATE() as date) BETWEEN kln_krs and krs_akhir")->all();
        $mKr    = Kalender::find()->where("isnull(RStat,0)=0 and kr_kode='21516'")->all();
        $kln_id=[];
        foreach($mKr as $d){$kln_id[]=$d['kln_id'];}

        $kln_id=implode(',',$kln_id);

        $query = Mahasiswa::find()
            ->select([
                'tbl_mahasiswa.mhs_nim'
                ,'tbl_mahasiswa.mhs_angkatan'
                ,'kl.kr_kode'
                ,'tbl_mahasiswa.mk_kr'
                ,'tbl_mahasiswa.jr_id'
                ,'tbl_mahasiswa.pr_kode'
                ,'mhs_tipe'=>"iif(pd.keterangan is null or pd.keterangan='',1,2)"
                ,'Nama'=>'bd.nama'
                ,'thn'=>'pd.kurikulum'
                ,'regis'=>'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'
                ,'reg'=>'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'
                ,'krs'=>'iif(tkh.nim is null,0,1)'
                ,'krs_head'=>'tkh.id'
                ,'klnId'=>'kl.kln_id'
                ,'krsHeadApp'=>'isnull(tkh.app,0)'
            ])
            ->innerJoin("tbl_kalender kl","( kl.jr_id=tbl_mahasiswa.jr_id 
                    and kl.pr_kode=tbl_mahasiswa.pr_kode
                    and isnull(kl.RStat,0)=0 
                    and (CAST(GETDATE() as date) BETWEEN CAST(GETDATE() as date) and  cast(dateadd(day,-1,kln_masuk) as date))
                )
            ")
            ->innerJoin("tbl_jurusan jr"," (jr.jr_id=tbl_mahasiswa.jr_id) ")
            ->innerJoin("$keuangan.dbo.student pd","(pd.nim COLLATE Latin1_General_CI_AS=tbl_mahasiswa.mhs_nim)")
            ->innerJoin("$keuangan.dbo.people bd","(bd.No_Registrasi=pd.No_Registrasi)")
            ->join('LEFT JOIN',"$keuangan.dbo.regmhs r"," r.nim=pd.nim and r.tahun=kl.kr_kode")
            ->leftJoin("t_krs_head tkh","(tkh.nim=tbl_mahasiswa.mhs_nim and tkh.kr_kode=kl.kr_kode)")
            ->leftJoin("$transkrip.dbo.t_head th","(th.npm COLLATE Latin1_General_CI_AS=tbl_mahasiswa.mhs_nim)")
            /*
            ->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = tbl_mahasiswa.mhs_nim ")
            ->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
            */
            ->where("isnull(tbl_mahasiswa.RStat,0)=0  and th.npm is NULL"
            );

        if($kon!=''){$query->andWhere($kon);}


        $query->orderBy([
            'tbl_mahasiswa.jr_id'=>SORT_ASC,
            'tbl_mahasiswa.pr_kode'=>SORT_ASC,
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            #$query->andWhere("1=0");
            return $dataProvider;

        }


        $query->andFilterWhere([
            'mhs_tipe' => $this->mhs_tipe,
            'iif(tkh.nim is null,0,1)'=>$this->krs,
            'iif(hd.npm is not null,1,0)' => $this->ws,
            'iif(isnull(r.tahun,0)<>kl.kr_kode,0,1)'=>$this->reg,
            "iif(pd.keterangan is null or pd.keterangan='',1,2)"=>$this->tipe,
            'isnull(tkh.app,0)'=>$this->krsHeadApp,
            "tbl_mahasiswa.pr_kode"=>$this->pr_kode,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'bd.Nama', $this->Nama])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'tbl_mahasiswa.mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'tbl_mahasiswa.jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['=', 'tbl_mahasiswa.ds_wali', $this->ds_wali])
            ->andFilterWhere(['like', 'pd.kurikulum', $this->thn]);
        return $dataProvider;
    }


}