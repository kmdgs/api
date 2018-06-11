<?php
/**
 * 栏目控制器
 *
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 15:45
 */

namespace api\common\controllers;


use api\common\controllers\core\ApiTokenController;

class CategoryController extends ApiTokenController
{

    public $modelClass = 'api\common\models\ApiCategory';
}
