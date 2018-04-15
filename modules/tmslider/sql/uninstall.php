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

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_slider`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_slider_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_sliders_slides`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_slide`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_slide_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_item`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_item_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmslider_slider_to_page`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}