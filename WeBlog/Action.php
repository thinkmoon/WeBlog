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
        $this->avatarUrl = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->avatarUrl;
        // 页面文章数
        $this->pageSize = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->pageSize;
        $this->db  = Typecho_Db::get();
        $this->res = new Typecho_Response();
        if (method_exists($this, $this->request->type)) {
            call_user_func(array(
                $this,
                $this->request->type
            ));
        } else {
            $this->defaults();
        }
    }
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
                $this->export('none');
            }
        } else {
            $this->export("error code");
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
    // 获取文章页数
    function getPageNum()
    {
        $select =  $this->db->select('COUNT(cid) AS Num')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish');
        $data = $this->db->fetchAll($select);
        $Num = $data[0]['Num'] / $this->pageSize + 1;
        $this->export((int) $Num);
    }
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
    function getRecentPost()
    {
        $page     = (int) self::GET('page', 1);
        $offset   = $this->pageSize * ($page - 1);
        $select   = $this->db->select('cid', 'title', 'authorId ', 'table.contents.created', 'slug', 'commentsNum', 'views', 'likes', 'screenName', 'url')->from('table.contents')->join('table.users', 'table.contents.authorId = table.users.uid', Typecho_DB::LEFT_JOIN)->where('type = ?', 'post')->where('status = ?', 'publish')->where('table.contents.created < ?', time())->order('table.contents.created', Typecho_Db::SORT_DESC)->offset($offset)->limit($this->pageSize);
        $posts  = $this->db->fetchAll($select);
        foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $thumb = "https://blog.cdn.thinkmoon.cn/default.jpg";
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])) ? $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])) : array(array("str_value" => $thumb));
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
        $comments = $this->db->fetchAll($this->db->select('cid', 'coid', 'created', 'text', 'parent', 'table.comments.openid AS openid', 'table.WeBlog_users.nickName AS nickName', 'table.WeBlog_users.avatarUrl AS avatarUrl', 'table.WeBlog_users.gender AS gender')->from('table.comments')->join('table.WeBlog_users', 'table.comments.openid = table.WeBlog_users.openid')->where('cid = ?', $cid)->where('status = ?', 'approved')->order('table.comments.created', Typecho_Db::SORT_DESC));
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
    function getAboutcid()
    {
        $cid = 'none';
        $cid = Typecho_Widget::widget('Widget_Options')->plugin('WeBlog')->aboutCid;

        $this->export($cid);
    }
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
    // 获取AccessToken
    private function getAccessToken()
    {
        // 如果 session中存有 access_token 并且未超过有效期 使用session中的access_token
        if ($_SESSION['access_token'] && $_SESSION['expire_time'] > time()) {
            //$this->export($_SESSION['access_token']);
            return $_SESSION['access_token'];
        } else {
            $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', $this->appID, $this->appSecret);
            $res = json_decode(file_get_contents($url));
            $access_token = $res->access_token;
            //将获取到的access_token存到session 设置过期时间
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expire_time'] = time() + 7000;
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
