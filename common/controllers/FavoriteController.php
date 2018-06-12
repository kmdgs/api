<?php
/**
 * 收藏关注控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 15:45
 */

namespace api\common\controllers;


use api\common\controllers\core\BearerAuthController;
use api\common\models\user\ApiFavorite;
use api\traits\Params;
use common\models\user\Favorite;
use Yii;


class FavoriteController extends BearerAuthController
{

    public $modelClass = 'api\common\models\user\ApiFavorite';


    /**
     * actions
     * 黄东 kmdgs@qq.com
     * 2018/6/11 14:54
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $requestParams = Yii::$app->request->queryParams;


        $actions['index'] = [
            'class' => 'api\action\IndexAction',
            'user' => $this->getUser(),
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'dataFilter' => [
                'class' => 'yii\data\ActiveDataFilter',
                'searchModel' => function () {
                    return ApiFavorite::getSearchModel();
                },
                'filter' => Params::getFilterParams($requestParams,
                    ['eq' => ['id', 'table', 'siteid']]),
            ]
        ];
        $actions['create'] = [
            'class' => 'api\common\action\favorite\CreateAction',
            'user' => $this->getUser(),
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'scenario' => $this->createScenario,
        ];
        $actions['delete'] = [
            'class' => 'api\common\action\favorite\DeleteAction',
            'user' => $this->getUser(),
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
        ];
        return $actions;
    }


    /**
     * actionWhether
     * 黄东 kmdgs@qq.com
     * 2018/6/11 17:32
     *
     * @param        $id
     * @param string $table
     * @return array|int|string
     */
    public function actionWhether($id, $table = 'article')
    {
        $user = $this->getUser();
        return ['result' => Favorite::find()->where(['userid' => $user->id])->andWhere(['id' => $id])->andWhere(['table' => $table])->count()];
    }


}
