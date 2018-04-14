<?php
/**
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_items`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_html`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_html_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_link`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_link_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_banner`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_banner_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_video`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_video_lang`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_map`';

$sql[] = 'DROP TABLE IF EXISTS`'._DB_PREFIX_.'tmmegamenu_map_lang`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
