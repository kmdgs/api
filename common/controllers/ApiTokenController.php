<?php
/**
 * 不验证TOKEN控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/28 14:34
 */

namespace api\common\controllers;


use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;


class ApiTokenController extends ActiveController
{

    /**
     * behaviors
     * 黄东 kmdgs@qq.com
     * 2018/6/6 11:43
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Access-Control-Request-Method' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        return $behaviors;
    }
}