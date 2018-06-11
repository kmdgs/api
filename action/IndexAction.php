<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\action;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

/**
 * IndexAction implements the API endpoint for listing multiple models.
 * For more details and usage information on IndexAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class IndexAction extends \yii\rest\IndexAction
{



    //用户ID
    public $user;

    //用户字段名称
    public $user_field='userid';

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
        if(!empty($user)){
            $query->andWhere([$this->user_field=>$user->id]);
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
