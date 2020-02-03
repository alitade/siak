<?php
namespace app\models;
use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
	use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{
    
/*
    const ADMIN     =   1;
    const FULL      =   2;
    const DOSEN     =   3;
    const PRODI     =   4;
    const MHS       =   5;
    const REKTOR    =   6;
    const PIKET    	=   7;
    const BAA   	=   8;
    const REKTORAT  =   12;
*/	
	const STATUS_ACTIVE = 10;
    const STATUS_NON_ACTIVE = 20;
    
    const ROLE_ADMIN = 10;

	public $akses;
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

    public function rules(){
        return [
            [['akses','name','username','password','posisi','tipe'], 'required'],
        ];
    }

	private function view($user,$pass){

		$params = [':username' => trim($user), ':password' => md5($pass)];

		$cmd = Yii::$app->db->createCommand("
		select  * from(
			select ds_user username,ds_email username2,'3' tipe,ds_pass pass,'Dosen' posisi from tbl_dosen
			union all 
			select mhs_nim,NULL,'5',mhs_pass,'Mahasiswa' from tbl_mahasiswa
		) t 
		
		where (username=:username or username2=:username )
		and pass=:password
		and username not in(
			select username from user_
		)
		")->bindValues($params)->queryOne();

		//die(print_r($cmd));
		if($cmd){
			$GetKode	= Funct::acak(10);
			$PassBaru 	= md5($pass.$GetKode.$cmd['tipe']);
			$CEK =User::findOne(['username'=>$user]);			
			if(
				!User::find()->andWhere(['password'=>$PassBaru])
				->orWhere(['username'=>$user,'username2'=>$user])->one()				
				){
				$UserBaru = new User();
				$UserBaru->username 	= "$cmd[username]";
				$UserBaru->name 		= "$cmd[username]";
				$UserBaru->password 	= $PassBaru;
				$UserBaru->pass_kode 	= $GetKode;
				$UserBaru->tipe 		= "$cmd[tipe]";
				$UserBaru->posisi 		= "$cmd[posisi]";
				$UserBaru->username2 	= "$cmd[username2]";
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

			$params = [':username' => trim($username), ':password' => $password];

            $que="
            Select *, CAST(id AS VARCHAR(200)) idku, lower(password) password, 
			lower(convert(varchar(32),HASHBYTES('MD5',concat(:password,pass_kode,tipe)),2)) authKey from user_
            where (username=:username or username2=:username)
			 and lower(password) = lower(convert( varchar(32),HASHBYTES('MD5', concat(:password,pass_kode,tipe)),2))
            ";

            $command = Yii::$app->db->createCommand($que)->bindValues($params);

            $result  = $command->queryOne();
			//die(print_r($result));
			
            self::$users[] =[
                        'id'        =>  $result['id'],
                        'idku'      =>  trim($result['username']),
                        'username'  =>  trim($result['username']),
                        'username2' =>  trim($result['username2']),
                        'password'  =>  $result['password'],
                        'authKey'   =>  $result['authKey'],
                        'tipe'      =>  $result['tipe'],
             ];
			//echo "<!-- ".print_r($result)." -->";
			//echo "<!-- ".print_r(self::$users)." -->";
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
            if (
				strcasecmp($user['username'], $username) === 0|| strcasecmp($user['username2'],$username) === 0
			) {

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


    public function validatePassword($password)
    {
       $model = $this->getUser($this->username,$password);
       if($model){
        return true;
       }else{
        return false;
       }
    }
    public function ChangePassword($model,$password){         

    		  $params = [':id' => trim($model->id), ':password' => $password];

	          $cmd = Yii::$app->db->createCommand("update user_ set [password] = lower(convert( varchar(32),HASHBYTES('MD5', concat(:password,pass_kode,tipe)),2)) where id=:id")->bindValues($params);
	          
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
		$model = TblTipe::findOne(['tp_id'=>$id]);
		return $model;		
	}
}
