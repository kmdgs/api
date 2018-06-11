<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\common\action\favorite;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * DeleteAction implements the API endpoint for deleting a model.
 * For more details and usage information on DeleteAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DeleteAction extends \api\action\DeleteAction
{


    /**
     * Deletes a model.
     *
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {

        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        $user = $this->user;

        $model = $query->where(['favid' => $id])->andWhere(['userid' => $user->id])->one();

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}
