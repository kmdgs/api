<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\common\action\goods;

use api\models\goods\Goods;
use common\models\goods\GoodsAttr;
use common\models\goods\GoodsAttribute;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Action;

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


    /**
     * @throws \yii\base\InvalidConfigException
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


        $query = $modelClass::find()->where(['is_on_sale' => 1]);


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


        $attr_key = GoodsAttribute::getKeyOrValue($requestParams);
        $attr_value = GoodsAttribute::getKeyOrValue($requestParams, 'value');
        $attr_type = GoodsAttribute::getKeyOrValue($requestParams, 'type');

        //获取属性筛选
        if (count($attr_key) > 0) {
            $query->andWhere([
                'in',
                Goods::tableName() . '.goods_id',
                GoodsAttr::getGoodsId($attr_key, $attr_value, $attr_type)
            ]);
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
