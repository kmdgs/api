<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 16:42
 */

namespace api\models;


use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     * tableName
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:31
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * fields
     * 黄东 kmdgs@qq.com
     * 2018/6/6 17:31
     *
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'username',
            'photo'
        ];
    }
}