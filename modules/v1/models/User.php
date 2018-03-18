<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/2/26 9:52
 */

namespace api\modules\v1\models;


class User extends \common\models\user\User
{

    // 明确列出每个字段，适用于你希望数据表或
// 模型属性修改时不导致你的字段修改（保持后端API兼容性）
    public function fields()
    {
        return [
            // 字段名和属性名相同
            'id',
            // 字段名为"email", 对应的属性名为"email_address"
            'email_address' => 'email',
            'name'=>'username'
            // 字段名为"name", 值由一个PHP回调函数定义
           /* 'name' => function ($model) {
                return $model->first_name . ' ' . $model->last_name;
            },*/
        ];
    }
}