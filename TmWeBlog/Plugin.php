<?php
/**
 * 一个typecho博客的微信小程序版
 * 
 * @package TmWeBlog 
 * @author 醉月思
 * @version 0.0.1
 * @link https://github.com/thinkmoon/tmWeBlog
 */
class TmWeBlog_Plugin implements Typecho_Plugin_Interface
{
    /* 激活插件方法 */
    public static function activate(){
      Helper::addRoute('TmWeBlog', '/TmWeBlog/api/[type]', 'TmWeBlog_Action');
      Helper::addAction('TmWeBlog', 'WeTypecho_Action');
      Helper::addPanel(1, 'TmWeBlog/manage.php', 'TmWeBlog', '管理', 'administrator');
    }
 
    /* 禁用插件方法 */
    public static function deactivate(){
      Helper::removePanel(1, 'TmWeBlog/manage.php');
    }
 
    /* 插件配置方法 */
    public static function config(Typecho_Widget_Helper_Form $form){
      $appID = new Typecho_Widget_Helper_Form_Element_Text('appid', NULL, 'xxx', _t('微信小程序的APPID'),  _t('小程序的APP ID'));
      $form->addInput($appID);
    }
 
    /* 个人用户的配置方法 */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
 
    /* 插件实现方法 */
    public static function render(){}
}