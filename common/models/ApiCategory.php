<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/6 15:47
 */

namespace api\common\models;


use yii\db\ActiveRecord;

class ApiCategory extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';

    }


    /**
     * rules
     * @return array
     * @author 黄东 kmdgs@qq.com
     */
    public function rules()
    {
        return [
            [
                [
                    'cmoban',
                    'ctime',
                    'address',
                    'prize',
                    'outlink',
                    'english',
                    'pc_list_moban',
                    'pc_content_moban',
                    'm_list_moban',
                    'm_content_moban'
                ],
                'safe'
            ],
            [['name', 'title'], 'string', 'max' => 60],
            [['keywords', 'aurl', 'description', 'cpicurl'], 'string', 'max' => 200],
            [
                [
                    'listnum',
                    'readonly',
                    'disabled',
                    'collapsed',
                    'ctype',
                    'nav',
                    'new_windows',
                    'list_order',
                    'istop',
                    'siteid'
                ],
                'integer'
            ],
            [['action'], 'string', 'max' => 50],
            [['model'], 'string', 'max' => 20],
            ['action', 'match', 'pattern' => '/^[a-zA-Z]\w+$/', 'message' => '只能输入英文字母！'],
        ];
    }


    /**
     * attributeLabels
     * @return array
     * @author 黄东 kmdgs@qq.com
     */
    public function attributeLabels()
    {
        return [
            'id' => '节点ID',
            'name' => '名字',
            'keywords' => '关键字[SEO]',
            'title' => '标题[SEO]',
            'description' => '描述[SEO]',
            'listnum' => '前台列表页文章显示条数',
            'cpicurl' => '栏目图片地址',
            'sum' => '栏目下文章总数',
            'aurl' => '后台URL',
            'action' => '栏目目录',
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'lvl' => 'Lvl',
            'icon' => '图标',
            'icon_type' => '图标类型',
            'active' => '活动',
            'selected' => '已选中',
            'disabled' => '已禁用',
            'readonly' => '只读',
            'visible' => '显示',
            'collapsed' => '折叠',
            'movable_u' => '向上移动',
            'movable_d' => '向下移动',
            'movable_l' => '向左移动',
            'movable_r' => '向右移动',
            'removable' => 'Removable',
            'removable_all' => 'Removable All',
            'cmoban' => '介绍',
            'ctime' => '时间',
            'address' => '地点',
            'ctype' => '栏目类型',
            'nav' => '导航栏显示',//0 不显示 1 头部主导航条 2 尾部导航条 3 都显示
            'new_windows' => '是否新窗口', //新窗口打开 0 不是 1 是,
            'list_order' => '排序方式', //排序方式  1.更新时间 2 发布时间 3.点击次数 4.ID倒序 5.ID顺序 6.自定义排序
            'outlink' => '前台外链',
            'english' => '栏目英文名称',
            'istop' => '循环类型', //0 否 1 是 栏目综合页显示
            'pc_list_moban' => '文章列表页模板',
            'pc_content_moban' => '文章内容页模板',
            'm_content_moban' => '移动列表页模板',
            'm_list_moban' => '移动列表模板',
            'siteid' => '所属站点ID',
            'model' => "模型名称"
        ];
    }


    /**
     * 返回字段
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'name',
            'keywords',
            'title',
            'description',
            'listnum',
            'cpicurl',
            'sum',
            'aurl',
            'action',
            'root',
            'lft',
            'rgt',
            'lvl',
            'cmoban',
            'ctime',
            'address',
            'ctype',
            'nav',//0 不显示 1 头部主导航条 2 尾部导航条 3 都显示
            'new_windows', //新窗口打开 0 不是 1 是,
            'list_order', //排序方式  1.更新时间 2 发布时间 3.点击次数 4.ID倒序 5.ID顺序 6.自定义排序
            'outlink',
            'english',
            'istop', //0 否 1 是 栏目综合页显示
            'siteid',
            'model'
        ];
    }

}

?>


