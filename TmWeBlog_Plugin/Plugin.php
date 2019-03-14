/**
 * 一个typecho博客的微信小程序版
 * 
 * @package TmWeBlog_Plugin 
 * @author 醉月思
 * @version 0.0.1
 * @link https://github.com/thinkmoon/tmWeBlog
 */
class TmWeBlog_Plugin implements Typecho_Plugin_Interface
{
    /* 激活插件方法 */
    public static function activate(){}
 
    /* 禁用插件方法 */
    public static function deactivate(){}
 
    /* 插件配置方法 */
    public static function config(Typecho_Widget_Helper_Form $form){}
 
    /* 个人用户的配置方法 */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
 
    /* 插件实现方法 */
    public static function render(){}
}