<?php

namespace api\common\controllers;

/**
 * 文章控制器接口
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/25 16:07
 */


use api\common\controllers\core\ApiTokenController;
use api\common\models\ApiArticle;
use api\traits\Params;
use Yii;


class PosterController extends ApiTokenController
{

    use Params;

   

    public $modelClass = 'api\common\models\module\ApiPosterSpace';


}
