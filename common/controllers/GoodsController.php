<?php
/**
 * 栏目控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 15:45
 */

namespace api\common\controllers;


use api\common\models\ApiGoods;
use api\traits\Params;
use Yii;

class GoodsController extends ApiTokenController
{

    use Params;

    public $modelClass = 'api\common\models\ApiGoods';


    /**
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $requestParams = Yii::$app->request->queryParams;


        $actions['search'] = [
            'class' => 'api\common\action\goods\SearchAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'dataFilter' => [
                'class' => 'yii\data\ActiveDataFilter',
                'searchModel' => function () {
                    return ApiGoods::getSearchModel();
                },
                'filter' => Params::getFilterParams($requestParams,
                    ['eq' => ['goods_id', 'cat_id'], 'like' => ['goods_name']]),
            ]
        ];
        return $actions;
    }

}
