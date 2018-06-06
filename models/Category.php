<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 16:46
 */

namespace api\models;


use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%category}}';
    }


    public function fields()
    {
        return [
          'id',
          'name'
        ];
    }
}