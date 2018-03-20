<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 11:38
 */

namespace api\filter\auth;


use yii\filters\auth\QueryParamAuth;
use yii\web\UnauthorizedHttpException;

class ApiQueryParamsAuth extends QueryParamAuth
{

    /**
     * @inheritdoc
     */
    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('您的请求令牌无效。');
    }
}