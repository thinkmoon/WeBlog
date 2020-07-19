<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

class Restful_Action extends Typecho_Widget implements Widget_Interface_Do
{
    /**
     * @var Typecho_Config
     */
    private $config;

    /**
     * @var Typecho_Db
     */
    private $db;

    /**
     * @var Widget_Options
     */
    private $options;

    /**
     * @var array
     */
    private $httpParams;

    public function __construct($request, $response, $params = null)
    {
        parent::__construct($request, $response, $params);

        $this->db = Typecho_Db::get();
        $this->options = $this->widget('Widget_Options');
        $this->config = $this->options->plugin('Restful');
    }

    /**
     * 获取路由参数
     *
     * @return array
     */
    public static function getRoutes()
    {
        $routes = array();
        $reflectClass = new ReflectionClass(__CLASS__);
        $prefix = defined('__TYPECHO_RESTFUL_PREFIX__') ? __TYPECHO_RESTFUL_PREFIX__ : '/api/';

        foreach ($reflectClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectMethod) {
            $methodName = $reflectMethod->getName();

            preg_match('/(.*)Action$/', $methodName, $matches);
            if (isset($matches[1])) {
                array_push($routes, array(
                    'action' => $matches[0],
                    'name' => 'rest_' . $matches[1],
                    'shortName' => $matches[1],
                    'uri' => $prefix . $matches[1],
                    'description' => trim(str_replace(
                        array('/', '*'),
                        '',
                        substr($reflectMethod->getDocComment(), 0, strpos($reflectMethod->getDocComment(), '@'))
                    )),
                ));
            }
        }
        return $routes;
    }

    public function execute()
    {
        $this->sendCORS();
        $this->parseRequest();
    }

    public function action()
    {
    }

    private static function getOpenId()
    {
        return $_SERVER['HTTP_OPENID'];
    }
    /**
     * 发送跨域 HEADER
     *
     * @return void
     */
    private function sendCORS()
    {
        $httpOrigin = $this->request->getServer('HTTP_ORIGIN');
        $allowedHttpOrigins = explode("\n", str_replace("\r", "", $this->config->origin));

        if (!$httpOrigin) {
            return;
        }

        if (in_array($httpOrigin, $allowedHttpOrigins)) {
            $this->response->setHeader('Access-Control-Allow-Origin', $httpOrigin);
        }

        if (strtolower($this->request->getServer('REQUEST_METHOD')) == 'options') {
            Typecho_Response::setStatus(204);
            $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type');
            $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            exit;
        }
    }

    /**
     * 解析请求参数
     *
     * @return void
     */
    private function parseRequest()
    {
        if ($this->request->isPost()) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                $this->throwError('Parse JSON error');
            }
            $this->httpParams = $data;
        }
    }

    /**
     * 获取 GET/POST 参数
     *
     * @param string $key 目标参数的 key
     * @param mixed $default 返回的默认值
     * @return mixed
     */
    private function getParams($key, $default = null)
    {
        if ($_SERVER['HTTP_APISECRET'] != $this->config->apiSecret) {
            $this->throwError("接口密钥不正确", 403);
            return 0;
        }
        if ($this->request->isGet()) {
            return $this->request->get($key, $default);
        }
        if (!isset($this->httpParams[$key])) {
            return $default;
        }
        return $this->httpParams[$key];
    }

    /**
     * 以 JSON 格式返回错误
     *
     * @param string $message 错误信息
     * @param integer $status HTTP 状态码
     * @return void
     */
    private function throwError($message = 'unknown', $status = 400)
    {
        Typecho_Response::setStatus($status);
        $this->response->throwJson(array(
            'status' => 'error',
            'message' => $message,
            'data' => null,
            'success' => false
        ));
    }

    /**
     * 以 JSON 格式响应请求的信息
     *
     * @param mixed $data 要返回给用户的内容
     * @return void
     */
    private function throwData($data, $message = "成功请求")
    {
        $this->response->throwJson(array(
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'success' => true
        ));
    }

    /**
     * 锁定 API 请求方式
     *
     * @param string $method 请求方式 (get/post)
     * @return void
     */
    private function lockMethod($method)
    {
        $method = strtolower($method);
        if (strtolower($this->request->getServer('REQUEST_METHOD')) != $method) {
            $this->throwError('method not allowed', 405);
        }
    }


    /**
     * 登录API
     *  @param code 小程序login的code
     *  @param mp 小程序平台类型["weixin","qq"]
     *  @return openid 
     */
    public function loginAction()
    {
        $this->lockMethod('get');
        $code = $this->getParams('code', 'null');
        $mp = $this->getParams('mp', null);
        if ($code != 'null') {
            $nickName = $this->getParams('nickName', 'null');
            $avatarUrl = $this->getParams('avatarUrl', 'null');
            $city = $this->getParams('city', 'null');
            $country = $this->getParams('country', 'null');
            $gender = $this->getParams('gender', 'null');
            $province = $this->getParams('province', 'null');
            if ($mp == "weixin") {
                $url = sprintf('https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code', $this->config->weixinAppID, $this->config->weixinAppSecret, $code);
            } else if ($mp == "qq") {
                $url = sprintf('https://api.q.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code', $this->config->qqAppID, $this->config->qqAppSecret, $code);
            }
            $info = file_get_contents($url);
            $json = json_decode($info); //对json数据解码
            $arr = get_object_vars($json);
            $openid = $arr['openid'];
            if ($openid != null && $openid != '') {
                $row = $this->db->fetchRow($this->db->select('openid', 'lastlogin')->from('table.WeBlog_users')->where('openid = ?', $openid));
                //已存在的用户,更新上次登录时间
                if (sizeof($row) > 0) {
                    // 更新用户信息
                    if ($avatarUrl != 'null') {
                        $this->db->query($this->db->update('table.WeBlog_users')->rows(array(
                            'lastlogin' => time(),
                            'nickName' => $nickName, 'avatarUrl' => $avatarUrl, 'city' => $city, 'country' => $country,
                            'gender' => $gender, 'province' => $province
                        ))->where('openid = ?', $openid));
                    } else {
                        $this->db->query($this->db->update('table.WeBlog_users')->rows(array(
                            'lastlogin' => time()
                        ))->where('openid = ?', $openid));
                    }
                    $this->throwData($openid);
                } else {
                    //新用户
                    $this->db->query($this->db->insert('table.WeBlog_users')->rows(array(
                        'openid' => $openid, 'createtime' => time(), 'lastlogin' => time(),
                        'nickName' => $nickName, 'avatarUrl' => $avatarUrl, 'city' => $city, 'country' => $country,
                        'gender' => $gender, 'province' => $province
                    )));
                    $this->throwData($openid);
                }
            } else {
                $this->throwError("兑换openId失败", 500);
            }
        } else {
            $this->throwError("错误的code", 500);
        }
    }
    /**
     * 获取文章列表、搜索文章的接口
     *
     * @return void
     */
    public function postsAction()
    {
        $this->lockMethod('get');

        $page = $this->getParams('page', 1);
        $mid = $this->getParams('mid', 0);

        $select   = $this->db->select('distinct table.contents.cid as cid', 'title', 'table.contents.created', 'commentsNum', 'views', 'likes')->from('table.contents')->join('table.relationships', 'table.contents.cid = table.relationships.cid', Typecho_DB::LEFT_JOIN)->where('type = ?', 'post')->where('status = ?', 'publish')->order('table.contents.created', Typecho_Db::SORT_DESC)->page($page, 10);
        if ($mid != 0) {
            $select = $select->where('mid = ?', $mid);
        }
        $posts  = $this->db->fetchAll($select);
        foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $post['category'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'category'));
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "thumb"));
            $post['desc'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "desc"));
            $result[]    = $post;
        }

        $this->throwData($result);
    }
    /**
     * @method getSticky 获取置顶文章列表
     * @return List post_list
     */
    function getStickyAction()
    {
        $select   = "SELECT typecho_contents.cid, typecho_contents.title,typecho_fields.str_value as 'thumb' FROM typecho_contents,typecho_fields WHERE typecho_contents.cid = typecho_fields.cid AND typecho_fields.`name` = 'thumb' AND typecho_contents.cid IN (" . $this->sticky . ")";
        $query = $this->db->query($select);
        $post = $this->db->fetchAll($query);
        $this->throwData($post);
    }

    /**
     * 获取博客数据总览
     * 
     * */ 
    function getOverviewAction()
    {
        $select   = $this->db->select('COUNT(cid) AS Num')->from('table.contents')->where('type = ?', 'post');
        $data['posts'] = $this->db->fetchAll($select);
        $select   = $this->db->select('COUNT(coid) AS Num')->from('table.comments');
        $data['comments'] = $this->db->fetchAll($select);
        $select   = $this->db->select('COUNT(mid) AS Num')->from('table.metas')->where('type = ?', 'category');
        $data['categorys'] = $this->db->fetchAll($select);
        $select   = $this->db->select('COUNT(mid) AS Num')->from('table.metas')->where('type = ?', 'tag');
        $data['tags'] = $this->db->fetchAll($select);
        $this->throwData($data);
    }
    /**
     * 新增评论
     */
    function addCommentAction()
    {
        $cid = $this->getParams('cid', -1);
        $openid = $this->getOpenId();
        $text = $this->getParams('text', "None");
        $parent = $this->getParams('parent', 0);

        $select = $this->db->select('nickName')->from('table.WeBlog_users')->where('openid = ?', $openid);
        $data = $this->db->fetchAll($select);

        if (sizeof($data) <= 0) {
            $this->throwError("未找到用户");
            return 0;
        }

        $coid = $this->db->query($this->db->insert('table.comments')->rows(array(
            'cid' => $cid, 'created' => time(), 'openid' => $openid, 'authorId' => '0', 'author' => $data[0]["nickName"],
            'ownerId' => '1', 'text' => $text, 'type' => 'comment',
            'status' => 'waiting', 'parent' => $parent
        )));

        if ($coid > 0) {
            $row = $this->db->fetchRow($this->db->select('commentsNum')->from('table.contents')->where('cid = ?', $cid));
            $this->db->query($this->db->update('table.contents')->rows(array('commentsNum' => (int) $row['commentsNum'] + 1))->where('cid = ?', $cid));
        }
        $this->throwData($coid);
    }
    /**
     * 获取评论
     */
    function getCommentAction()
    {
        $cid = $this->getParams('cid', -1);
        $comments = $this->db->fetchAll($this->db->select('cid', 'coid', 'created', 'text', 'parent', 'table.comments.openid AS openid', 'table.WeBlog_users.nickName AS nickName', 'table.WeBlog_users.avatarUrl AS avatarUrl', 'table.WeBlog_users.gender AS gender')->from('table.comments')->join('table.WeBlog_users', 'table.comments.openid = table.WeBlog_users.openid')->where('cid = ?', $cid)->where('parent = 0')->where('status = ?', 'approved')->order('table.comments.created', Typecho_Db::SORT_DESC));
        $result = array();
        //获取根评论
        foreach ($comments as $comment) {
            if ($comment['parent'] == 0) {
                $result[] = $comment;
            }
        }
        // //获取子评论
        // foreach($comments as $comment) {
        //     if($comment['parent'] != 0) {
        //         $parent = $comment['parent'];
        //         $temp = $this->db->fetchAll($this->db->select('cid','coid','created', 'author', 'text', 'parent', 'authorImg')->from('table.comments')->where('cid = ?', $cid)->where('coid = ?', $parent)->where('status = ?', 'approved')->order('table.comments.created', Typecho_Db::SORT_DESC));                
        //         if(sizeof($temp)>0)
        //         {
        //             while($temp[0]['parent']!=0)
        //             {
        //                 $parent = $temp[0]['parent'];
        //                 $temp = $this->db->fetchAll($this->db->select('cid','coid','created', 'author', 'text', 'parent', 'authorImg')->from('table.comments')->where('cid = ?', $cid)->where('coid = ?', $parent)->where('status = ?', 'approved')->order('table.comments.created', Typecho_Db::SORT_DESC));
        //             }
        //             for($i=0; $i<sizeof($result);$i++)
        //             {
        //                 if($result[$i]['coid'] == $temp[0]['coid']) {
        //                     $comment['parentitem'] = $this->db->fetchAll($this->db->select('cid','coid','created', 'author', 'text', 'parent', 'authorImg')->from('table.comments')->where('cid = ?', $cid)->where('coid = ?', $comment['parent'])->where('status = ?', 'approved')->order('table.comments.created', Typecho_Db::SORT_DESC));
        //                     $result[$i]['replays'][] = $comment;
        //                 }
        //             }
        //         }
        //     }
        // }
        $this->throwData($result);
    }
    /**
     * 获取关于页cid
     */
    function getAboutCidAction()
    {
        $this->throwData($this->config->aboutCid);
    }
    /**
     * 通过cid获取post
     */
    function getPostBycidAction()
    {
        if (isset($_GET['cid'])) {
            $cid = $this->getParams('cid');
            $select = $this->db->select('cid', 'title', 'created', 'type', 'slug', 'likes', 'text', 'commentsNum')->from('table.contents')->where('status = ?', 'publish')->where('created < ?', time())->where('cid = ?', $cid);
            //更新点击量数据库
            $row = $this->db->fetchRow($this->db->select('views')->from('table.contents')->where('cid = ?', $cid));
            $this->db->query($this->db->update('table.contents')->rows(array('views' => (int) $row['views'] + 1))->where('cid = ?', $cid));
            $posts  = $this->db->fetchAll($select);
            foreach ($posts as $post) {
                $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
                $post['category'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'category'));
                $result[]    = $post;
            }
            $this->throwData($result);
        }
    }
    /**
     * 给文章点赞
     */
    function likePostAction()
    {
        $cid = $this->getParams('cid', -1);
        $openid = self::getOpenId();
        $row = $this->db->fetchRow($this->db->select('likes')->from('table.contents')->where('cid = ?', $cid));

        $this->db->query($this->db->update('table.contents')->rows(array('likes' => (int) $row['likes'] + 1))->where('cid = ?', $cid));
        //更新赞数据库
        $this->db->query($this->db->insert('table.WeBlog_like')->rows(array('openid' => $openid, 'cid' => $cid)));
        $this->throwData(true);
    }
    /**
     * 判断用户是否点赞指定文章
     */
    function getPostLikeStatusAction()
    {
        $openid = self::getOpenId();
        $cid = $this->getParams('cid', 'null');
        $row = $this->db->fetchRow($this->db->select('openid', 'cid')->from('table.WeBlog_like')->where('cid = ?', $cid)->where('openid = ?', $openid));
        if (count($row) == 0) {
            $this->throwData(false);
        } else {
            $this->throwData(true);
        }
    }
    /**
     * 搜索
     */
    function searchAction()
    {
        $keyword = $this->getParams('keyWord', 'null');
        $mid = $this->getParams('mid', 'null');
        $select = $this->db->select('table.contents.cid AS cid', 'title', 'table.contents.created', 'commentsNum', 'views', 'likes')->from('table.contents')->join('table.relationships', 'table.contents.cid = table.relationships.cid', Typecho_DB::LEFT_JOIN)->where('type = ?', 'post')->where('status = ?', 'publish')->order('table.contents.created', Typecho_Db::SORT_DESC)->limit(20);
        if ($keyword != 'null') {
            $select = $select->where('table.contents.text LIKE ?', '%' . $keyword . '%');
        }
        if ($mid != 'null') {
            $select = $select->where('mid = ?', $mid);
        }
        $posts  = $this->db->fetchAll($select);
        foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $post['category'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'category'));
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "thumb"));
            $post['desc'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "desc"));
            $result[]    = $post;
        }
        $this->throwData($result,"搜索:".$keyword.", mid:".$mid);
    }
    /**
     * 获取博客的所有标签
     */
    function tagsAction()
    {
        $sql = "SELECT mid,name,count FROM typecho_metas WHERE type = 'tag'";
        $select = $this->db->query($sql);
        $result = $this->db->fetchAll($select);
        $this->throwData($result);
    }
    /**
     * 获取博客的所有分类
     */
    function categoriesAction()
    {
        $select = $this->db->select('mid', 'name', 'count')->from('table.metas')->where('type = ?', 'category')->order('order', Typecho_Db::SORT_DESC);
        $result = $this->db->fetchAll($select);
        $this->throwData($result);
    }
    // 获取AccessToken
    private function getAccessToken()
    {
        //实例化redis
        $redis = new Redis();
        //连接
        $redis->connect('127.0.0.1', 6379);
        $redis->select(1);
        $access_token = $redis->get("access_token");
        if ($access_token && $redis->get("token_expire_time") > time()) {
            return $access_token;
        } else {
            $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', $this->weixinAppID, $this->weixinAppSecret);
            $res = json_decode(file_get_contents($url));
            $access_token = $res->access_token;
            $redis->set('access_token', $access_token);
            $redis->set('token_expire_time', time() + 7000);
            return $access_token;
        }
    }
    /**
     * 生成 CSRF Token
     *
     * @param mixed $key
     * @return string
     */
    private function generateCsrfToken($key)
    {
        return base64_encode(
            hash_hmac(
                'sha256',
                hash_hmac(
                    'sha256',
                    date('Ymd') . $this->request->getServer('REMOTE_ADDR') . $this->request->getServer('HTTP_USER_AGENT'),
                    hash('sha256', $key, true),
                    true
                ),
                $this->config->csrfSalt,
                true
            )
        );
    }

    /**
     * 检查 CSRF Token 是否匹配
     *
     * @param mixed $key
     * @param mixed $token
     * @return boolean
     */
    private function checkCsrfToken($key, $token)
    {
        return hash_equals($token, $this->generateCsrfToken($key));
    }
}
