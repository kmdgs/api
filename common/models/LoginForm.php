<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace api\common\models;


use Yii;
use yii\base\Model;


/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


    /**
     * validatePassword
     * @author 黄东 kmdgs@qq.com
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUserByUserName();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
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
            return Yii::$app->user->login($this->getUserByUsername());
        }
        return false;
    }


    /**
     * getUser
     * @author 黄东 kmdgs@qq.com
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }


    /**
     * getUser
     * @author 黄东 kmdgs@qq.com
     * @return \dektrium\user\models\User|null|static
     */
    protected function getUserByUserName()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
