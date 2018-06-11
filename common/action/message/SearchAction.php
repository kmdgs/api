<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\common\action\message;


use common\models\user\grouppm\Grouppmlog;
use Yii;
use yii\data\ActiveDataProvider;
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


        $user=$this->user;
        if(!empty($user->id)){
            $query = $modelClass::find()->where(['>','dateline',$user->created_at]);
        }else{
            throw new BadRequestHttpException('没有找到用户信息！');
        }

        //不显示已删除消息
        $already_delete=Grouppmlog::find()->where(['uid'=>$user->id,'status'=>-1])->asArray()->all();

        $already_delete=ArrayHelper::getColumn($already_delete,'gpmid');

        //消息类型
        if(!empty($requestParams['typeid'])){
            $query->andWhere(['typeid'=>$requestParams['typeid']]);
        }


        //消息读取状态
        $status=ArrayHelper::getValue($requestParams,'status');
        if(!empty($status)){
            $log=Grouppmlog::find()->where(['uid'=>$user->id,'status'=>1])->asArray()->all();
            $logid=ArrayHelper::getColumn($log,'gpmid');
            if($status=='yes'){
                $query->andWhere(['in','id',$logid+$already_delete]);
            }
            if($status=='no'){
                $query->andWhere(['not in','id',$logid+$already_delete]);
            }
        }else{
            $query->andWhere(['not in','id',$already_delete]);
        }


        /*$filter = null;
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

        */

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
