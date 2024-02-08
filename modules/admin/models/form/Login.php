<?php

namespace app\modules\admin\models\form;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\Model;
use app\modules\admin\models\User;

/**
 * Login form
 */
class Login extends Model
{
    public $email;
    public $password;
    public $rememberMe = false;
    public $reCaptcha;
    
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['email','email'],
            ['password', 'validatePassword'],
            /*[['reCaptcha'], ReCaptchaValidator2::className(),
                'secret' => getenv('RECAPTCHASECRET'),
                'uncheckedMessage' => 'Please confirm that you are not a bot.',
                'when' => function ($model) {
                    return Yii::$app->session->get('numberOfAttempt') >= 2;
                }
            ],*/
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->getUser()->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $class = Yii::$app->getUser()->identityClass ? : 'app\modules\admin\models\User';
            $this->_user = $class::findByUserEmail($this->email);
        }

        return $this->_user;
    }
}
