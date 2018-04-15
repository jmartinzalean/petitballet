<?php
/**
* 2002-2016 TemplateMonster
*
* TM Products Slider
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
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

class AdminTMProductsSliderController extends ModuleAdminController
{
    public function ajaxProcessUpdatePosition()
    {
        $items = Tools::getValue('item');
        $total = count($items);
        $success = true;
        //var_dump($items);
        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'tmproductsslider_item',
                array('slide_order' => $i),
                '`id_slide` = '.str_replace('_0', '', preg_replace('/(tr__)([0-9]+)/', '${2}', $items[$i - 1]))
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Update Fail')));
        }
        die(Tools::jsonEncode(array('success' => 'Update Success !', 'error' => false)));
    }
}
