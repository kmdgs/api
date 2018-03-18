<?php

namespace api\common\controllers;

/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */

use api\auth\ApiQueryParamsAuth;
use yii\base\DynamicModel;
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
        $behaviors['authenticator']=[
            'class'=>ApiQueryParamsAuth::className()
        ];



       $behaviors['corsFilter'] = [
            'class' => Cors::className(),
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
            'class' => ContentNegotiator::className(),
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
        $queryParams=\Yii::$app->request->queryParams;
        $params=['id','title','catid'];
        $filter=[];
        //查询条件
        foreach ($params as $value){
            $result = (isset($queryParams[$value])) ? array_merge($filter,[$value=>$queryParams[$value]]) : '';
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
                'filter'=>$result,
            ]
        ];
        return $actions;
    }


    public $modelClass = 'api\common\models\ApiArticle';


}
