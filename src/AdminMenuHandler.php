<?php

namespace LitaPig\PageGuardWatermark;

defined('WPINC') || exit;

/**
 * 后台菜单助手类
 * Class AdminMenuHandler
 * @package LitaPig\PageGuardWatermark
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
    }

    /**
     * 顶级菜单
     */
    private static function topLevelMenus()
    {
        add_menu_page(
            'Page Guard Watermark List',
            "PageMark",
            'manage_options',
            self::TOP_MENU_SLUG,
            [__NAMESPACE__ . '\Controller\PageGuardWatermark', 'list'],
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
            "List",
            "List",
            "manage_options",
            "page_guard_watermark_list",
            [__NAMESPACE__ . '\Controller\PageGuardWatermark', 'list'],
        );
    }

    /**
     * 当有子菜单时 wordpress 默认把主菜单复制一份为子菜单。主、子菜单同名会不协调，因此这里改名
     */
    private static function customizeWatermarkListSubmenuName()
    {
        global $submenu;

        // 更改第一个子菜单的名称
        if (isset($submenu[LITA_PIG_PGW_WP_NAME])) {
            $submenu[LITA_PIG_PGW_WP_NAME][0][0] = 'All Watermark';
        }
    }
}