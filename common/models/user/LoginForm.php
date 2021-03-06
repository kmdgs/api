<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/29 17:05
 */

namespace api\common\models\user;

use Yii;
use yii\base\Model;


/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @property mixed user
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class LoginForm extends Model
{

    public $username;
    public $password;

    private $_user;


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }


    /**
     * 验证用户名和密码 用户名和密码不能为空
     *
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }


    /**
     * 验证密码
     *
     * @author 黄东 kmdgs@qq.com
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }


    /**
     * @author 黄东 kmdgs@qq.com
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {

            /** @var TYPE_NAME $this */
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }


    /**
     * 过用户名获取密码
     *
     * @author 黄东 kmdgs@qq.com
     * @return null|static
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        /** @var TYPE_NAME $this */
        return $this->_user;
    }


}
