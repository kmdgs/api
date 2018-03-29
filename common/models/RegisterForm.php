<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/29 17:05
 */

namespace api\common\models;


use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;

    public $email;

    public $tel;

    public $password;
    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\user\User', 'message' => '此用户名已经被占用'],
            ['username', 'string', 'length' => [3, 25]],
            //  ['username', 'match', 'pattern' => '/^[A-Za-z0-9_-]{3,25}$/', 'message' => '您的用户名只能包含字母数字字符、下划线和破折号。'],
            /*   ['email', 'trim'],
               ['email', 'required'],
               ['email', 'email'],
               ['email', 'string', 'max' => 255],
               ['email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => '此邮箱已经被占用'],*/
            ['tel', 'filter', 'filter' => 'trim'],
            ['tel', 'required'],
            ['tel', 'unique', 'targetClass' => '\common\models\user\User', 'message' => '手机号已经注册。'],
            [['tel'], 'match', 'pattern' => '/^1(3|4|5|7|8)\d{9}$$/', 'message' => '手机号格式输入不正确。'],
            ['tel', 'required'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '电子邮箱',
            'password' => '密码',
            'tel' => '手机号码',
            'tel_at' => '手机认证时间'
        ];
    }

    /**
     * Signs user up.
     * 注册用户
     * @return boolean the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            // $user->email = $this->email;
            $user->tel = $this->tel;
            $user->tel_at = time();
            $user->role = User::ROLE_USER;
            $user->status = User::STATUS_PENDING;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->last_login_ip = Yii::$app->request->userIP;
            if ($user->save(false)) {
                $this->_user = $user;
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Return User object
     *
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    public function sendConfirmationEmail()
    {
        $confirmURL = \Yii::$app->params['frontendURL'] . '#/confirm?id=' . $this->_user->id . '&auth_key=' . $this->_user->auth_key;
        $email = \Yii::$app->mailer
            ->compose(
                ['html' => 'signup-confirmation-html'],
                [
                    'appName' => \Yii::$app->name,
                    'confirmURL' => $confirmURL,
                ]
            )
            ->setTo($this->email)
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
            ->setSubject('Signup confirmation')
            ->send();
        return $email;
    }
}