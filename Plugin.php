<?php
/**
 * 微语 - 发表心情动态的独立页面插件
 * 
 * @package Whisper
 * @author 执迷
 * @version 1.0.0
 * @link https://blog.zhangmingrui.top
 */
class Whisper_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        // 创建数据表
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}whispers` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `content` text NOT NULL,
            `images` text,
            `os_info` varchar(100),
            `likes` int(11) DEFAULT 0,
            `created_at` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        try {
            $db->query($sql);
            
            // 检查是否需要添加 likes 字段（兼容旧版本）
            $hasLikes = false;
            $columns = $db->fetchAll($db->query("SHOW COLUMNS FROM `{$prefix}whispers`"));
            foreach ($columns as $column) {
                if ($column['Field'] == 'likes') {
                    $hasLikes = true;
                    break;
                }
            }
            
            if (!$hasLikes) {
                $db->query("ALTER TABLE `{$prefix}whispers` ADD `likes` int(11) DEFAULT 0");
            }
        } catch (Exception $e) {
            throw new Typecho_Plugin_Exception('数据表创建失败: ' . $e->getMessage());
        }
        
        // 注册 Action
        Helper::addAction('whisper', 'Whisper_Action');
        
        return '插件安装成功！请创建独立页面，使用自定义模板 whisper.php';
    }
    
    public static function deactivate()
    {
        Helper::removeAction('whisper');
    }
    
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $pageTitle = new Typecho_Widget_Helper_Form_Element_Text(
            'pageTitle',
            NULL,
            '有什么新鲜事想告诉大家？',
            _t('登录后页面标题'),
            _t('登录状态下显示在微语页面顶部的标题')
        );
        $form->addInput($pageTitle);
        
        $guestTitle = new Typecho_Widget_Helper_Form_Element_Text(
            'guestTitle',
            NULL,
            '岁月无声，唯有文字留痕。',
            _t('未登录页面标题'),
            _t('未登录状态下显示在微语页面顶部的标题，留空则不显示')
        );
        $form->addInput($guestTitle);
        
        $showGuestTitle = new Typecho_Widget_Helper_Form_Element_Radio(
            'showGuestTitle',
            array('1' => '显示', '0' => '不显示'),
            '1',
            '是否显示未登录标题',
            '选择是否在未登录状态下显示页面标题'
        );
        $form->addInput($showGuestTitle);
        
        $placeholder = new Typecho_Widget_Helper_Form_Element_Text(
            'placeholder',
            NULL,
            '发表您的新鲜事儿...',
            _t('输入框提示文字'),
            _t('发表框的占位符文字')
        );
        $form->addInput($placeholder);
        
        $pageSize = new Typecho_Widget_Helper_Form_Element_Text(
            'pageSize',
            NULL,
            '10',
            _t('每页显示数量'),
            _t('每页显示多少条微语')
        );
        $form->addInput($pageSize);
        
        $allowUpload = new Typecho_Widget_Helper_Form_Element_Radio(
            'allowUpload',
            array('1' => '允许', '0' => '不允许'),
            '1',
            '是否允许上传图片',
            '开启后可以在微语中上传图片'
        );
        $form->addInput($allowUpload);
    }
    
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }
}
