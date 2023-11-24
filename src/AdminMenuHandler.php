<?php

namespace LitaCat\PageGuardWatermark;

defined('WPINC') || exit;

/**
 * 后台菜单助手类
 * Class AdminMenuHandler
 * @package LitaCat\PageGuardWatermark
 */
class AdminMenuHandler
{
    const TOP_MENU_SLUG = 'page_guard_watermark';

    /**
     * 插件菜单管理
     */
    public static function menusManager()
    {
        self::topLevelMenus();

        self::subMenus();
        // 调整首个子菜单名字
        self::customizeWatermarkListSubmenuName();

        // 修改按钮颜色
        self::adjustProMenuItem();
        add_action('admin_head', "LitaCat\\PageGuardWatermark\\AdminMenuHandler::admin_menu_styles", 11);
    }

    /**
     * Output inline styles for the admin menu.
     *
     * @since 1.7.8
     */
    public static function admin_menu_styles()
    {

//        $styles = '#adminmenu .wpforms-menu-new { color: #f18500; vertical-align: super; font-size: 9px; font-weight: 600; padding-left: 2px; }';

//        if (!wpforms()->is_pro()) {
        $styles = 'a.page-guard-watermark-sidebar-upgrade-pro { background-color: #00a32a !important; color: #fff !important; font-weight: 600 !important; }';
//        }

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        printf('<style>%s</style>', $styles);
    }

    /**
     * 顶级菜单
     */
    private static function topLevelMenus()
    {
        add_menu_page(
            esc_html__('Page Guard Watermark List', 'page-guard-watermark'),
            esc_html__("PageMark", 'page-guard-watermark'),
            'manage_options',
            self::TOP_MENU_SLUG,
            [__NAMESPACE__ . '\Controller\PageGuardWatermark', 'edit'],
            'dashicons-images-alt',
            20
        );
    }

    /**
     * 二级菜单
     */
    private static function subMenus()
    {
        add_submenu_page(self::TOP_MENU_SLUG,
            esc_html__("Upgrade to Pro", 'page-guard-watermark'),
            esc_html__("Upgrade to Pro", 'page-guard-watermark'),
            "manage_options",
            "page_guard_watermark_upgrade_to_pro",
            [__NAMESPACE__ . '\Controller\UpgradeToPro', 'show'],
        );
    }

    /**
     * 给“Upgrade to Pro” 按钮增加一个 class，方便上颜色
     */
    private static function adjustProMenuItem()
    {
        global $submenu;
        $upgradeButtonPosition = count($submenu[self::TOP_MENU_SLUG]) - 1;
        if ($upgradeButtonPosition < 1) {
            return;
        }

        if (isset($submenu[self::TOP_MENU_SLUG][$upgradeButtonPosition][4])) {
            $submenu[self::TOP_MENU_SLUG][$upgradeButtonPosition][4] .= ' page-guard-watermark-sidebar-upgrade-pro';
        } else {
            $submenu[self::TOP_MENU_SLUG][$upgradeButtonPosition][] = 'page-guard-watermark-sidebar-upgrade-pro';
        }
    }


    /**
     * 当有子菜单时 wordpress 默认把主菜单复制一份为子菜单。主、子菜单同名会不协调，因此这里改名
     */
    private static function customizeWatermarkListSubmenuName()
    {
        global $submenu;

        // 更改第一个子菜单的名称
        if (isset($submenu[LITA_CAT_PGW_WP_NAME])) {
            $submenu[LITA_CAT_PGW_WP_NAME][0][0] = esc_html__('Watermark Settings', 'page-guard-watermark');
        }
    }
}