<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/6
 * Time: 16:37
 */

namespace api\models\module;



use common\models\module\poster\Poster;
use yii\db\ActiveRecord;


class PosterSpace extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%poster_space}}';
    }

    /**
     * getArticleside
     *
     * @return \yii\db\ActiveQuery
     * @author 黄东 kmdgs@qq.com
     */
    public function getPoster()
    {
        return $this->hasMany(Poster::class,
            ['spaceid' => 'spaceid']);
    }




}