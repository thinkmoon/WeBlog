<?php

/**
 * 一个typecho博客的微信小程序版
 * 
 * @package WeBlog 
 * @author 醉月思
 * @version 1.0.0 dev
 * @link https://github.com/thinkmoon/WeBlog
 */
class WeBlog_Plugin implements Typecho_Plugin_Interface
{
    /* 激活插件方法 */
    public static function activate()
    {
        Helper::addRoute('WeBlog', '/WeBlog/api/[type]', 'WeBlog_Action');
        Helper::addAction('WeBlog', 'WeBlog_Action');
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
    }

    /* 禁用插件方法 */
    public static function deactivate()
    {
        Helper::removeRoute('WeBlog');
        Helper::removeAction('WeBlog');
        Helper::removePanel(1, 'WeBlog/Manage.php');
    }

    /* 插件配置方法 */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $weixinAppID = new Typecho_Widget_Helper_Form_Element_Text('weixinAppID', NULL, 'xxx', _t('微信小程序的APPID'),  _t('小程序的APP ID'));
        $form->addInput($weixinAppID);
        $weixinAppSecret = new Typecho_Widget_Helper_Form_Element_Text('weixinAppSecret', NULL, 'xxx', _t('微信小程序的secret'),  _t('微信小程序的secret'));
        $form->addInput($weixinAppSecret);
        $qqAppID = new Typecho_Widget_Helper_Form_Element_Text('qqAppID', NULL, 'xxx', _t('QQ小程序的APPID'),  _t('小程序的APP ID'));
        $form->addInput($qqAppID);
        $qqAppSecret = new Typecho_Widget_Helper_Form_Element_Text('qqAppSecret', NULL, 'xxx', _t('QQ小程序的secret'),  _t('小程序的secret'));
        $form->addInput($qqAppSecret);
        $sticky = new Typecho_Widget_Helper_Form_Element_Text('sticky', NULL, '1,2,3', _t('置顶文章cid'),  _t('显示在轮播图中'));
        $form->addInput($sticky);
        $apiSecret = new Typecho_Widget_Helper_Form_Element_Text('apiSecret', NULL, 'xxx', _t('你的接口密钥'),  _t('你可以设置一个不容易被他人猜出来的随机字符串'));
        $form->addInput($apiSecret);
    }

    /* 个人用户的配置方法 */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    /* 插件实现方法 */
    public static function render()
    {
    }
}
