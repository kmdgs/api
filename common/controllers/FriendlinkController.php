<?php
/**
 * 友情链接控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/28 15:42
 */

namespace api\common\controllers;


use api\common\models\ApiFriendLink;
use Yii;
use yii\base\DynamicModel;

class FriendlinkController extends ApiTokenController
{

    /**
     * 获取友情链接
     *
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
                    return (new DynamicModel(['type' => null]))
                        ->addRule('type', 'integer');
                },
                'filter' => ApiFriendLink::getFilterParams($requestParams),
            ]
        ];
        return $actions;
    }

    public $modelClass = 'api\common\models\ApiFriendLink';
}