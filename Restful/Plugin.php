<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}
/**
 * Typecho WeBlog Restful 插件
 *
 * @package Restful
 * @author 醉月思
 * @version 1.1.0
 * @link https://thinkmoon.cn
 */
class Restful_Plugin implements Typecho_Plugin_Interface
{
    const ACTION_CLASS = 'Restful_Action';

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        $routes = call_user_func(array(self::ACTION_CLASS, 'getRoutes'));
        foreach ($routes as $route) {
            Helper::addRoute($route['name'], $route['uri'], self::ACTION_CLASS, $route['action']);
        }

        Helper::addPanel(1, 'WeBlog/Manage.php', 'WeBlog', '管理', 'administrator');

        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        //创建用户数据库
        $scripts = file_get_contents('usr/plugins/WeBlog/sql/users.sql');
        $scripts = str_replace('typecho_', $prefix, $scripts);
        $scripts = explode(';', $scripts);
        try {
            if (!$db->fetchRow($db->query("SHOW TABLES LIKE '{$prefix}WeBlog_users';", Typecho_Db::READ))) {
                foreach ($scripts as $script) {
                    $script = trim($script);
                    if ($script) {
                        $db->query($script, Typecho_Db::WRITE);
                    }
                }
            }
        } catch (Typecho_Db_Exception $e) {
            throw new Typecho_Plugin_Exception(_t('数据表建立失败，插件启用失败，错误信息：%s。', $e->getMessage()));
        } catch (Exception $e) {
            throw new Typecho_Plugin_Exception($e->getMessage());
        }
        //创建赞数据库
        $scriptslike = file_get_contents('usr/plugins/WeBlog/sql/like.sql');
        $scriptslike = str_replace('typecho_', $prefix, $scriptslike);
        $scriptslike = explode(';', $scriptslike);
        try {
            if (!$db->fetchRow($db->query("SHOW TABLES LIKE '{$prefix}WeBlog_like';", Typecho_Db::READ))) {
                foreach ($scriptslike as $script) {
                    $script = trim($script);
                    if ($script) {
                        $db->query($script, Typecho_Db::WRITE);
                    }
                }
            }
        } catch (Typecho_Db_Exception $e) {
            throw new Typecho_Plugin_Exception(_t('数据表建立失败，插件启用失败，错误信息：%s。', $e->getMessage()));
        } catch (Exception $e) {
            throw new Typecho_Plugin_Exception($e->getMessage());
        }
        //创建赞数据库
        try {
            //增加点赞和阅读量
            if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
                $db->query(
                    'ALTER TABLE `' . $prefix
                        . 'contents` ADD `views` INT DEFAULT 0;'
                );
            }
            if (!array_key_exists('likes', $db->fetchRow($db->select()->from('table.contents')))) {
                $db->query(
                    'ALTER TABLE `' . $prefix
                        . 'contents` ADD `likes` INT DEFAULT 0;'
                );
            }
            if (!array_key_exists('openid', $db->fetchRow($db->select()->from('table.comments')))) {
                $db->query(
                    'ALTER TABLE `' . $prefix
                        . 'comments` ADD `openid` varchar(500) DEFAULT NULL;'
                );
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }

        return '_(:з」∠)_';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        Helper::removePanel(1, 'WeBlog/Manage.php');

        $routes = call_user_func(array(self::ACTION_CLASS, 'getRoutes'));
        foreach ($routes as $route) {
            Helper::removeRoute($route['name']);
        }

        return '( ≧Д≦)';
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /* cross-origin settings */
        $origin = new Typecho_Widget_Helper_Form_Element_Textarea('origin', null, null, _t('域名列表'), _t('一行一个<br>以下是例子qwq<br>http://localhost:8080<br>https://blog.example.com<br>若不限制跨域域名，请使用通配符 *。'));
        $form->addInput($origin);

        /* CSRF token salt */
        $csrfSalt = new Typecho_Widget_Helper_Form_Element_Text('csrfSalt', null, '05faabd6637f7e30c797973a558d4372', _t('CSRF加密盐'), _t('请务必修改本参数，以防止跨站攻击。'));
        $form->addInput($csrfSalt);

        $apiSecret = new Typecho_Widget_Helper_Form_Element_Text('apiSecret', NULL, 'xxx', _t('接口密钥'),  _t('必须与客户端的保持一致'));
        $form->addInput($apiSecret);

        $weixinAppID = new Typecho_Widget_Helper_Form_Element_Text('weixinAppID', NULL, 'xxx', _t('微信小程序的APPID'),  _t('小程序的APP ID'));
        $form->addInput($weixinAppID);
        $weixinAppSecret = new Typecho_Widget_Helper_Form_Element_Text('weixinAppSecret', NULL, 'xxx', _t('微信小程序的secret'),  _t('微信小程序的secret'));
        $form->addInput($weixinAppSecret);
        $qqAppID = new Typecho_Widget_Helper_Form_Element_Text('qqAppID', NULL, 'xxx', _t('QQ小程序的APPID'),  _t('小程序的APP ID'));
        $form->addInput($qqAppID);
        $qqAppSecret = new Typecho_Widget_Helper_Form_Element_Text('qqAppSecret', NULL, 'xxx', _t('QQ小程序的secret'),  _t('小程序的secret'));
        $form->addInput($qqAppSecret);
        $sticky = new Typecho_Widget_Helper_Form_Element_Text('sticky', NULL, '1,2,3', _t('置顶文章cid'),  _t('暂无用户'));
        $form->addInput($sticky);
        $aboutCid = new Typecho_Widget_Helper_Form_Element_Text('aboutCid', NULL, '2', _t('关于页的cid'),  _t('显示在关于页的文章'));
        $form->addInput($aboutCid);

        $prefix = defined('__TYPECHO_RESTFUL_PREFIX__') ? __TYPECHO_RESTFUL_PREFIX__ : '/api/';
        /* API switcher */
        $routes = call_user_func(array(self::ACTION_CLASS, 'getRoutes'));
        echo '<h3>API 状态设置</h3>';
        echo '<h4>API BASE_URL' . $prefix . '</h4>';

        foreach ($routes as $route) {
            if ($route['shortName'] == 'upgrade') {
                continue;
            }
            $tmp = new Typecho_Widget_Helper_Form_Element_Radio($route['shortName'], array(
                0 => _t('禁用'),
                1 => _t('启用'),
            ), 1, $route['uri'], _t($route['description']));
            $form->addInput($tmp);
        }
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }
}
