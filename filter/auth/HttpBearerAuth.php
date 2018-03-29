<?php
    /**
     * @link http://www.yiiframework.com/
     * @copyright Copyright (c) 2008 Yii Software LLC
     * @license http://www.yiiframework.com/license/
     */

    namespace api\filter\auth;

    use yii\filters\auth\AuthMethod;

    /**
     * HttpBearerAuth is an action filter that supports the authentication method based on HTTP Bearer token.
     *
     * You may use HttpBearerAuth by attaching it as a behavior to a controller or module, like the following:
     *
     * ```php
     * public function behaviors()
     * {
     *     return [
     *         'bearerAuth' => [
     *             'class' => \yii\filters\auth\HttpBearerAuth::className(),
     *         ],
     *     ];
     * }
     * ```
     *
     * @author Qiang Xue <qiang.xue@gmail.com>
     * @since 2.0
     */
    class HttpBearerAuth extends AuthMethod
    {
        /**
         * @var string the HTTP authentication realm
         */
        public $realm = 'api';


        /**
         * @inheritdoc
         */
        public function authenticate($user, $request, $response)
        {
            $authHeader = $request->getHeaders()->get('Authorization');


            // Added following lines to support fastcgi issue. 添加一下代码对解决FASTCGI的支持的问题
            // To support this, must update .htaccess as below:  在.htaccess 添加如下代码
            //  # Authorization Headers
            //  RewriteCond %{HTTP:Authorization} .
            //  RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

            if($authHeader == null && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] != "") {
                $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }


            if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {

                $identity = $user->loginByAccessToken($matches[1], get_class($this));
                if ($identity === null) {
                    $this->handleFailure($response);
                }
                return $identity;
            }

            return null;
        }

        /**
         * @inheritdoc
         */
        public function challenge($response)
        {
            $response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
        }



    }
