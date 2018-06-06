<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 16:37
 */

namespace api\models;


use common\models\content\content\ArticleSide;
use yii\db\ActiveRecord;


class Article extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * getArticleside
     *
     * @return \yii\db\ActiveQuery
     * @author 黄东 kmdgs@qq.com
     */
    public function getSide()
    {
        return $this->hasOne(ArticleSide::class,
            ['id' => 'id']);
    }

    /**
     * getUser
     * 黄东 kmdgs@qq.com
     * 2018/6/6 16:48
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class,
            ['id' => 'userid']);
    }

    /**
     * getCategory
     * 黄东 kmdgs@qq.com
     * 2018/6/6 16:49
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class,
            ['id' => 'catid']);
    }
}