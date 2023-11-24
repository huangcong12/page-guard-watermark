<?php
/*
Plugin Name: Page Guard Watermark
Text Domain: page-guard-watermark
Domain Path: /assets/languages
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Add a secure watermark to your WordPress admin pages in a simple and user-friendly way.
Author: Cong Hang
Version: 1.0.0
Author URI: https://github.com/huangcong12
*/

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
add_action('admin_menu', "LitaPig\\PageGuardWatermark\\AdminMenuHandler::menusManager");

// 展示水印
add_action('init', 'LitaPig\\PageGuardWatermark\\WatermarkHandler::show');
