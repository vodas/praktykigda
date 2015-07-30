<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use app\validators\LoginValidator;
use app\validators\EmailValidator;
use app\validators\EmailUpdateValidator;

class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_READ = 'read';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    public $pass_repeat;
    public $image;
    public $avatar;

    public $auth_key;
    public static function tableName()
    {
        return 'users';
    }

    public function getUserRole()
    {
        return $this->hasOne(UserRole::className(), ['user_id' => 'user_id']);
    }

    public function getOrder()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'user_id']);
    }

    public function getUsername(){
        return $this->login;
    }
    public function rules(){
        return [
            [['login','pass'],'string','max'=>100],
            [['login', 'email'], 'unique'],
            ['email','email'],
            ['newsletter', 'boolean'],
            [['house_nr','flat_nr'],'integer'],
            [['name', 'surname', 'street', 'city'], 'string'],
            ['pass','string', 'min'=>6],
            ['zipcode', 'match', 'pattern'=>'/^\d{2}-\d{3}$/'],
            [['email', 'pass'], 'required', 'on' => self::SCENARIO_CREATE],
            [['login','email'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['user_id','login','pass','pass_repeat','email','name','surname','street','house_nr','flat_nr','zipcode','city','user_role','newsletter'], 'safe'],
            ['login', LoginValidator::className(),  'on' => self::SCENARIO_CREATE],
            ['email', EmailValidator::className(),  'on' => self::SCENARIO_CREATE],
            ['email', EmailUpdateValidator::className(),  'on' => self::SCENARIO_UPDATE],
            ['pass','match', 'pattern'=>'^(?=.*\d)^','message' => 'Hasło powinno zawiera chociaż jedną cyfrę.',  'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            ['pass_repeat', 'compare', 'compareAttribute'=>'pass'],
            [['image'], 'safe'],
            [['image'], 'file', 'extensions'=>'jpg, gif, png'],
            ['filename', 'file', 'extensions'=>'jpg, gif, png']
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['user_id','login','pass','pass_repeat','email','name','surname','street','house_nr','flat_nr','zipcode','city','user_role','newsletter'];
        $scenarios[self::SCENARIO_READ] = ['user_id','login','pass','pass_repeat','email','name','surname','street','house_nr','flat_nr','zipcode','city','user_role','newsletter'];
        $scenarios[self::SCENARIO_UPDATE] = ['user_id','login','pass','pass_repeat','email','name','surname','street','house_nr','flat_nr','zipcode','city','user_role','newsletter'];
        return $scenarios;
    }


    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
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
        if(isset($this->auth_key) == false || isset($this->auth_key) == null)
            $this->generateAuthKey();
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
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->pass === hash('sha256',\Yii::$app->params['hashSalt'].$password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /** EXTENSION MOVIE **/
}