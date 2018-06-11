<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\common\action\favorite;


use Yii;
use yii\helpers\Url;

/**
 * IndexAction implements the API endpoint for listing multiple models.
 * For more details and usage information on IndexAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CreateAction extends \api\action\CreateAction
{


    /**
     * Creates a new model.
     *
     * @throws \yii\base\InvalidConfigException
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $requestParams = Yii::$app->getRequest()->getBodyParams();

        $user_field=$this->user_field;
        $user=$this->user;


        $modelClass = $this->modelClass;
        //查询是否关注过此文章
        if(!empty($requestParams['id']) && !empty($requestParams['table'])){
            $model=$modelClass::find()->where(['userid'=>$user->id])->andWhere(['id'=>$requestParams['id']])->andWhere(['table'=>$requestParams['table']])->one();
           if($model!=null){
               return $model;
           }
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->$user_field=$user->id;
        $model->dateline=time();



        $model->load($requestParams, '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
