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
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'photo'
        ];
    }
}