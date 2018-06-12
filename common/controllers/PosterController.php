<?php

namespace api\common\controllers;

/**
 * 广告接口
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */


use api\common\controllers\core\ApiTokenController;


class PosterController extends ApiTokenController
{

    public $modelClass = 'api\common\models\module\ApiPosterSpace';

}
