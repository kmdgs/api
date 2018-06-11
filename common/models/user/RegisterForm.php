<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/29 17:05
 */

namespace api\common\models\user;


use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;

class RegisterForm extends Model
{

    use ModuleTrait;

    //用户名
    public $username;

    //邮箱
    public $email;

    //手机号
    public $tel;

    //手机验证码
    public $code;

    //邮箱验证码
    public $email_code;

    //密码
    public $password;
    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {


        $rule = [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\api\models\User', 'message' => '此用户名已经被占用'],
            ['username', 'string', 'length' => [3, 25]],

            ['email', 'trim'],
            ['email', 'required', 'on' => ['email']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\api\models\User', 'message' => '此邮箱已经被占用'],
            ['tel', 'filter', 'filter' => 'trim'],
            ['tel', 'required'],
            ['tel', 'unique', 'targetClass' => '\api\models\User', 'message' => '手机号已经注册。'],
            [['tel'], 'match', 'pattern' => '/^1(3|4|5|7|8)\d{9}$$/', 'message' => '手机号格式输入不正确。'],
            ['tel', 'required', 'on' => ['tel']],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['email_code', 'required', 'on' => ['email']],
            ['email_code', 'checkEmailCode'],
        ];

        //是否启用短信接口
        if (Yii::$app->params['snsio'] == 1) {
            $rule = array_merge($rule, [['code', 'required', 'on' => ['tel']], ['code', 'checkCode'],]);
        };

        return $rule;
    }


    /**
     * 注册用户场景
     * scenarios
     * 黄东 kmdgs@qq.com
     * 2018/6/11 10:08
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            'tel' => ['username', 'tel', 'password', 'code'],
            'email' => ['username', 'email', 'password', 'code'],
        ];
    }


    /**
     * checkEmailCode
     * 黄东 kmdgs@qq.com
     * 2018/6/11 11:04
     *
     * @param $attribute
     */
    public function checkEmailCode($attribute)
    {
        $cache = Yii::$app->cache;
        $old_code = $cache->get($this->email);
        if ($this->email_code != $old_code) {
            $this->addError($attribute, '验证码输入错误');
        }
    }

    /**
     * 检验验证码
     *
     * @param $attribute
     */
    public function checkCode($attribute)
    {
        $cache = Yii::$app->cache;
        $old_code = $cache->get($this->tel);
        if ($this->code != $old_code) {
            $this->addError($attribute, '验证码输入错误');
        }
    }


    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '电子邮箱',
            'password' => '密码',
            'tel' => '手机号码',
            'tel_at' => '手机认证时间',
            'code' => '短信验证码'
        ];
    }

    /**
     * Signs user up.
     * 注册用户
     *
     * @throws \yii\base\Exception
     * @return boolean the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            // $user->email = $this->email;
            $user->tel = $this->tel;
            $user->tel_at = time(); //手机号确认时间
            $user->confirmed_at = time(); //确认时间
            $user->role = User::ROLE_USER; //用户角色
            $user->status = User::STATUS_ACTIVE; //账号状态
            $user->registration_ip = Yii::$app->request->userIP; //注册IP
            $user->setPassword($this->password); //生成密码
            $user->generateAuthKey(); //随机生成验证密码
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

    /**
     * 发送确认邮件
     *
     * @author 黄东 kmdgs@qq.com
     * @return bool
     */
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