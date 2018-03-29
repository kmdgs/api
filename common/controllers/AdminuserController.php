<?php

namespace api\common\controllers;

/**
 * 用户管理用户登录注册等接口
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */


use api\common\models\LoginForm;
use api\common\models\RegisterForm;
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
        $behaviors['authenticator']['except'] = ['options', 'login','register','sendmessage'];
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


        if ($model->load(Yii::$app->request->post(),'') && $model->login()) {
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
            throw new UnprocessableEntityHttpException(Json::encode($model->errors));
        }
    }


    /**
     * 用户注册
     * @author 黄东 kmdgs@qq.com
     */
    public function actionRegister()
    {
        $model = new RegisterForm();
        
        $model->load(Yii::$app->request->post(),'');
        if ($model->validate() && $model->signup()) {
            // 发送确认邮箱
            //$model->sendConfirmationEmail();
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $responseData = "true";
            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, Json::encode($model->errors));
        }
    }


    /**
     * 发送短息
     * @author 黄东 kmdgs@qq.com
     */
    public function actionSendmessage(){

    }




    /**
     * 获取用户本身信息
     * @author 黄东 kmdgs@qq.com
     * @return mixed
     */
    public function actionMe() {
        return $this->getUser();
    }

    /**
     * 检查用户是否有访问权限　用户只允许访问自己的信息
     * 根据传递TOKEN获取用户ID判断是否和传递过来的ID相同　
     * 如果不相同则是其他用户不能访问
     * @author 黄东 kmdgs@qq.com
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $user=$this->getUser();
        if($user->id!=$model->id){
            switch ($action){
                case 'view':
                case 'update':
                throw new ForbiddenHttpException('您无权访问此用户信息！');
            }
        }
    }

}
