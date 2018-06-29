<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 2018/6/11
 * Time: 11:50
 */

namespace api\common\models\user;


use common\models\user\Favorite;
use yii\base\DynamicModel;

class ApiFavorite extends Favorite
{


    /**
     * fields
     * 黄东 kmdgs@qq.com
     * 2018/6/12 17:38
     *
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['userid'],$fields['dateline'],$fields['spaceuid'],$fields['siteid']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'id', 'spaceuid', 'dateline', 'siteid'], 'integer'],
            [['title'], 'required'],
            [['description'], 'string'],
            [['route', 'title', 'table'], 'string', 'max' => 255]
        ];
    }

    /**
     * scenarios
     * 黄东 kmdgs@qq.com
     * 2018/6/11 15:30
     *
     * @return array
     */
    public function scenarios()
    {
        return [
          'create'=>['id','title','route','table'],
        ];
    }


    /**
     * getSearchModel
     * 黄东 kmdgs@qq.com
     * 2018/6/11 14:06
     *
     * @return mixed
     */
    public static function getSearchModel()
    {
        $model = (new DynamicModel(['id', 'table', 'siteid']))
            ->addRule(['table'],'string')
            ->addRule(['id','siteid'], 'integer');
        return $model;
    }



}