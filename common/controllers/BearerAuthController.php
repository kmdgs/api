<?php
/**
 * 验证ACCESS_TOKEN控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/28 14:41
 */

namespace api\common\controllers;


use Yii;
use yii\rest\ActiveController;
use api\filter\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;

class BearerAuthController extends ActiveController
{

    //更新场景
    public $updateScenario = 'update';

    //新增场景
    public $createScenario = 'create';

    /**
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //根据access_token进行用户认证
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class, //引用认证类 复合认证，支持多种认证方式同时操作器
            'authMethods' => [
                HttpBearerAuth::class, //引入认证方法 支持基于HTTP承载令牌的认证方法操作器
                // ApiQueryParamsAuth::class, //引入认证方法 支持基于HTTP承载令牌的认证方法操作器
            ],
        ];


        //跨域资源共享 CORS
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

        //返回数据格式 xml或是 json
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        return $behaviors;
    }


    /**
     * 根据头信息获取用户信息
     *
     * @author 黄东 kmdgs@qq.com
     * @return mixed
     */
    protected function getUser()
    {
        //根据TOKEEN获取当前用户信息
        $httpbearerauth = new HttpBearerAuth();
        $this->attachBehavior('getuser', $httpbearerauth);
        return $this->authenticate(Yii::$app->getUser(), Yii::$app->getRequest(), Yii::$app->getResponse());
    }
}