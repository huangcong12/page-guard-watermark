<?php

namespace LitaCat\PageGuardWatermark\Controller;

use LitaCat\PageGuardWatermark\MessageHandler;
use RuntimeException;

defined('WPINC') || exit;

class PageGuardWatermark
{
    /**
     * 后台水印名字
     */
    const ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION = "Admin Panel Watermark Conf";

    /**
     * 数据编辑页
     */
    public static function edit()
    {
        try {
            // TODO 权限校验
//            if (!current_user_can('edit_page_guard_watermark', get_current_user_id())) {
//                wp_die(__('Sorry, you are not allowed to edit this user.'));
//            }
            if (is_admin() && 'POST' === $_SERVER['REQUEST_METHOD']) {
                if (check_admin_referer('page_guard_watermark', 'nonce_field') === false) {
                    throw new RuntimeException(esc_html__("The page has expired. Please refresh the page and try again.", "page-guard-watermark"));
                }

                // 参数校验
                $status = (int)$_POST['status'];
                if (empty($status) || !in_array($status, [1, 2])) {
                    throw new RuntimeException(esc_html__("Status entry is incorrect. Please refresh the page and try again.", "page-guard-watermark"));
                }
                $rotateAngle = (int)$_POST['rotate_angle'];
                if (!empty($rotateAngle) && ($rotateAngle < 0 || $rotateAngle > 90)) {
                    throw new RuntimeException(esc_html__("Rotate Angle entry is incorrect. Only values between 0 and 90 are allowed. Please refresh the page and try again.", "page-guard-watermark"));
                }
                $vertical = (int)$_POST['vertical'];
                $horizontal = (int)$_POST['horizontal'];
                $textContent = wp_kses_data($_POST['text_content']);
//                $textContent = $_POST['text_content'];
                if (empty($textContent)) {
                    throw new RuntimeException(esc_html__("Text Content entry is incorrect. It cannot be empty. If you wish to hide the watermark, you can achieve that by changing the status to 'Hide'.", "page-guard-watermark"));
                }
                $size = (int)$_POST['text_size'];
                if (empty($size)) {
                    throw new RuntimeException(esc_html__("Size entry is incorrect. It cannot be empty.", "page-guard-watermark"));
                }
                $textLineSpacing = (float)$_POST['text_line_spacing'];
                $textAlpha = (float)$_POST['text_alpha'];
                $textColor = sanitize_text_field($_POST['text_color']);

                $saveRes = self::save($status, $rotateAngle, $vertical, $horizontal, $textContent, $size, $textLineSpacing, $textAlpha, $textColor);
                if (!$saveRes) {
                    throw new RuntimeException(esc_html__("Save failed. Please retry.", "page-guard-watermark"));
                }

                // 设置页面刷新
                self::refreshPage();
                return;
            }

        } catch (RuntimeException $exception) {
            MessageHandler::addError($exception->getMessage());
        }

        // 查询数据用于展示
        $data = self::getData();
        // 初始化颜色选择控件
        self::enqueueColorPicker();
        // 展示页面
        self::showPage($data);
    }

    /**
     * 更新完成刷新页面，规避需要手动刷新才能看到效果的问题
     */
    public static function refreshPage()
    {
        echo '<script>location.reload();</script>';
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
        require_once(LITA_CAT_PGW_WP_DIR . 'tpl/pageGuardWatermarkList.php');
//        require_once(LITA_CAT_PGW_WP_DIR . 'tpl/footer.php');
    }

    /**
     * 保存数据
     */
    private static function save($status, $rotateAngle, $vertical, $horizontal, $textContent, $size, $textLineSpacing, $textAlpha, $textColor)
    {
        $saveData = [
            'status' => $status,
            'rotate_angle' => $rotateAngle,
            'vertical' => $vertical,
            'horizontal' => $horizontal,
            'text_content' => $textContent,
            'text_size' => $size,
            'text_line_spacing' => $textLineSpacing,
            'text_alpha' => $textAlpha,
            'text_color' => $textColor,
        ];
        $currentData = get_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION);

        // 无变化直接返回，否则 update_option 会返回 false
        if (md5(serialize($saveData)) == md5(serialize($currentData))) {
            return true;
        }

        // 存在则更新
        if (!empty($currentData)) {
            return update_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION, $saveData);
        }

        return add_option(self::ADMIN_PANEL_WATERMARK_CONFIGURATION_OPTION, $saveData, '', 'no');
    }
}