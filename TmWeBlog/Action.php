<?php
header('Access-Control-Allow-Origin: https://servicewechat.com/wx53f9b5912c0f0cf6');
class TmWeBlog_Action extends Typecho_Widget implements Widget_Interface_Do {
  private $db;
  private $res;
  public function __construct($request, $response, $params = NULL) {
        parent::__construct($request, $response, $params);
    	
     	$this->appID = Typecho_Widget::widget('Widget_Options')->plugin('TmWeBlog')->appID;
    	$this->appSecret = Typecho_Widget::widget('Widget_Options')->plugin('TmWeBlog')->appSecret;
        $this->avatarUrl = Typecho_Widget::widget('Widget_Options')->plugin('TmWeBlog')->avatarUrl;
    	$this->pageSize = Typecho_Widget::widget('Widget_Options')->plugin('TmWeBlog')->pageSize;
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
  function getPageNum(){
     $select =  $this->db->select('COUNT(cid) AS Num')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish');
     $data = $this->db->fetchAll($select);
     $Num =$data[0]['Num'] / $this->pageSize + 1;
     $this->export((int)$Num);
  }
  // 获取博客总览
  private function getOverview() {
    $select   = $this->db->select('COUNT(cid) AS Num')->from('table.contents');
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
  private function getAuthorInfo(){
    $select   = $this->db->select('*')->from('table.users');
    $lists = $this->db->fetchAll($select);
    $lists[0]['avatarUrl'] = $this->avatarUrl;
	$this->export($lists);
  }
  // 获取最近post
  private function getRecentPost(){
    $page     = (int) self::GET('page', 1);
    $offset   = $this->pageSize * ($page - 1);
    $select   = $this->db->select('cid', 'title','authorId ', 'created', 'slug','commentsNum', 'views', 'likes')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('created < ?', time())->order('table.contents.created', Typecho_Db::SORT_DESC)->offset($offset)->limit($this->pageSize);
    $posts  = $this->db->fetchAll($select);
    foreach ($posts as $post) {
            $post['tag'] = $this->db->fetchAll($this->db->select('name')->from('table.metas')->join('table.relationships', 'table.metas.mid = table.relationships.mid', Typecho_DB::LEFT_JOIN)->where('table.relationships.cid = ?', $post['cid'])->where('table.metas.type = ?', 'tag'));
      		$thumb = "https://www.thinkmoon.cn/usr/themes/armx/img/sj/".mt_rand (1, 8).".jpg";
            $post['thumb'] = $this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid']))?$this->db->fetchAll($this->db->select('str_value')->from('table.fields')->where('cid = ?', $post['cid'])):array(array("str_value"=>$thumb));
            $result[]    = $post;
        }
        $this->export($result);
  }
  // 通过cid获取post
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
  // 获取AccessToken
  function getAccessToken(){
    // 如果 session中存有 access_token 并且未超过有效期 使用session中的access_token
		if( $_SESSION['access_token'] && $_SESSION['expire_time'] > time()) {
            //$this->export($_SESSION['access_token']);
			return $_SESSION['access_token'];
		}else{
           $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',$this->appID,$this->appSecret);
           $res = json_decode(file_get_contents($url));
		   $access_token = $res->access_token;
			//将获取到的access_token存到session 设置过期时间
		   $_SESSION['access_token'] = $access_token ;
		   $_SESSION['expire_time'] = time() + 7000 ;
			return $access_token ;
		}
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