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
use api\common\models\message\ApiGrouppm;
use api\traits\Params;
use Yii;

class GrouppmController extends BearerAuthController
{

    use Params;


    public function actions()
    {
        $actions = parent::actions();
        $requestParams = Yii::$app->request->queryParams;


        $actions['search'] = [
            'class' => 'api\common\action\message\SearchAction',
            'user' => $this->getUser(),
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'dataFilter' => [
                'class' => 'yii\data\ActiveDataFilter',
                'searchModel' => function () {
                    return ApiGrouppm::getSearchModel();
                },
                'filter' => Params::getFilterParams($requestParams,
                    ['eq' => ['id','typeid']]),
            ]
        ];
        return $actions;
    }



    public $modelClass = 'api\common\models\message\ApiGrouppm';

}
