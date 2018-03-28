<?php

namespace api\common\controllers;

/**
 * 文章控制器接口
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */



use api\common\models\ApiArticle;
use Yii;
use yii\base\DynamicModel;



class ArticleController extends ApiTokenController
{


    /**
     * 根据传递的参数查询文章列表
     * actions
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $requestParams = Yii::$app->request->queryParams;

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
               'filter' => ApiArticle::getFilterParams($requestParams),
            ]
        ];
        return $actions;
    }


    public $modelClass = 'api\common\models\ApiArticle';


}
