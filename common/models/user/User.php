<?php
/**
 * @link http://www.kemengduo.com/
 * @author 黄东 kmdgs@qq.com
 * @date 2018/3/29 17:05
 */

namespace api\common\models\user;


use common\models\common\Mycache;
use common\thirdclass\taobao\Dayu;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Firebase\JWT\JWT;
use yii\filters\RateLimitInterface;
use yii\web\IdentityInterface;
use yii\web\Request as WebRequest;


/**
 * This is the model class for table "adminuser".
 *
 * @property integer $id
 * @property string  $username
 * @property string  $realname
 * @property string  $email
 * @property integer $status
 * @property string  $password_hash
 * @property string  $auth_key
 * @property string  $password_reset_token
 * @property string  $access_token
 * @property integer $expire_at
 * @property integer $last_login_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_ip,
 * @property integer $source
 * @property integer $role
 * @property integer $tel
 * @property integer $tel_at
 */
class User extends ActiveRecord implements IdentityInterface, RateLimitInterface
{

    const ROLE_USER = 10; //用户
    const ROLE_STAFF = 50; //工作人员
    const ROLE_ADMIN = 99; //管理员


    const STATUS_DELETED = -1; //删除
    const STATUS_DISABLED = 0; //禁用
    const STATUS_PENDING = 1; //等待确认
    const STATUS_ACTIVE = 10; //正常
    public $role;

    public $allowance;

    public $allowance_updated_at;


    # 速度控制  6秒内访问3次，注意，数组的第一个不要设置1，设置1会出问题，一定要
    #大于2，譬如下面  6秒内只能访问三次
    # 文档标注：返回允许的请求的最大数目及时间，例如，[100, 600] 表示在600秒内最多100次的API调用。
    public function getRateLimit($request, $action)
    {
        $rateLimit = Yii::$app->params['rateLimit'];
        if (is_array($rateLimit['limit']) && !empty($rateLimit['limit'])) {
            return $rateLimit['limit'];
        } else {
            return [120, 60];
        }

    }

    # 文档标注： 返回剩余的允许的请求和相应的UNIX时间戳数 当最后一次速率限制检查时。
    public function loadAllowance($request, $action)
    {
        //return [1,strtotime(date("Y-m-d H:i:s"))];
        //echo $this->allowance;exit;
        return [$this->allowance, $this->allowance_updated_at];
    }
    # allowance 对应user 表的allowance字段  int类型
    # allowance_updated_at 对应user allowance_updated_at  int类型
    # 文档标注：保存允许剩余的请求数和当前的UNIX时间戳。
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }


    /**
     * 获取角色标签
     * getRoleLabel
     *
     * @author 黄东 kmdgs@qq.com
     * @return string
     */
    private function getRoleLabel()
    {
        $roleLabel = '';
        switch ($this->role) {
            case self::ROLE_USER:  //10
                $roleLabel = '用户';
                break;
            case self::ROLE_STAFF: //50
                $roleLabel = '工作人员';
                break;
            case self::ROLE_ADMIN: //99
                $roleLabel = '管理员';
                break;
        }
        return $roleLabel;
    }


    /**
     * allStatus
     * 黄东 kmdgs@qq.com
     * 2018/6/7 14:45
     *
     * @return array
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE => '正常', self::STATUS_DELETED => '禁用'];
    }

    /**
     * getStatusStr
     * 黄东 kmdgs@qq.com
     * 2018/6/7 14:45
     *
     * @return string
     */
    public function getStatusStr()
    {
        return $this->status == self::STATUS_ACTIVE ? '正常' : '禁用';
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * 认证规则
     *
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function rules()
    {
        return [
            [['realname', 'photo', 'birthday'], 'required', 'on' => ['update']],
            [['status', 'expire_at', 'last_login_at', 'created_at', 'updated_at', 'source', 'birthday'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 40],
            [
                ['realname', 'email', 'password_hash', 'auth_key', 'password_reset_token', 'access_token'],
                'string',
                'max' => 255
            ],
            [['email'], 'unique'],
            [['access_token'], 'unique'],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_STAFF, self::ROLE_ADMIN]],
        ];
    }


    public function scenarios()
    {
        $scenarios=parent::scenarios();
        return $scenarios+[
            'update' => ['realname', 'photo', 'birthday'],
        ];
    }

    /**
     * API接口中返回的数据
     *
     * @author 黄东 kmdgs@qq.com
     * @return array
     */
    public function fields()
    {
        $fields = [
            'id',
            'username',
            'email',
            'last_login_at',
            'last_login_ip',
            'confirmed_at',
            'status',
            'status_label' => function () {
                switch ($this->status) {
                    case self::STATUS_ACTIVE:
                        return '正常';
                    case self::STATUS_PENDING:
                        return '等待确认';
                    case self::STATUS_DISABLED:
                        return '禁用';
                    case self::STATUS_DELETED:
                        return '删除';
                }
            },
            'created_at',
            'updated_at',
            'realname',
            'tel',
            'tel_at',
            'photo',
            'sex',
            'birthday',
            'role',
            'role_label' => function () {
                switch ($this->role) {
                    case self::ROLE_USER:
                        return '用户';
                    case self::ROLE_STAFF:
                        return '工作人员';
                    case self::ROLE_ADMIN:
                        return '管理员';
                }
            },
        ];


        return $fields;

    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'realname' => '姓名',
            'email' => '电子邮箱',
            'status' => '状态',
            'password_hash' => '密码',
            'auth_key' => '授权key',
            'password_reset_token' => '密码重置token',
            'access_token' => '访问token',
            'expire_at' => '过期时间',
            'last_login_at' => '最后登入时间',
            'created_at' => '创建时间',
            'updated_at' => '最后修改时间',
            'last_login_ip' => '最后登录IP',
            'source' => '角色',
            'birthday' => '出生日期'
        ];
    }


    /**
     * 验证密码
     *
     * @author 黄东 kmdgs@qq.com
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     *从密码生成密码散列并将其设置为模型。
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }


    /**
     * 用户密码通过后生成令牌
     *
     * @author 黄东 kmdgs@qq.com
     */
    public function generateAccessToken()
    {
        $tokens = $this->getJWT();
        $this->access_token = $tokens[0];   // 令牌
        $this->expire_at = $tokens[1]['exp']; // 过期时间

    }


    /**
     * 生成令牌
     * generateAccessTokenAfterUpdatingClientInfo
     *
     * @author 黄东 kmdgs@qq.com
     * @param bool $forceRegenerate 是否再次生成
     * @return bool
     */
    public function generateAccessTokenAfterUpdatingClientInfo($forceRegenerate = false)
    {
        $this->last_login_ip = Yii::$app->request->userIP;
        $this->last_login_at = time();

        if ($forceRegenerate == true || $this->expire_at == null || (time() > strtotime($this->expire_at))) {
            $this->generateAccessToken();
        }
        $this->save(false);
        return true;
    }

    /**
     * 通过电话号码查找用户信息
     *
     * @param $tel
     * @return static|null
     */
    public static function findByPasswordResetTel($tel)
    {
        return static::findOne([
            'tel' => $tel,
        ]);
    }


    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Store JWT token header items.
     *
     * @var array
     */
    protected static $decodedToken;


    /**
     * 用户通过JWT登录
     * Logins user by given JWT encoded string. If string is correctly decoded
     * - array (token) must contain 'jti' param - the id of existing user
     *
     * @param      $token
     * @param null $type
     * @return mixed|null          User model or null if there's no user
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        $secret = static::getSecretKey();
        // Decode token and transform it into array.
        // Firebase\JWT\JWT throws exception if token can not be decoded
        try {
            $decoded = JWT::decode($token, $secret, [static::getAlgo()]);
        } catch (\Exception $e) {
            return false;
        }
        static::$decodedToken = (array)$decoded;
        // If there's no jti param - exception
        if (!isset(static::$decodedToken['jti'])) {
            return false;
        }
        // JTI is unique identifier of user.
        // For more details: https://tools.ietf.org/html/rfc7519#section-4.1.7
        $id = static::$decodedToken['jti'];
        return static::findByJTI($id);
    }


    /**
     * Finds User model using static method findOne
     * Override this method in model if you need to complicate id-management
     *
     * @param  string $id if of user to search
     * @return mixed       User model
     */
    public static function findByJTI($id)
    {
        /** @var User $user */
        $user = static::find()
            ->where(['=', 'id', $id])
            ->andWhere(['=', 'status', self::STATUS_ACTIVE])
            ->andWhere(['>', 'expire_at', time()])
            ->one();
        if ($user !== null &&
            ($user->getIsBlocked() == true || $user->getIsConfirmed() == false)
        ) {
            return null;
        }
        return $user;
    }

    /**
     * @return bool Whether the user is blocked or not.
     * 用户是否被锁定
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    /**
     * @return bool Whether the user is confirmed or not.
     * 用户是否已经确认
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at != null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * 通过用户名查找用户 删除状态为 0
     *
     * @author 黄东 kmdgs@qq.com
     * @param $username
     * @return null|static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * 令牌秘钥
     *
     * @author 黄东 kmdgs@qq.com
     * @return string
     */
    protected static function getSecretKey()
    {
        return Yii::$app->params['jwtSecretCode'];
    }

    // 如果你愿意的话
    protected static function getHeaderToken()
    {
        return [];
    }

    /**
     * JWT生成的加密方式 声明加密的算法
     *
     * @return string needed algorytm
     */
    public static function getAlgo()
    {
        return 'HS256';
    }

    /**
     * Returns some 'id' to encode to token. By default is current model id.
     * If you override this method, be sure that findByJTI is updated too
     *
     * @return integer any unique integer identifier of user
     */
    public function getJTI()
    {
        return $this->getId();
    }

    /**
     * Encodes model data to create custom JWT with model.id set in it
     * 生成令牌JWT
     * @return array encoded JWT
     */
    public function getJWT()
    {
        // 收集所有数据
        //获取令牌秘钥，后台基本设置中管理
        $secret = static::getSecretKey();
        //当前时间
        $currentTime = time();
        //令牌过期时间 当前时间+后台设置过期时长
        $expire = $currentTime + Mycache::get_cache_time(Yii::$app->params['expireAt']); // 1 day 86400
        $request = Yii::$app->request;
        $hostInfo = '';
        // There is also a \yii\console\Request that doesn't have this property
        if ($request instanceof WebRequest) {
            $hostInfo = $request->hostInfo;
        }

        // 合并与预设不错过任何参数自定义令牌
        // 配置
        $token = array_merge([
            //jwt的签发时间
            'iat' => $currentTime,
            // jwt签发者: timestamp of token issuing.
            'iss' => $hostInfo,
            // 发行人: 接收jwt的一方,包含发行者应用程序的名称或标识符的字符串。可以是一个域名，可以用来丢弃其他应用程序的标记。.
            'aud' => $hostInfo,
            //定义在什么时间之前，该jwt都是不可用的
            'nbf' => $currentTime,
            //  jwt的过期时间，这个过期时间必须要大于签发时间。
            'exp' => $expire,
            // Expire: 令牌何时停止有效的时间戳。应大于IAT和NBF。在这种情况下，令牌将在发出后60秒到期。.
            'data' => [
                'username' => $this->username,  //用户名
                'roleLabel' => $this->getRoleLabel(), //角色标签
                'lastLoginAt' => $this->last_login_at, //最后登录时间
                'role' => $this->role,
            ]
        ], static::getHeaderToken());
        //  jwt的唯一身份标识，主要用来作为一次性token,从而回避重放攻击
        // 可以使用一个惟一的字符串来验证令牌，但不支持没有集中的发行方权限。
        //用户ID
        $token['jti'] = $this->getJTI();
        return [JWT::encode($token, $secret, static::getAlgo()), $token];
    }

    /**
     * 发送短息
     *
     * @author 黄东 kmdgs@qq.com
     * @param $type 短信类型
     * @param $tel 电话号码
     * @return mixed|\ResultSet|\SimpleXMLElement|string
     */
    public static function sendMessage($type, $tel)
    {
        $code = mt_rand(1000, 9999);
        $cache = Yii::$app->cache;
        $cache->set($tel, $code, 180000);
        $message = new Dayu();
        if (!empty($tel) && Yii::$app->params['regtel'] == 1) {
            $appkey = Yii::$app->params['snsappkey'];
            $secret = Yii::$app->params['snssecret'];
            $webname = Yii::$app->params['webname'];
            return $message->verification($code, $webname . '手机认证', $tel, $type, $appkey, $secret);
        } else {
            return 'error';
        }
    }

}



