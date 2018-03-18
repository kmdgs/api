<?php

namespace api\common\controllers;

/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */

use api\common\models\LoginForm;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\Response;


class AdminuserController extends ActiveController
{
    public $modelClass = 'api\common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(), //支持多种认证方法同时操作器
            'authMethods' => [
                HttpBearerAuth::className(), //支持基于HTTP承载令牌的认证方法操作器
            ],

        ];

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);


        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'login', 'signup',];


        //跨域资源共享 CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Access-Control-Request-Method' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        //返回数据格式 xml或是 json
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        return $behaviors;
    }


    /**
     * actionLogin
     * @author 黄东 kmdgs@qq.com
     * @return array
     * @throws HttpException
     */
    public function actionLogin()
    {

        $model = new LoginForm();

       if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user=$model->getUser();
            $user->generateAccessTokenAfterUpdatingClientInfo(true);

           $response = \Yii::$app->getResponse();
           $response->setStatusCode(200);
           $id = implode(',', array_values($user->getPrimaryKey(true)));

           $responseData = [
               'id'    =>  (int)$id,
               'access_token' => $user->access_token,
           ];

           return $responseData;
          // return ['status'=>'success','access_token' => $model->login()];

        } else {
            $model->validate();
            throw new HttpException(422,json_encode($model->errors));
        }
    }


}
