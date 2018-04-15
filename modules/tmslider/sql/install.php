<?php
/**
 * 2002-2017 TemplateMonster
 *
 * TM Slider
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    TemplateMonster
 *  @copyright 2002-2017 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_slider` (
    `id_slider` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY  (`id_slider`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_slider_lang` (
    `id_slider` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` varchar(150),
    PRIMARY KEY  (`id_slider`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_sliders_slides` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
    `id_slider` int(11) NOT NULL,
    `id_slide` int(11) NOT NULL,
    `status` int(11) NOT NULL,
    `order` int(11) NOT NULL,
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_slide` (
    `id_slide` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `type` varchar(150) NOT NULL,
    `target` varchar(150) NOT NULL,
    `width` varchar(150) NOT NULL,
    `height` varchar(150) NOT NULL,
    `multi_link` int(11) NOT NULL,
    `multi_img` int(11) NOT NULL,
    `multi_video` int(11) NOT NULL,
    `multi_thumb` int(11) NOT NULL,
    `single_link` varchar(150),
    `single_img` varchar(150),
    `single_img_tablet` varchar(150),
    `single_img_mobile` varchar(150),
    `single_img_retina` varchar(150),
    `single_video` varchar(150),
    `single_thumb` varchar(150),
    `single_poster` varchar(150),
    PRIMARY KEY  (`id_slide`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_slide_lang` (
    `id_slide` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` varchar(150) NOT NULL,
    `img_url` varchar(150) NOT NULL,
    `tablet_img_url` varchar(150) NOT NULL,
    `mobile_img_url` varchar(150) NOT NULL,
    `retina_img_url` varchar(150) NOT NULL,
    `video_url` varchar(150) NOT NULL,
    `thumb_url` varchar(150) NOT NULL,
    `poster_url` varchar(150) NOT NULL,
    `url` varchar(150) NOT NULL,
    `description` varchar(150) NOT NULL,
    `thumb_text` varchar(150) NOT NULL,
    PRIMARY KEY  (`id_slide`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_item` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
    `specific_class` varchar(150),
    `id_slide` int(11) NOT NULL,
    `item_status` int(11) NOT NULL,
    `position_type` varchar(150),
    `position_predefined` varchar(150),
    `position_x` varchar(150),
    `position_x_measure` varchar(150),
    `position_y` varchar(150),
    `position_y_measure` varchar(150),
    `show_effect` varchar(150),
    `hide_effect` varchar(150),
    `show_delay` varchar(150),
    `hide_delay` varchar(150),
    `item_order` varchar(150),
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_item_lang` (
    `id_item` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` varchar(150) NOT NULL,
    `content` text NOT NULL,
    PRIMARY KEY  (`id_item`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmslider_slider_to_page` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `id_slider` int(11) NOT NULL,
    `hook` varchar(150) NOT NULL,
    `page` varchar(150) NOT NULL,
    `sort_order` int(11) NOT NULL,
    `active` int(11) NOT NULL,
    `slide_only` int(11) NOT NULL,
    `width` varchar(150),
    `height` varchar(150),
    `responsive` varchar(150),
    `autoplay` varchar(150),
    `autoplay_delay` varchar(150),
    `autoplay_direction` varchar(150),
    `autoplay_on_hover` varchar(150),
    `fade` varchar(150),
    `fade_out_previous_slide` varchar(150),
    `fade_duration` varchar(150),
    `image_scale_mode` varchar(150),
    `center_image` varchar(150),
    `allow_scale_up` varchar(150),
    `auto_height` varchar(150),
    `auto_slide_size` varchar(150),
    `start_slide` varchar(150),
    `shuffle` varchar(150),
    `orientation` varchar(150),
    `force_size` varchar(150),
    `loop` varchar(150),
    `slide_distance` varchar(150),
    `slide_animation_duration` varchar(150),
    `height_animation_duration` varchar(150),
    `visible_size` varchar(150),
    `center_selected_slide` varchar(150),
    `right_to_left` varchar(150),
    `arrows` varchar(150),
    `fade_arrows` varchar(150),
    `buttons` varchar(150),
    `keyboard` varchar(150),
    `keyboard_only_on_focus` varchar(150),
    `touch_swipe` varchar(150),
    `touch_swipe_threshold` varchar(150),
    `fade_caption` varchar(150),
    `caption_fade_duration` varchar(150),
    `full_screen` varchar(150),
    `fade_full_screen` varchar(150),
    `wait_for_layers` varchar(150),
    `auto_scale_layers` varchar(150),
    `reach_video_action` varchar(150),
    `leave_video_action` varchar(150),
    `play_video_action` varchar(150),
    `end_video_action` varchar(150),
    `thumbnail_type` varchar(150),
    `thumbnail_width` varchar(150),
    `thumbnail_height` varchar(150),
    `thumbnails_position` varchar(150),
    `thumbnail_pointer` varchar(150),
    `thumbnail_arrows` varchar(150),
    `fade_thumbnail_arrows` varchar(150),
    `thumbnail_touch_swipe` varchar(150),
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
