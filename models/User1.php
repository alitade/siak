<?php
namespace app\models;
use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{
    
    const ADMIN     =   1;
    const FULL      =   2;
    const DOSEN     =   3;
    const PRODI     =   4;
    const MHS       =   5;
    const REKTOR    =   6;
    const BAA       =   8;
	
	const STATUS_ACTIVE = 10;
    const STATUS_NON_ACTIVE = 20;
    
    const ROLE_ADMIN = 10;
	public $idku;
	public $authKey;
	public $table;

    private static $Login;
    private static $users =[]; 

	 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_';
    }

	private function view($user,$pass){
		$cmd = Yii::$app->db->createCommand("
		select  * from(
			select ds_user username,NULL username2, '3' tipe,ds_pass pass,'Dosen' posisi from tbl_dosen
			union all 
			select mhs_nim,NULL,'5',mhs_pass,'Mahasiswa' from tbl_mahasiswa
		) t 
		
		where (username='$user' or username2='$user') and pass='".md5($pass)."'
		and username not in(
			select username from user_
			union
			select username2 from user_
		)
		")->queryOne();
		//die(print_r($cmd));
		if($cmd){
			$GetKode	= Funct::acak(10);
			$PassBaru 	= md5($pass.$GetKode.$cmd['tipe']);
			$CEK =User::findOne(['username'=>$user]);
			//$CEK =User::findOne(['username'=>$user,'username2'=>$user]);
			if(!User::findOne(['username'=>$user,'password'=>$PassBaru])){
				$UserBaru = new User();
				$UserBaru->username 	= "$cmd[username]";
				$UserBaru->name 		= "$cmd[username]";
				$UserBaru->password 	= $PassBaru;
				$UserBaru->pass_kode 	= $GetKode;
				$UserBaru->tipe 		= "$cmd[tipe]";
				$UserBaru->posisi 		= "$cmd[posisi]";
				$UserBaru->username2	= "$cmd[username2]";
				$UserBaru->stat 		='1';
				$UserBaru->status 		='10';
				$UserBaru->save(false);
				
				$ID =$UserBaru->id;
				$cmd = Yii::$app->db->createCommand("insert auth_assignment(item_name,[user_id],created_at)values('".($UserBaru->tipe==3?'Dosen':'Mahasiswa')."','$ID','".time()."') ")->execute();
			}else{return false;}				
		}else{return false;}			
	}
	
    private function getUser($username,$pass=''){
            $password = ($pass !='')? $pass: @$_POST['LoginForm']['password'];
			User::view($username,$password);
			
            $que="
            Select *, CAST(id AS VARCHAR(200)) idku, lower(password) password, 
			lower(convert(varchar(32),HASHBYTES('MD5',concat('$password',pass_kode,tipe)),2)) authKey from user_
            where (username='$username' or username2='$username' )
			and lower(password) = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2))
            ";
			
            $command = Yii::$app->db->createCommand($que);
            $result  = $command->queryOne();
			//die(print_r($command->queryAll()));
			
			
            self::$users[] = [
                        'id'        =>  $result['id'],
                        'idku'      =>  $result['username'],
                        'username'  =>  $result['username'],
                        'password'  =>  $result['password'],
                        'authKey'   =>  $result['authKey'],
                        'tipe'      =>  $result['tipe'],
             ];
            return $result;
    }

    /**
     * @inheritdoc
     
    public static function findIdentity($id)
    {
        return Yii::$app->session['user'];//self::$Login;//isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }
	*/
	 
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
	          $cmd = Yii::$app->db->createCommand("update user_ set [password] = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2)) where id='$model->id'");
			  
/*            if ($model instanceof Dosen) {
              $cmd = Yii::$app->db->createCommand("update tbl_dosen set ds_pass = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',ds_pass_kode,ds_tipe)),2)) where ds_nidn='$model->ds_nidn'");
             
            } elseif ($model instanceof Mahasiswa) {
                 $cmd = Yii::$app->db->createCommand("update tbl_mahasiswa set mhs_pass = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',mhs_pass_kode,mhs_tipe)),2)) where mhs_nim='$model->mhs_nim'");
            } else{
                 $cmd = Yii::$app->db->createCommand("update tbl_user set password = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2)) where username='$model->username'");
            }
*/
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

    public static function Tipe($id){
		$model= TblTipe::findOne(['tp_id'=>$id]);
		return $model;		
	}


}


