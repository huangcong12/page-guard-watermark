<?php

namespace PageGuardWatermark;

use LitaPig\PageGuardWatermark\ArrayHelper;

defined('WPINC') || exit;
?>


<div class="admin-content">
    <h1><?php echo esc_html__('Watermark Settings', 'page-guard-watermark') ?></h1>
    <hr class="wp-header-end">

    <form id="your-profile" action="" method="post" novalidate="novalidate">
        <h2>Watermark Configuration</h2>
        <?php wp_nonce_field('page_guard_watermark', 'nonce_field'); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th><?php echo esc_html__('Name', 'page-guard-watermark') ?></th>
                <td><input type="text" name="name" value="<?php echo ArrayHelper::safeGet($data, 'name') ?>" readonly>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Status', 'page-guard-watermark') ?></th>
                <td>
                    <select name="status" id="">
                        <option value=1 <?php echo ArrayHelper::safeEqual($data, 'status', 1) ? "selected" : "" ?>><?php echo esc_html__('Show', 'page-guard-watermark') ?></option>
                        <option value=2 <?php echo ArrayHelper::safeEqual($data, 'status', 2) ? "selected" : "" ?>><?php echo esc_html__('Hide', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Rotate Angle', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" placeholder="<?php echo esc_html__('Supports 0~90°', 'page-guard-watermark') ?>"
                           name="rotate_angle" value="<?php echo ArrayHelper::safeGet($data, 'rotate_angle') ?>" readonly>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Watermark Margin', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text"
                           placeholder="<?php echo esc_html__('Vertical Spacing, e.g. 20', 'page-guard-watermark') ?>"
                           name="vertical" value="<?php echo ArrayHelper::safeGet($data, 'vertical') ?>">
                    <input type="text"
                           placeholder="<?php echo esc_html__('Horizontal Spacing, e.g. 20', 'page-guard-watermark') ?>"
                           name="horizontal" value="<?php echo ArrayHelper::safeGet($data, 'horizontal') ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Watermark Generation Type', 'page-guard-watermark') ?></th>
                <td>
                    <select name="watermark_generation_type" disabled>
                        <option value=1><?php echo esc_html__('Tile', 'page-guard-watermark') ?></option>
                        <option value=2><?php echo esc_html__('Center', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Matching Rules', 'page-guard-watermark') ?></th>
                <td>
                    <select name="matching_rules" id="" disabled>
                        <option value=1><?php echo esc_html__('All Admin Pages', 'page-guard-watermark') ?></option>
                        <option value=2><?php echo esc_html__('Admin Login Page', 'page-guard-watermark') ?></option>
                        <option value=3><?php echo esc_html__('Custom Paths', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Effective Paths</th>
                <td>
                    <textarea name="effective_paths" id="" cols="30" rows="5"
                              placeholder="<?php echo esc_html__('For multiple paths, please press Enter for each line, with each path on a separate line.', 'page-guard-watermark') ?>"
                    ><?php echo ArrayHelper::safeGet($data, 'effective_paths') ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Excluded Paths</th>
                <td>
                    <textarea name="excluded_paths" id="" cols="30" rows="5" disabled
                              placeholder="<?php echo esc_html__('For multiple paths, please press Enter for each line, with each path on a separate line.', 'page-guard-watermark') ?>"
                    ><?php echo ArrayHelper::safeGet($data, 'excluded_paths') ?></textarea>
                </td>
            </tr>
        </table>

        <hr>
        <h2><?php echo esc_html__('Text Configuration', 'page-guard-watermark') ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th><?php echo esc_html__('Text Content (required)', 'page-guard-watermark') ?></th>
                <td>
                    <textarea name="text_content" id="" cols="30" rows="8" placeholder="e.g. Hello {$admin_name}, please be mindful of data security.
{$now_date}"><?php echo ArrayHelper::safeGet($data, 'text_content') ?></textarea>
                    <br>
                    <tip>Supports line breaks, supports variables: {$admin_id},</tip>
                    <br>
                    <tip>{$admin_name}, {$ip}, {$login_time}, {$now_time}, {$now_date}</tip>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Text Align (required)', 'page-guard-watermark') ?></th>
                <td>
                    <select name="text_align" id="">
                        <option value=1 <?php echo ArrayHelper::safeEqual($data, 'text_align', 1) ? "selected" : "" ?>><?php echo esc_html__('Left', 'page-guard-watermark') ?></option>
                        <option value=2 <?php echo ArrayHelper::safeEqual($data, 'text_align', 2) ? "selected" : "" ?>><?php echo esc_html__('Right', 'page-guard-watermark') ?></option>
                        <option value=3 <?php echo ArrayHelper::safeEqual($data, 'text_align', 3) ? "selected" : "" ?>><?php echo esc_html__('Top', 'page-guard-watermark') ?></option>
                        <option value=4 <?php echo ArrayHelper::safeEqual($data, 'text_align', 4) ? "selected" : "" ?>><?php echo esc_html__('Bottom', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Size (required)', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" name="text_size" placeholder="e.g. 24" value="<?php echo ArrayHelper::safeGet($data, 'text_size') ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Line Spacing', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" name="text_line_spacing" placeholder="e.g. 1.2" value="<?php echo ArrayHelper::safeGet($data, 'text_line_spacing') ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Alpha', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" placeholder="<?php echo esc_html__('e.g. 0.3', 'page-guard-watermark') ?>"
                           name="text_alpha" value="<?php echo ArrayHelper::safeGet($data, 'text_alpha') ?>">
                    <br>
                    <span><?php echo esc_html__('0~1, 0 as fully transparent, 1 as opaque.', 'page-guard-watermark') ?></span>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Color', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" name="text_color" class="color-picker" value="<?php echo ArrayHelper::safeGet($data, 'text_color') ?>">
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Changes')); ?>
    </form>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('.color-picker').wpColorPicker();
    });
</script>
