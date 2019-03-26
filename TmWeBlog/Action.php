<?php
header('Access-Control-Allow-Origin: https://servicewechat.com/wx53f9b5912c0f0cf6');
class TmWeBlog_Action extends Typecho_Widget implements Widget_Interface_Do {
  private $db;
  private $res;
  public function __construct($request, $response, $params = NULL) {
        parent::__construct($request, $response, $params);
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
  private function getAuthorInfo(){
    $select   = $this->db->select('*')->from('table.users');
    $lists = $this->db->fetchAll($select);
    $lists[0]['avatarUrl'] = "https://www.thinkmoon.cn/usr/uploads/2018/12/55979974.jpg";
	$this->export($lists);
  }
  private function getRecentPost(){
    $pageSize = (int) self::GET('pageSize', 1000);
    $page     = (int) self::GET('page', 1);
    $offset   = $pageSize * ($page - 1);
    $select   = $this->db->select('cid', 'title','authorId ', 'created', 'slug','commentsNum', 'views', 'likes')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('created < ?', time())->order('table.contents.created', Typecho_Db::SORT_DESC)->offset($offset)->limit($pageSize);
    $posts  = $this->db->fetchAll($select);
    foreach ($posts as $post) {
        $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
      	$thumb = "https://www.thinkmoon.cn/usr/themes/armx/img/sj/".mt_rand (1, 8).".jpg";
        $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid']))?$this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])):array(array("str_value"=>$thumb));
        $result[]    = $post;
    }
    $this->export($result);
  }
  private function getPostBycid(){
    if (isset($_GET['cid'])) {
        $cid = self::GET('cid');
        $select = $this->db->select('cid', 'title', 'created', 'type', 'slug', 'text','commentsNum')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('created < ?', time())->where('cid = ?', $cid);
        //更新点击量数据库
        $row = $this->db->fetchRow($this->db->select('views')->from('table.contents')->where('cid = ?', $cid));
        $this->db->query($this->db->update('table.contents')->rows(array('views' => (int)$row['views']+1))->where('cid = ?', $cid));
        $posts  = $this->db->fetchAll($select);
        foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $result[]    = $post;
        }
        $this->export($result);
    }
  }
  private function post1(){
        $pageSize = (int) self::GET('pageSize', 1000);
        $page     = (int) self::GET('page', 1);
        $authorId = self::GET('authorId', 0);
        $offset   = $pageSize * ($page - 1);
        $getpage = self::GET('getpage', 0);
        $idx     = self::GET('idx',-1);
        
        // 根据cid偏移获取文章
        if (isset($_GET['cid'])) {
            $cid = self::GET('cid');
            if($getpage) {
                $select = $this->db->select('cid', 'title', 'created', 'type', 'slug', 'text','commentsNum')->from('table.contents')->where('type = ?', 'page')->where('status = ?', 'publish')->where('created < ?', time())->order('table.contents.created', Typecho_Db::SORT_DESC)->offset($offset)->limit($pageSize);
            } else {
                $select = $this->db->select('cid', 'title', 'created', 'type', 'slug', 'text','commentsNum')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('created < ?', time())->order('table.contents.created', Typecho_Db::SORT_DESC)->offset($offset)->limit($pageSize);
            }
             $select->where('cid = ?', $cid);
            //更新点击量数据库
            $row = $this->db->fetchRow($this->db->select('views')->from('table.contents')->where('cid = ?', $cid));
            $this->db->query($this->db->update('table.contents')->rows(array('views' => (int)$row['views']+1))->where('cid = ?', $cid));
        }
        else
        {
            //如果不指定具体文章CID，不抓取text
            $select   = $this->db->select('cid', 'title', 'created', 'type', 'slug','commentsNum')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('created < ?', time())->order('table.contents.created', Typecho_Db::SORT_DESC)->offset($offset)->limit($pageSize);
        }
        // 根据分类或标签获取文章
        if (isset($_GET['category']) || isset($_GET['tag'])) {
            $name     = isset($_GET['category']) ? $_GET['category'] : $_GET['tag'];
            $resource = $this->db->fetchAll($this->db->select('cid')->from('table.relationships')->join('table.metas', 'table.metas.mid = table.relationships.mid', Typecho_Db::LEFT_JOIN)->where('slug = ?', $name));
            $cids     = array();
            foreach ($resource as $item) {
                $cids[] = $item['cid'];
            }
            $select->where('cid IN ?', $cids);
        }
        if($idx>=0){
            switch($idx)
            {
                case 0:
                    //浏览量
                    $select->order('table.contents.views', Typecho_Db::SORT_DESC);
                    break;
                case 1:
                    //评论数
                    $select->order('table.contents.commentsNum', Typecho_Db::SORT_DESC);
                    break;
                case 2:
                    //点赞数
                    $select->order('table.contents.likes', Typecho_Db::SORT_DESC);
                    break;
                default:
                    break;
            }
        }
        $posts  = $this->db->fetchAll($select);
        $result = array();
        $temp = Typecho_Widget::widget('Widget_Options')->plugin('WeTypecho')->hiddenShare;
        foreach ($posts as $post) {
            $post        = $this->widget("Widget_Abstract_Contents")->push($post);
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid']))?$this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])):array(array("str_value"=>"https://api.isoyu.com/bing_images.php"));
            $post['views'] = $this->db->fetchAll($this->db->select('views')->from('table.contents')->where('table.contents.cid = ?', $post['cid']));
            $post['likes'] = $this->db->fetchAll($this->db->select('likes')->from('table.contents')->where('table.contents.cid = ?', $post['cid']));
            $post['showshare'] = $temp;
            $result[]    = $post;
        }
        $this->export($result);
  }
  public function export($data = array(), $status = 200) {
        $this->res->throwJson(array(
            'status' => $status,
            'data' => $data
        ));
        exit;
    }
    public static function GET($key, $default = '') {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    public function action() {
        $this->on($this->request);
    }
  	private function getaccesscode()
    {
        if($path == 'null') {
            $path = 'page/index/index';
        }
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',$this->appid,$this->appsecret);
        $info = file_get_contents($url);
        $json = json_decode($info);
        $arr = get_object_vars($json);
        $accesscode = $arr['access_token'];
        $url_1 = sprintf('https://api.weixin.qq.com/wxa/getwxacode?access_token=%s',$accesscode);
        //$qrurl = $arr_t['access_token'];
        $post_data = array(
            'path' => $path
        );
        $jsonStr = json_encode($post_data);
        $qrcode = $this->http_post_data($url_1, $jsonStr);
        $filename = 'qrcode.png';
        $write_fd = @fopen($filename,'w+');
        if( fwrite($write_fd, $qrcode) ) {
            fclose($write_fd);
            $this->export($filename);
        }
        $this->export("error");
    }

    function http_post_data($url, $data_string) {  
  
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
            'Content-Type: application/json; charset=utf-8',  
            'Content-Length: ' . strlen($data_string))  
        );  
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
		//echo $return_content."<br>";
        ob_end_clean();  
  
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      //  return array($return_code, $return_content);  
	  return  $return_content;
    } 
}