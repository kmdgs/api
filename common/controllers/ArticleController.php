<?php

namespace api\common\controllers;

/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */



use api\filter\auth\ApiQueryParamsAuth;
use Yii;
use yii\base\DynamicModel;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\Response;


class ArticleController extends ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        //根据access_token进行用户认证
      /*  $behaviors['authenticator'] = [
            'class' => CompositeAuth::class, //引用认证类 复合认证，支持多种认证方式同时操作器
            'authMethods' => [
                ApiQueryParamsAuth::class, //引入认证方法 支持基于HTTP承载令牌的认证方法操作器
            ],
        ];*/


        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Access-Control-Request-Method' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
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


    /**
     * actions
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $requestParams = Yii::$app->request->queryParams;
        $params = ['id', 'title', 'catid'];
        $result = [];
        //查询条件
        foreach ($params as $value) {
            if(!empty($requestParams[$value])){
                $result[$value]=$requestParams[$value];
            }
        }

        $actions['index'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'dataFilter' => [
                'class' => 'yii\data\ActiveDataFilter',
                'searchModel' => function () {
                    return (new DynamicModel(['id' => null, 'title' => null, 'catid' => null]))
                        ->addRule('id', 'integer')
                        ->addRule('title', 'trim')
                        ->addRule('title', 'string')
                        ->addRule('catid', 'integer');
                },
               'filter' => $result,
            ]
        ];
        return $actions;
    }


    public $modelClass = 'api\common\models\ApiArticle';


}
