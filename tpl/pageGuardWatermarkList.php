<?php

namespace PageGuardWatermark;

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
                <td><input type="text" name="name" value="<?php echo esc_html__('Admin Panel Watermark Configuration', 'page-guard-watermark') ?>" readonly></td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Status', 'page-guard-watermark') ?></th>
                <td>
                    <select name="status" id="">
                        <option value="1"><?php echo esc_html__('Show', 'page-guard-watermark') ?></option>
                        <option value="2"><?php echo esc_html__('Hide', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Rotate Angle', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" placeholder="<?php echo esc_html__('Supports 0~90°', 'page-guard-watermark') ?>" name="rotate_angle" value="45" readonly>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Watermark Margin', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" placeholder="<?php echo esc_html__('Vertical Spacing, e.g. 20', 'page-guard-watermark') ?>" name="vertical">
                    <input type="text" placeholder="<?php echo esc_html__('Horizontal Spacing, e.g. 20', 'page-guard-watermark') ?>" name="horizontal">
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Watermark Generation Type', 'page-guard-watermark') ?></th>
                <td>
                    <select name="watermark_generation_type" disabled>
                        <option value="1"><?php echo esc_html__('Tile', 'page-guard-watermark') ?></option>
                        <option value="2"><?php echo esc_html__('Center', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Matching Rules', 'page-guard-watermark') ?></th>
                <td>
                    <select name="matching_rules" id="" disabled>
                        <option value=""><?php echo esc_html__('All Admin Pages', 'page-guard-watermark') ?></option>
                        <option value=""><?php echo esc_html__('Admin Login Page', 'page-guard-watermark') ?></option>
                        <option value=""><?php echo esc_html__('Custom Paths', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Effective Paths</th>
                <td>
                    <textarea name="effective_paths" id="" cols="30" rows="5"
                              placeholder="<?php echo esc_html__('For multiple paths, please press Enter for each line, with each path on a separate line.', 'page-guard-watermark') ?>"></textarea>
                </td>
            </tr>
            <tr>
                <th>Excluded Paths</th>
                <td>
                    <textarea name="excluded_paths" id="" cols="30" rows="5"
                              placeholder="<?php echo esc_html__('For multiple paths, please press Enter for each line, with each path on a separate line.', 'page-guard-watermark') ?>"></textarea>
                </td>
            </tr>
        </table>

        <hr>
        <h2><?php echo esc_html__('Text Configuration', 'page-guard-watermark') ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th><?php echo esc_html__('Text Content', 'page-guard-watermark') ?></th>
                <td>
                    <textarea name="text_content" id="" cols="30" rows="8" placeholder="e.g. Hello {$admin_name}, please be mindful of data security.
{$now_date}"></textarea>
                    <br>
                    <tip>Supports line breaks, supports variables: {$admin_id}, </tip>
                    <br>
                    <tip>{$admin_name}, {$ip}, {$login_time}, {$now_time}, {$now_date}</tip>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Text Align', 'page-guard-watermark') ?></th>
                <td>
                    <select name="text_align" id="">
                        <option value=""><?php echo esc_html__('Left', 'page-guard-watermark') ?></option>
                        <option value=""><?php echo esc_html__('Right', 'page-guard-watermark') ?></option>
                        <option value=""><?php echo esc_html__('Top', 'page-guard-watermark') ?></option>
                        <option value=""><?php echo esc_html__('Bottom', 'page-guard-watermark') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Size', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" name="size" placeholder="e.g. 24">
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Line Spacing', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" name="line_spacing" placeholder="e.g. 1.2">
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Alpha', 'page-guard-watermark') ?></th>
                <td>
                    <input type="text" placeholder="<?php echo esc_html__('e.g. 0.3', 'page-guard-watermark') ?>" name="alpha">
                    <br>
                    <span><?php echo esc_html__('0~1, 0 as fully transparent, 1 as opaque.', 'page-guard-watermark') ?></span>
                </td>
            </tr>
            <tr>
                <th><?php echo esc_html__('Color', 'page-guard-watermark') ?></th>
                <td><input type="text" name="color" class="color-picker"></td>
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
