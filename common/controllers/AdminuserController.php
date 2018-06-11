<?php

namespace api\common\controllers;

/**
 * 用户管理用户登录注册等接口
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */


use api\common\controllers\core\BearerAuthController;
use api\common\models\user\LoginForm;
use api\common\models\user\PasswordResetForm;
use api\common\models\user\RegisterForm;
use api\common\models\user\User;
use Yii;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;


class AdminuserController extends BearerAuthController
{
    /*
     * @var 引用用户模型类
     */
    public $modelClass = 'api\common\models\user\User';


    /**
     * 注入行为
     * behaviors
     *
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'options',
            'login',
            'register',
            'sendmessage',
            'passwordreset',
            'captcha'
        ];
        return $behaviors;
    }


    /**
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['captcha'] = [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            'minLength' => 4,
            'maxLength' => 4,
            'backColor' => 0xFFFF00
        ];
        return $actions;
    }

    /**
     * 用户登录接口
     * POST参数
     * LoginForm[username] 用户名
     * LoginForm[password] 密码
     * 用户名和密码错误 返回错误数据 状态码码 422
     *
     * @author 黄东 kmdgs@qq.com
     * @return array
     * @throws HttpException
     */
    public function actionLogin()
    {

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            $user = $model->user;
            $user->generateAccessTokenAfterUpdatingClientInfo(true);

            Yii::$app->response->setStatusCode(200);

            return [
                'id' => $user->getPrimaryKey(false), //获取用户ID
                'access_token' => $user->access_token, //后台TOKEN
            ];

        } else {
            $model->validate();
            throw new UnprocessableEntityHttpException(Json::encode($model->errors));
        }
    }


    /**
     * 用户注册
     *
     * @author 黄东 kmdgs@qq.com
     * @throws UnprocessableEntityHttpException
     * @throws \yii\base\Exception
     * @return array
     */
    public function actionRegister()
    {
        $model = new RegisterForm();
        $scenario=Yii::$app->request->get('scenario','tel');
        $model->scenario = $scenario;
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate() && $model->signup()) {
            $user = $model->user;
            $user->generateAccessTokenAfterUpdatingClientInfo(true);
            $id = $user->getPrimaryKey(false);

            $response = Yii::$app->getResponse();

            if (!empty($id) && !empty($user->username) && !empty($user->access_token)) {
                $response->setStatusCode(201);
                $responseData = [
                    'status' => 'true',
                    'id' => $id,
                    'username' => $user->username,
                    'access_token' => $user->access_token,
                ];
            } else {
                throw new UnprocessableEntityHttpException('未知错误，用户未注册成功！');
            }

            return $responseData;
        } else {
            // Validation error
            throw new UnprocessableEntityHttpException(Json::encode($model->errors));
        }
    }




    /**
     * 重置密码
     *
     * @author 黄东 kmdgs@qq.com
     * @return string
     * @throws HttpException
     */
    public function actionPasswordreset()
    {
        $model = new PasswordResetForm();
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate() && $model->resetPassword()) {
            $response = Yii::$app->getResponse();
            $user = $model->user;
            if (!empty($user->username) && !empty($user->access_token)) {
                $response->setStatusCode(201);
                $responseData = [
                    'status' => 201,
                    'username' => $user->username,
                    'tel' => $user->tel,
                ];
            } else {
                throw new UnprocessableEntityHttpException('未知错误，密码未修改成功！');
            }

            /** @var TYPE_NAME $responseData */
            return $responseData;


            /** @var TYPE_NAME $responseData */
            return $responseData;
        } else {
            // Validation error
            throw new UnprocessableEntityHttpException(Json::encode($model->errors));
        }
    }


    /**
     * 短信注册发送
     * 发送类型 post
     * 参数
     * type 短信类型 [1=>'活动验证',2=>'变更验证',3=>'登录验证',4=>'注册验证',5=>'身份验证',6=>'登录异常']
     * tel 电话号码
     *
     * @author 黄东 kmdgs@qq.com
     */
    public function actionSendmessage()
    {
        return User::sendMessage(Yii::$app->request->post('type'), Yii::$app->request->post('tel'));
    }


    /**
     * 获取用户本身信息,只能查找用户信息，不能查找管理员信息
     *
     * @author 黄东 kmdgs@qq.com
     * @return mixed
     */
    public function actionMe()
    {
        return $this->getUser();
    }

    /**
     * 检查用户是否有访问权限　用户只允许访问自己的信息
     * 根据传递TOKEN获取用户ID判断是否和传递过来的ID相同　
     * 如果不相同则是其他用户不能访问
     *
     * @author 黄东 kmdgs@qq.com
     * @param string $action
     * @param null   $model
     * @param array  $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $user = $this->getUser();
        if ($user->id != $model->id) {
            switch ($action) {
                case 'view':
                case 'update':
                    throw new ForbiddenHttpException('您无权访问此用户信息！');
            }
        }
    }

}
