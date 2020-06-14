<?php
class WeBlog_Action extends Typecho_Widget implements Widget_Interface_Do
{
    private $db;
    private $res;

    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);
        // 微信小程序相关配置
        $this->weixinAppID = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->weixinAppID;
        // appSecret
        $this->weixinAppSecret = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->weixinAppSecret;
        // QQ小程序相关配置
        $this->qqAppID = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->qqAppID;
        // appSecret
        $this->qqAppSecret = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->qqAppSecret;
        // 博客头像
        $this->sticky = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->sticky;
        // apiSecret
        $this->apiSecret = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->apiSecret;
        $this->db  = Typecho_Db::get();
        $this->res = new Typecho_Response();
        if (method_exists($this, $this->request->type)) {
            if ($_SERVER['HTTP_APISECRET'] != $this->apiSecret) {
                $this->export("接口密钥不正确", 403);
                return 0;
            }
            call_user_func(array(
                $this,
                $this->request->type
            ));
        } else {
            $this->defaults();
        }
    }
    /**
     * 登录API
     *  @param code 小程序login的code
     *  @param mp 小程序平台类型["weixin","qq"]
     *  @return openid 
     */
    function login()
    {
        $code = self::GET('code', 'null');
        $mp = self::GET('mp', null);
        if ($code != 'null') {
            $nickName = self::GET('nickName', 'null');
            $avatarUrl = self::GET('avatarUrl', 'null');
            $city = self::GET('city', 'null');
            $country = self::GET('country', 'null');
            $gender = self::GET('gender', 'null');
            $province = self::GET('province', 'null');
            if ($mp == "weixin") {
                $url = sprintf('https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code', $this->weixinAppID, $this->weixinAppSecret, $code);
            } else if ($mp == "qq") {
                $url = sprintf('https://api.q.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code', $this->qqAppID, $this->qqAppSecret, $code);
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
                    $this->export($openid);
                } else {
                    //新用户
                    $this->db->query($this->db->insert('table.WeBlog_users')->rows(array(
                        'openid' => $openid, 'createtime' => time(), 'lastlogin' => time(),
                        'nickName' => $nickName, 'avatarUrl' => $avatarUrl, 'city' => $city, 'country' => $country,
                        'gender' => $gender, 'province' => $province
                    )));
                    $this->export($openid);
                }
            } else {
                $this->export("兑换openId失败", 500);
            }
        } else {
            $this->export("错误的code", 500);
        }
    }
    // 获取文章点赞用户列表
    function getLikeUsers()
    {
        $cid = self::GET('cid', 'null');
        if ($cid != 'null') {
            $openids = $this->db->fetchAll($this->db->select('openid')->from('table.WeBlog_like')->where('cid = ?', $cid));
            foreach ($openids as $openid) {
                $temp = $this->db->fetchAll($this->db->select('nickName', 'avatarUrl')->from('table.WeBlog_users')->where('openid = ?', $openid));
                if (sizeof($temp) > 0) {
                    $likeinfo[] = $temp[0];
                }
            }
            $this->export($likeinfo);
        } else {
            $this->export("No one like");
        }
    }
    /**
     * @method getSticky 获取置顶文章列表
     * @return List post_list
     */
    function getSticky()
    {
        // $cids = explode(",", $this->sticky);
        // $select   = $this->db->select('cid', 'title')->from('table.contents')->where('cid IN ?', $cids);
        // $posts  = $this->db->fetchAll($select);
        $select   = "SELECT\n" .
            "	typecho_contents.cid, \n" .
            "	typecho_contents.title, \n" .
            "	typecho_fields.str_value as 'thumb'\n" .
            "FROM\n" .
            "	typecho_contents,\n" .
            "	typecho_fields\n" .
            "WHERE\n" .
            "	typecho_contents.cid = typecho_fields.cid AND\n" .
            "	typecho_fields.`name` = 'thumb' AND\n" .
            "   typecho_contents.cid IN (" . $this->sticky . ")";

        $query = $this->db->query($select);
        $post = $this->db->fetchAll($query);

        // foreach ($posts as $post) {
        //     $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "thumb"));
        //     $result[]    = $post;
        // }
        $this->export($post);
    }
    // 获取文章页数
    // function getPageNum()
    // {
    //     $select =  $this->db->select('COUNT(cid) AS Num')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish');
    //     $data = $this->db->fetchAll($select);
    //     $Num = $data[0]['Num'] / $this->pageSize + 1;
    //     $this->export((int) $Num);
    // }
    // 获取博客总览
    function getOverview()
    {
        $select   = $this->db->select('COUNT(cid) AS Num')->from('table.contents')->where('type = ?', 'post');
        $data['posts'] = $this->db->fetchAll($select);
        $select   = $this->db->select('COUNT(coid) AS Num')->from('table.comments');
        $data['comments'] = $this->db->fetchAll($select);
        $select   = $this->db->select('COUNT(mid) AS Num')->from('table.metas')->where('type = ?', 'category');
        $data['categorys'] = $this->db->fetchAll($select);
        $select   = $this->db->select('COUNT(mid) AS Num')->from('table.metas')->where('type = ?', 'tag');
        $data['tags'] = $this->db->fetchAll($select);
        $this->export($data);
    }
    // 获取博客作者信息
    function getAuthorInfo()
    {
        $select   = $this->db->select('*')->from('table.users');
        $lists = $this->db->fetchAll($select);
        $lists[0]['avatarUrl'] = $this->avatarUrl;
        $this->export($lists);
    }
    // 获取最近post
    function post()
    {
        $page     = (int) self::GET('page', 1);
        $select   = $this->db->select('cid', 'title', 'table.contents.created', 'commentsNum', 'views', 'likes')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->order('table.contents.created', Typecho_Db::SORT_DESC)->page($page, 10);
        $posts  = $this->db->fetchAll($select);
        foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $post['category'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'category'));
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "thumb"));
            $post['desc'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "desc"));
            $result[]    = $post;
        }
        $this->export($result);
    }
    // 新增评论
    function addComment()
    {
        $cid = self::GET('cid', -1);
        $openid = self::getOpenId();
        $text = self::GET('text', "None");
        $parent = self::GET('parent', 0);

        $select = $this->db->select('nickName')->from('table.WeBlog_users')->where('openid = ?', $openid);
        $data = $this->db->fetchAll($select);

        $coid = $this->db->query($this->db->insert('table.comments')->rows(array(
            'cid' => $cid, 'created' => time(), 'openid' => $openid, 'authorId' => '0', 'author' => $data[0]["nickName"],
            'ownerId' => '1', 'text' => $text, 'type' => 'comment',
            'status' => 'waiting', 'parent' => $parent
        )));

        if ($coid > 0) {
            $row = $this->db->fetchRow($this->db->select('commentsNum')->from('table.contents')->where('cid = ?', $cid));
            $this->db->query($this->db->update('table.contents')->rows(array('commentsNum' => (int) $row['commentsNum'] + 1))->where('cid = ?', $cid));
        }
        $this->export($coid);
    }
    // 获取评论
    function getComment()
    {
        $cid = self::GET('cid', -1);
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
        $this->export($result);
    }
    // 获取关于页cid
    // function getAboutcid()
    // {
    //     $cid = 'none';
    //     $cid = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->aboutCid;

    //     $this->export($cid);
    // }
    // 通过cid获取post
    function getPostBycid()
    {
        if (isset($_GET['cid'])) {
            $cid = self::GET('cid');
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
            $this->export($result);
        }
    }
    // 文章点赞
    function likePost()
    {
        $cid = self::GET('cid', -1);
        $openid = self::getOpenId();
        $row = $this->db->fetchRow($this->db->select('likes')->from('table.contents')->where('cid = ?', $cid));

        $this->db->query($this->db->update('table.contents')->rows(array('likes' => (int) $row['likes'] + 1))->where('cid = ?', $cid));
        //更新赞数据库
        $this->db->query($this->db->insert('table.WeBlog_like')->rows(array('openid' => $openid, 'cid' => $cid)));
        $this->export(true);
    }
    // 获取用户点赞信息
    function getPostLikeStatus()
    {
        $openid = self::getOpenId();
        $cid = self::GET('cid', 'null');
        $row = $this->db->fetchRow($this->db->select('openid', 'cid')->from('table.WeBlog_like')->where('cid = ?', $cid)->where('openid = ?', $openid));
        if (count($row) == 0) {
            $this->export(false);
        } else {
            $this->export(true);
        }
    }
    // 搜索关键字
    function search()
    {
        $keyword = self::GET('keyWord', 'null');
        $select   = $this->db->select('cid', 'title', 'table.contents.created', 'commentsNum', 'views', 'likes')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('table.contents.text LIKE ?', '%' . $keyword . '%')->order('table.contents.created', Typecho_Db::SORT_DESC);
        $posts  = $this->db->fetchAll($select);
        foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $post['category'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'category'));
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "thumb"));
            $post['desc'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])->where('name = ?', "desc"));
            $result[]    = $post;
        }
        $this->export($result);
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
    // 封装返回数据格式
    public function export($data = array(), $status = 200)
    {
        $this->res->throwJson(array(
            'status' => $status,
            'data' => $data
        ));
        exit;
    }
    // 判断GET
    private static function GET($key, $default = '')
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    // 获取openid
    private static function getOpenId()
    {
        return $_SERVER['HTTP_OPENID'];
    }

    public function action()
    {
        $this->on($this->request);
    }
    // 封装HTTP请求
    function http_post_data($url, $data_string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string)
            )
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return  $return_content;
    }
}
