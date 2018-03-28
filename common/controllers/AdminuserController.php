<?php

namespace api\common\controllers;

/**
 * 用户管理用户登录注册等接口
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */


use api\common\models\LoginForm;
use api\common\models\User;
use api\filter\auth\HttpBearerAuth;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Json;
use yii\web\HttpException;


class AdminuserController extends BearerAuthController
{
    /*
     * @var 引用用户模型类
     */
    public $modelClass = 'api\common\models\User';

    /**
     * 注入行为
     * behaviors
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class, //引用认证类 复合认证，支持多种认证方式同时操作器
            'authMethods' => [
                HttpBearerAuth::class, //引入认证方法 支持基于HTTP承载令牌的认证方法操作器
            ],
            'except' => ['options', 'login','register' ] //不认证的操作列表
        ];

        return $behaviors;
    }


    /**
     * 用户登录接口
     * POST参数
     * LoginForm[username] 用户名
     * LoginForm[password] 密码
     * 用户名和密码错误 返回错误数据 状态码码 422
     * @author 黄东 kmdgs@qq.com
     * @return array
     * @throws HttpException
     */
    public function actionLogin()
    {

        $model = new LoginForm();


        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->user;
            $user->generateAccessTokenAfterUpdatingClientInfo(true);

            Yii::$app->response->setStatusCode(200);
            $id = implode(',', array_values($user->getPrimaryKey(true)));

            $responseData = [
                'id' => (int)$id,
                'access_token' => $user->access_token,
            ];
            return $responseData;

        } else {
            $model->validate();
            throw new HttpException(422, Json::encode($model->errors));
        }
    }

    /**
     * 返回用户列表
     * @author 黄东 kmdgs@qq.com
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => User::find()->where([
                '!=',
                'status',
                -1
            ])->andWhere([
                'role' => User::ROLE_USER
            ])
        ]);
    }


    /**
     * 用户注册
     * @author 黄东 kmdgs@qq.com
     */
    public function actionRegister()
    {

    }


}
