<?php
header('Access-Control-Allow-Origin: https://servicewechat.com/wx53f9b5912c0f0cf6');
class TmWeBlog_Action extends Typecho_Widget implements Widget_Interface_Do {
  private $res;
  public function __construct($request, $response, $params = NULL) {
        parent::__construct($request, $response, $params);
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
  private function test(){
    $this->export("test");
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
}