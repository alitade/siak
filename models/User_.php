<?php
namespace app\models;
use Yii;
use yii\db\Query;


class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    
    const ADMIN     =   1;
    const FULL      =   2;
    const DOSEN     =   3;
    const PRODI     =   4;
    const MHS       =   5;
    const REKTOR    =   6;
    

    public $id;
    public $username;
    public $password;
    public $name;
    public $authKey;
    public $accessToken;
    public $role;
    public $tipe;
    public $idku;
    public $table;
     



    private static $Login;
    private static $users =[]; 

    private function getUser($username,$pass=''){            
            $password = ($pass !='')? $pass: @$_POST['LoginForm']['password'];
            $que="
            Select 'user' [table], username name, username, tipe, CAST(id AS VARCHAR(200)) idku, lower(password) password, lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2)) authKey from tbl_user
            where username='$username' and lower(password) = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2))
            union
            Select 'dosen', ds_nm, ds_user, ds_tipe, ds_nidn, lower(ds_pass), lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',ds_pass_kode,ds_tipe)),2)) from tbl_dosen
            where ds_user ='$username' and lower(ds_pass) = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',ds_pass_kode,ds_tipe)),2))
            union 
            Select 'mhs', mhs_nim, mhs_nim ,mhs_tipe, mhs_nim, lower(mhs_pass), lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',mhs_pass_kode,mhs_tipe)),2)) from tbl_mahasiswa
            where mhs_nim='$username' and lower(mhs_pass)= lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',mhs_pass_kode,mhs_tipe)),2))
            ";
            $command = Yii::$app->db->createCommand($que);
            $result  = $command->queryOne();
            self::$users[] = [
                        'id'        =>  $result['username'],
                        'idku'      =>  $result['idku'],
                        'username'  =>  $result['username'],
                        'password'  =>  $result['password'],
                        'authKey'   =>  $result['authKey'],
                        'tipe'      =>  $result['tipe'],
                        'name'      =>  $result['name'],
                        'table'     =>  $result['table'],
             ];
            return $result;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return Yii::$app->session['user'];//self::$Login;//isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
           
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        self::getUser($username);

        foreach (self::$users as $user) {

            if (strcasecmp($user['username'], $username) === 0) {
                $Login = new static($user);
                Yii::$app->session['user'] = $Login;
                return $Login;
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        

        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
       $model = $this->getUser($this->username,$password);
       if($model){
        return true;
       }else
       {
        return false;
       }
       //return $this->password === $this->authKey;//$this->password === md5($password);
    }
    public function ChangePassword($model,$password){         
            if ($model instanceof Dosen) {
              $cmd = Yii::$app->db->createCommand("update tbl_dosen set ds_pass = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',ds_pass_kode,ds_tipe)),2)) where ds_nidn='$model->ds_nidn'");
             
            } elseif ($model instanceof Mahasiswa) {
                 $cmd = Yii::$app->db->createCommand("update tbl_mahasiswa set mhs_pass = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',mhs_pass_kode,mhs_tipe)),2)) where mhs_nim='$model->mhs_nim'");
            } else{
                 $cmd = Yii::$app->db->createCommand("update tbl_user set password = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2)) where username='$model->username'");
            }
             return $cmd->execute() > 0;
    }

    public static function getModel(){
        $table      =   Yii::$app->session['user']->table;
        $model      =   null;
        switch ($table) {
            case 'user':
                $model = TUser::findOne(['id'=> Yii::$app->session['user']->idku]);
                break;
            case 'dosen':
                $model = Dosen::findOne(['ds_nidn'=> Yii::$app->session['user']->idku]);
                break;
            case 'mhs':
                $model = Mahasiswa::findOne(['mhs_nim'=> Yii::$app->session['user']->idku]);
                break;
        }
        return $model;
    }
}
