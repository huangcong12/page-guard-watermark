<?php

namespace LitaPig\PageGuardWatermark\Controller;

use LitaPig\PageGuardWatermark\MessageHandler;
use RuntimeException;

defined('WPINC') || exit;

class PageGuardWatermark
{
    /**
     * 数据编辑页
     */
    public static function edit()
    {
        try {
            // TODO 权限校验
//        if ( ! current_user_can( 'edit_user', $user_id ) ) {
//            wp_die( __( 'Sorry, you are not allowed to edit this user.' ) );
//        }
            if (is_admin() && 'POST' === $_SERVER['REQUEST_METHOD']) {
                if (check_admin_referer('page_guard_watermark', 'nonce_field') === false) {
                    throw new RuntimeException(esc_html__("The page has expired. Please refresh the page and try again.", "page-guard-watermark"));
                }

                self::save();
            }

        } catch (RuntimeException $exception) {
            MessageHandler::addError($exception->getMessage());
        }

        self::enqueueColorPicker();

        self::showPage();
    }

    /**
     * 加载颜色选择组件
     */
    private static function enqueueColorPicker()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    /**
     * 展示页面内容
     */
    private static function showPage()
    {
        require_once ABSPATH . 'wp-admin/admin-header.php';

        // 查询列表数据
        require_once(LITA_PIG_PGW_WP_DIR . 'tpl/pageGuardWatermarkList.php');
        require_once(LITA_PIG_PGW_WP_DIR . 'tpl/footer.php');
    }

    /**
     * 保存数据
     */
    private static function save()
    {
        var_dump($_POST);
    }
}