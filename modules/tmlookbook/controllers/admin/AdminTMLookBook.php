<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Look Book
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
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class AdminTMLookBookController extends ModuleAdminController
{
    public function ajaxProcessGetTemplates()
    {
        $lookbook = new Tmlookbook();
        $templates = $lookbook->renderTemplatesForm();

        die(Tools::jsonEncode(array('templates_form' => $templates)));
    }

    public function ajaxProcessGetProducts()
    {
        $tmlookbook = new Tmlookbook();
        $products = $tmlookbook->getProducts(Tools::getValue('id_category'));
        $type = Tools::getValue('type');
        if (!$selected_products = Tools::jsonDecode(Tools::getValue('selected_products'))) {
            $content = $tmlookbook->renderProductList($products, $type);
            die(Tools::jsonEncode(array('status' => 'true', 'content' => $content)));
        }

        foreach ($products as $key => $product) {
            if (is_numeric(array_search($product['id_product'], $selected_products))) {
                unset($products[$key]);
            }
        }

        if (count($products)) {
            $content = $tmlookbook->renderProductList($products, $type);
        } else {
            $content = $tmlookbook->displayWarning($this->l('No products to select'));
        }


        die(Tools::jsonEncode(array('status' => 'true', 'content' => $content)));
    }

    public function ajaxProcessUpdatePagesPosition()
    {
        $items = Tools::getValue('item');
        $total = count($items);
        $id_shop = (int)$this->context->shop->id;
        $success = true;

        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'tmlookbook',
                array('sort_order' => $i),
                '`id_page` = '.preg_replace('/(item_)([0-9]+)/', '${2}', $items[$i - 1]).'
                AND `id_shop` ='.$id_shop
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Update Fail')));
        }
        die(Tools::jsonEncode(array('success' => 'Update Success !', 'error' => false)));
    }

    public function ajaxProcessUpdateTabsPosition()
    {
        $items = Tools::getValue('item');
        $total = count($items);
        $success = true;

        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'tmlookbook_tabs',
                array('sort_order' => $i),
                '`id_tab` = '.preg_replace('/(item_)([0-9]+)/', '${2}', $items[$i - 1]).'
                AND `id_page` = '.(int)Tools::getValue('id_page')
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Update Fail')));
        }
        die(Tools::jsonEncode(array('success' => 'Update Success !', 'error' => false)));
    }

    public function ajaxProcessGetHotSpotForm()
    {
        $lookbook = new Tmlookbook();

        $form = $lookbook->renderHotSpotForm();

        die(Tools::jsonEncode(array('form' => $form)));
    }

    public function ajaxProcessSaveHotSpot()
    {
        $lookbook = new Tmlookbook();

        $errors = $lookbook->validateHostSpotFields();

        if (count($errors) > 0) {
            die(Tools::jsonEncode(array('status' => false, 'errors' => $lookbook->displayError($errors))));
        }

        $id_spot = $lookbook->saveHotSpot();

        die(Tools::jsonEncode(array('status' => true, 'id_spot' => $id_spot, 'message' => 'Update Success !')));
    }

    public function ajaxProcessRemoveHotSpot()
    {
        $lookbook = new Tmlookbook();

        $lookbook->deleteHotSpot();

        die(Tools::jsonEncode(array('status' => true)));
    }

    public function ajaxProcessDeleteHotSpots()
    {
        TMLookBookHotSpots::deleteByTabId(Tools::getValue('id_tab'));

        die(Tools::jsonEncode(array('status' => true)));
    }

    public function ajaxProcessUpdatePointPosition()
    {
        $id_point = Tools::getValue('id');
        $coordinates = Tools::getValue('coordinates');

        $success = TMLookBookHotSpots::updateCoordinates($id_point, $coordinates);

        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Update Fail')));
        }
        die(Tools::jsonEncode(array('success' => 'Update Success !', 'error' => false)));
    }
}
