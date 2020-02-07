<?php
namespace app\components;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class vdComponent extends Component{
    public function vd($tipe=1){
        $sql="select id,kd,nil from aturan WHERE parent is null and aktif=1";
        $sql =Yii::$app->db->createCommand($sql)->queryAll();
        $arr=[];
        foreach ($sql as $d){
            if($tipe==2){
                $arr[$d['id']]=$d['nil'];
            }else{
                $arr[$d['kd']]=$d['nil'];
            }

        }
        return $arr;
    }

    public function vid($id='',$tipe='1',$aktif=false){
        $kon="";
        $arr=[];
        if($tipe==2){
            if($id!=''){$kon="and kd=$id";}
            $sql="select id,kd,nil, isnull(aktif,0) aktif from aturan WHERE parent is null $kon";
            $sql =Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($sql as $d){
                if($aktif){
                    if($d['aktif']==1){if($id && $id==$d['kd']){return $d['nil'];}$arr[$d['kd']]=$d['nil'];}
                }else{
                    if($id && $id==$d['kd']){return $d['nil'];}
                    $arr[$d['kd']]=$d['nil'];
                }
            }
        }else{
            if($id!=''){$kon="and id=$id";}
            $sql="select id,kd,nil, isnull(aktif,0) aktif from aturan WHERE parent is null $kon";
            $sql =Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($sql as $d){
                if($aktif){
                    if($d['aktif']==1){if($id && $id==$d['id']){return $d['nil'];}$arr[$d['id']]=$d['nil'];}
                }else{
                    if($id && $id==$d['id']){return $d['nil'];}
                    $arr[$d['id']]=$d['nil'];
                }
            }
        }
        return $arr;
    }
}

?>
