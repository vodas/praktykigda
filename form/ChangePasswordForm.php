<?php

namespace app\form;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 */
class ChangePasswordForm extends Model
{
    public $password;
    public $password2;


    public function rules()
    {
        return [
            // username and password are both required
            [['password', 'password2'],'required'],
            ['password','string', 'min'=>6],
            ['password','match', 'pattern'=>'^(?=.*\d)^','message' => 'the password should contain at least one digit'],
            ['password2', 'compare', 'compareAttribute'=>'password']
        ];
    }
    //for logged user
    public function loggedChangePassword($userID) {
        $user = User::findOne(['login' => $userID]);
        if($user == null) {
            return false;
        } else {
            $user->pass = hash('sha256', Yii::$app->params['hashSalt'] . $this->password);
            $user->save();
            return true;
        }
    }
    //for quest from link via email
     public function changePassword($token)
     {
         $user = User::find()
             ->where(['token' => $token])
             ->one();
         if($user==null) {
             return false;
         }
         $user->token = NULL;
         $user->pass = hash('sha256', Yii::$app->params['hashSalt'] . $this->password);
         $user->save();
         return true;
     }
}



