<?php

namespace LitaPig\PageGuardWatermark\Controller;

use LitaPig\PageGuardWatermark\MessageHandler;
use RuntimeException;

defined('WPINC') || exit;

class PageGuardWatermark
{
    /**
     * 后台水印名字
     */
    const ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION = "Admin Panel Watermark Configuration";

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

                // 参数校验
                $status = (int)$_POST['status'];
                if (empty($status) || !in_array($status, [1, 2])) {
                    throw new RuntimeException(esc_html__("参数 status 有误，请刷新页面重试", "page-guard-watermark"));
                }
                $rotateAngle = (int)$_POST['rotate_angle'];
                if (!empty($rotateAngle) && ($rotateAngle < 0 || $rotateAngle > 90)) {
                    throw new RuntimeException(esc_html__("参数 Rotate Angle 有误，请刷新页面重试", "page-guard-watermark"));
                }
                $vertical = (int)$_POST['vertical'];
                $horizontal = (int)$_POST['horizontal'];
                $textContent = sanitize_text_field($_POST['text_content']);
                if (empty($textContent)) {
                    throw new RuntimeException(esc_html__("参数 Text Content 有误，不允许为空，假如希望不展示水印，可以通过修改状态为'Hide'", "page-guard-watermark"));
                }
                $textAlign = (int)$_POST['text_align'];
                if (!empty($textAlign) && !in_array($textAlign, [1, 2, 3, 4])) {
                    throw new RuntimeException(esc_html__("参数 Text Align 有误，请刷新页面重试", "page-guard-watermark"));
                }
                $size = (int)$_POST['text_size'];
                if (empty($size)) {
                    throw new RuntimeException(esc_html__("参数 Size 有误，不允许为空", "page-guard-watermark"));
                }
                $textLineSpacing = (float)$_POST['text_line_spacing'];
                $textAlpha = (float)$_POST['text_alpha'];
                $textColor = sanitize_text_field($_POST['text_color']);

                $saveRes = self::save($status, $rotateAngle, $vertical, $horizontal, $textContent, $textAlign, $size, $textLineSpacing, $textAlpha, $textColor);
                if (!$saveRes) {
                    throw new RuntimeException(esc_html__("保存失败，请重试", "page-guard-watermark"));
                }
            }

        } catch (RuntimeException $exception) {
            MessageHandler::addError($exception->getMessage());
        }

        // 查询数据用于展示
        $data = self::getData();

        self::enqueueColorPicker();

        self::showPage($data);
    }

    /**
     * 获取数据
     */
    public static function getData()
    {
        $data = (array)get_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION);
        // 默认名字
        if (!isset($data['name']) || empty($data['name'])) {
            $data['name'] = self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION;
        }
        // 默认生效路径
        if (!isset($data['effective_paths']) || empty($data['effective_paths'])) {
            $data['effective_paths'] = wp_make_link_relative(admin_url()) . '*';
        }

        return $data;
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
    private static function showPage($data)
    {
        require_once ABSPATH . 'wp-admin/admin-header.php';

        // 查询列表数据
        require_once(LITA_PIG_PGW_WP_DIR . 'tpl/pageGuardWatermarkList.php');
        require_once(LITA_PIG_PGW_WP_DIR . 'tpl/footer.php');
    }

    /**
     * 保存数据
     */
    private static function save($status, $rotateAngle, $vertical, $horizontal, $textContent, $textAlign, $size, $textLineSpacing, $textAlpha, $textColor)
    {
        // 存在则更新
        if (get_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION)) {
            return update_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION, [
                'status' => $status,
                'rotate_angle' => $rotateAngle,
                'vertical' => $vertical,
                'horizontal' => $horizontal,
                'text_content' => $textContent,
                'text_align' => $textAlign,
                'text_size' => $size,
                'text_line_spacing' => $textLineSpacing,
                'text_alpha' => $textAlpha,
                'text_color' => $textColor,
            ]);
        }

        return add_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION, [
            'status' => $status,
            'rotate_angle' => $rotateAngle,
            'vertical' => $vertical,
            'horizontal' => $horizontal,
            'text_content' => $textContent,
            'text_align' => $textAlign,
            'text_size' => $size,
            'text_line_spacing' => $textLineSpacing,
            'text_alpha' => $textAlpha,
            'text_color' => $textColor,
        ], '', 'no');
    }
}