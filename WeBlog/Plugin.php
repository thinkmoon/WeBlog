<?php

/**
 * 一个typecho博客的微信小程序版
 * 
 * @package WeBlog 
 * @author 醉月思
 * @version 0.1.9
 * @link https://github.com/thinkmoon/WeBlog
 */
class WeBlog_Plugin implements Typecho_Plugin_Interface
{
    /* 激活插件方法 */
    public static function activate()
    {
        Helper::addRoute('WeBlog', '/WeBlog/api/[type]', 'WeBlog_Action');
        Helper::addAction('WeBlog', 'WeBlog_Action');
        Helper::addPanel(1, 'WeBlog/manage.php', 'WeBlog', '管理', 'administrator');
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
        Helper::removePanel(1, 'WeBlog/manage.php');
    }

    /* 插件配置方法 */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $weixinAppID = new Typecho_Widget_Helper_Form_Element_Text('weixinAppID', NULL, 'wx53f9b5912c0f0cf6', _t('微信小程序的APPID'),  _t('小程序的APP ID'));
        $form->addInput($weixinAppID);
        $weixinAppSecret = new Typecho_Widget_Helper_Form_Element_Text('weixinAppSecret', NULL, '77376fa2b2dea8a18de2be5a6c56dbdf', _t('微信小程序的secret'),  _t('微信小程序的secret'));
        $form->addInput($weixinAppSecret);
        $qqAppID = new Typecho_Widget_Helper_Form_Element_Text('qqAppID', NULL, '1109992579', _t('QQ小程序的APPID'),  _t('小程序的APP ID'));
        $form->addInput($qqAppID);
        $qqAppSecret = new Typecho_Widget_Helper_Form_Element_Text('qqAppSecret', NULL, 'nXrapk4Lkd35ilOY', _t('QQ小程序的secret'),  _t('小程序的secret'));
        $form->addInput($qqAppSecret);
        $avatarUrl = new Typecho_Widget_Helper_Form_Element_Text('avatarUrl', NULL, 'https://www.thinkmoon.cn/usr/uploads/2018/12/55979974.jpg', _t('你的博客头像'),  _t('显示在小程序的头像'));
        $form->addInput($avatarUrl);
        $thumb = new Typecho_Widget_Helper_Form_Element_Text('thumb', NULL, 'https://blog.cdn.thinkmoon.cn/default.jpg', _t('默认缩略图'),  _t('显示在小程序的文章默认缩略图'));
        $form->addInput($thumb);
        $pageSize = new Typecho_Widget_Helper_Form_Element_Text('pageSize', NULL, '7', _t('每页文章数'),  _t('请不要留空'));
        $form->addInput($pageSize);
        $aboutCid = new Typecho_Widget_Helper_Form_Element_Text('aboutCid', NULL, '2', _t('关于页面CID'),  _t('小程序关于页面显示文章cid'));
        $form->addInput($aboutCid);
    }

    /* 个人用户的配置方法 */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    { }

    /* 插件实现方法 */
    public static function render()
    { }
}
