<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\common\action\order;

use api\models\goods\Goods;
use common\models\goods\GoodsAttr;
use common\models\goods\GoodsAttribute;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataFilter;
use yii\helpers\ArrayHelper;
use yii\rest\Action;
use yii\web\BadRequestHttpException;

/**
 * IndexAction implements the API endpoint for listing multiple models.
 * For more details and usage information on IndexAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SearchAction extends Action
{

    public $prepareDataProvider;

    public $dataFilter;

    public $user;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws BadRequestHttpException
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     *
     * @throws \yii\base\InvalidConfigException
     * @throws BadRequestHttpException
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }


        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;


        $query = $modelClass::find();

        $user=$this->user;
        if(!empty($user->id)){
            $query->andWhere(['user_id'=>$user->id]);
        }else{
            throw new BadRequestHttpException('没有找到用户信息！');
        }


        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {

                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }


        if (!empty($filter)) {
            $query->andWhere($filter);
        }



        if (!empty($requestParams['keywords'])) {
            $query->andFilterWhere(['like', 'goods_name', $requestParams['keywords']]);
        }


        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }
}
