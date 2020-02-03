<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_NON_ACTIVE = 20;
    
    const ROLE_ADMIN = 10;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_';
    }
    
    public function behaviors()
    {
        return [
            TimeStampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [['username', 'email'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['role', 'default', 'value' => self::ROLE_ADMIN],
            ['email', 'email'],
            ['username', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'plainPassword' => 'Password',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Ditambahkan Pada',
            'updated_at' => 'Diupdate Pada',
        ];
    }
    
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_NON_ACTIVE => 'Non Active',
        ];
    }
    
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
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	 private static $users =[]; 

    private function getUser($username,$pass=''){            
            $password = ($pass !='')? $pass: @$_POST['LoginForm']['password'];
            $que="
            Select lower(password) password, lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2)) authKey from tbl_user
            where username='$username' and lower(password) = lower(convert( varchar(32),HASHBYTES('MD5', concat('$password',pass_kode,tipe)),2))";
            $command = Yii::$app->db->createCommand($que);
            $result  = $command->queryOne();
            return $result;
    }

	
	public function maasCheck($str){
		$sql = "select item_name from auth_assignment where user_id=".Yii::$app->user->identity->id;
		$assign = AuthAssignment::findBySql($sql)->one();
		if(empty($assign)){
			return 0;
		}
		$exp = explode("/",$str);
		if(count($exp)==3){
			$sql = "select parent from auth_item_child where (parent='$assign' and child like '%$str%') or (parent='$assign' and child like '%/$exp[1]/*%')";
		}else{
			$sql = "select parent from auth_item_child where (parent='$assign' and child like '%$str%')";
		}
		$model = AuthItemChild::findBySql($sql)->count();
		if($model>0){
			return 1;
		}
		return 0;
	}
}
