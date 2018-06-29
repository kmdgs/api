<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/12
 * Time: 15:30
 */

namespace api\action;


use api\traits\FormatResult;

class ViewAction extends \yii\rest\ViewAction
{
    /**
     * run
     * 黄东 kmdgs@qq.com
     * 2018/6/12 15:32
     *
     * @param string $id
     * @throws \yii\web\NotFoundHttpException
     * @return \yii\db\ActiveRecordInterface
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return FormatResult::result($model);
    }
}