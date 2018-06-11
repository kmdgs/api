<?php
/**
 * 栏目控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 15:45
 */

namespace api\common\controllers;



use api\common\controllers\core\BearerAuthController;
use api\common\models\goods\ApiOrder;
use api\traits\Params;
use Yii;

class OrderController extends BearerAuthController
{

    use Params;


    /**
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $requestParams = Yii::$app->request->queryParams;


        $actions['search'] = [
            'class' => 'api\common\action\order\SearchAction',
            'user' => $this->getUser(),
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'dataFilter' => [
                'class' => 'yii\data\ActiveDataFilter',
                'searchModel' => function () {
                    return ApiOrder::getSearchModel();
                },
                'filter' => Params::getFilterParams($requestParams,
                    ['eq' => ['order_id', 'order_sn']]),
            ]
        ];
        return $actions;
    }

    public $modelClass = 'api\common\models\goods\ApiOrder';



}
