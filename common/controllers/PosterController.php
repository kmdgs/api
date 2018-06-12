<?php

namespace api\common\controllers;

/**
 * 文章控制器接口
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */


use api\common\controllers\core\ApiTokenController;
use api\common\models\ApiArticle;
use api\traits\Params;
use Yii;


class PosterController extends ApiTokenController
{

    use Params;

    /**
     * 根据传递的参数查询文章列表
     * actions
     *
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $requestParams = Yii::$app->request->queryParams;

        $actions['indexs'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'dataFilter' => [
                'class' => 'yii\data\ActiveDataFilter',
                'searchModel' => function () {
                    return ApiArticle::getSearchModel();
                },
                'filter' => Params::getFilterParams($requestParams,
                    ['eq' => ['id', 'catid'], 'like' => ['title', 'abstract']]),
            ]
        ];
        return $actions;
    }


    public $modelClass = 'api\common\models\module\ApiPosterSpace';


}
