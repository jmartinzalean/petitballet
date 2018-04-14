<?php
/**
* 2002-2015 TemplateMonster
*
* TM Homepage Category Gallery
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2015 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'tmhomepagecategorygallery`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'tmhomepagecategorygallery_shop`';

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'tmhomepagecategorygallery_lang`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
