<?php
/*
Plugin Name: Page Guard Watermark
Text Domain: page-guard-watermark
Domain Path: /assets/languages
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Protect and Label Your Webpage Content
Author: Hang Cong
Version: 1.0.0
Author URI: https://github.com/huangcong12
*/

use LitaPig\PageGuardWatermark\WatermarkHandler;

defined('WPINC') || exit;

if (!defined('ABSPATH')) {
    exit;
}

!defined('LITA_PIG_PGW_WP_DIR') && define('LITA_PIG_PGW_WP_DIR', __DIR__ . '/');    // 插件目录
!defined('LITA_PIG_PGW_WP_NAME') && define('LITA_PIG_PGW_WP_NAME', 'page_guard_watermark'); // 插件名字

// 自动注册类
require_once LITA_PIG_PGW_WP_DIR . 'autoload.php';

## -------------------------------------------------- ##
## -----------------	插件管理   ------------------- ##
## -------------------------------------------------- ##
register_activation_hook(__FILE__, "LitaPig\\PageGuardWatermark\\PluginActivationHandler::activation"); // 激活时候运行
register_deactivation_hook(__FILE__, "LitaPig\\PageGuardWatermark\\PluginActivationHandler::deactivation"); // 停用的时候运行
register_uninstall_hook(__FILE__, "LitaPig\\PageGuardWatermark\\PluginActivationHandler::uninstall");   // 删除插件

## -------------------------------------------------- ##
## -----------------	菜单管理   ------------------- ##
## -------------------------------------------------- ##
add_action('admin_menu', "LitaPig\\PageGuardWatermark\\AdminMenuHandler::menusManager");    // 顶级菜单

// 展示水印
add_action('init', 'LitaPig\\PageGuardWatermark\\WatermarkHandler::show');

//function pageguard_watermark_scripts()
//{
//    // 注册并添加JavaScript文件
//    wp_register_script('pageguard-watermark-js', plugin_dir_url(__FILE__) . 'js/page-guard-watermark.js', array('jquery'), '1.0', true);
//    wp_enqueue_script('pageguard-watermark-js');

// 注册并添加样式表文件
//    wp_register_style('pageguard-watermark-css', plugin_dir_url(__FILE__) . 'css/page-guard-watermark.css', array(), '1.0', 'all');
//    wp_enqueue_style('pageguard-watermark-css');
//}

//add_action('wp_footer', 'pageguard_watermark_scripts');
