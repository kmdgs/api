<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/28 15:41
 */

namespace api\common\models\message;

use common\models\user\grouppm\Grouppm;
use yii\base\DynamicModel;

class ApiGrouppm extends Grouppm
{
    /**
     * getSearchModel
     * 黄东 kmdgs@qq.com
     * 2018/6/9 16:42
     *
     * @return DynamicModel
     */
    public static function getSearchModel()
    {
        return (new DynamicModel(['id','typeid']))
            ->addRule(['id','typeid'], 'integer');
    }
}