<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 15:45
 */

namespace api\common\controllers;



use api\filter\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\Response;

class CategoryController extends ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();


        //根据access_token进行用户认证
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class, //引用认证类 复合认证，支持多种认证方式同时操作器
            'authMethods' => [
                HttpBearerAuth::class, //引入认证方法 支持基于HTTP承载令牌的认证方法操作器
            ],
        ];


        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                // restrict access to 限制访问
                'Access-Control-Request-Method' => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => ['*'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        return $behaviors;
    }





    public $modelClass = 'api\common\models\ApiCategory';


}
