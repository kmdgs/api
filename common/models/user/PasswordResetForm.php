<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/30 11:18
 */

namespace api\common\models\user;


use yii\base\Model;


class PasswordResetForm extends Model
{

    public $token;

    //密码
    public $password;

    /*确认输入密码*/
    public $again_password;

    //手机号
    public $tel;

    //短信确认
    public $code;
    /**
     * @var \api\common\models\user\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['tel', 'filter', 'filter' => 'trim'],
            [['tel', 'code', 'password', 'again_password'], 'required'],
            [['tel'], 'match', 'pattern' => '/^1(3|4|5|7|8)\d{9}$$/', 'message' => '手机号格式输入不正确。'],
            [
                'tel',
                'exist',
                'targetClass' => '\common\models\user\User',
                'message' => '没有这个手机注册用户'
            ],
            ['again_password', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致'],
            ['code', 'checkCode'],
            ['password', 'string', 'min' => 6],
        ];
    }


    /**
     * 检测验证码
     *
     * @param $attribute
     */
    public function checkCode($attribute)
    {
        $this->_user = User::findByPasswordResetTel($this->tel);
        $cache = \Yii::$app->cache;
        $old_code = $cache->get($this->tel);
        if ($this->code != $old_code) {
            $this->addError($attribute, '验证码输入错误');
        }
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
     * Resets password.
     * 重置密码
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        return $user->save(false);
    }
}