<?php
/**
* 2002-2017 TemplateMonster
*
* TemplateMonster Deal of Day
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
*  @author    TemplateMonster (Sergiy Sakun)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

class AdminTMDayDealProductsController extends ModuleAdminController
{

    public $specificprice = '';

    /**
     * Get information about products when the product has specific price
	 * @return array $data
    */
    public function ajaxProcessGetProductsSpecificPrice()
    {
        $data = array();
        $product_id = Tools::getValue('productId');
        $specificprice_list = DayDeal::getProductsBySpecificPrice($product_id);

        if (!count($specificprice_list)) {
            die(Tools::jsonEncode(array('status' => false)));
        }
        foreach ($specificprice_list as $key => $specificprice) {
            $data[$key]['id_specific_price'] = $specificprice['id_specific_price'];
            $data[$key]['from'] = $specificprice['from'];
            $data[$key]['to'] = $specificprice['to'];
            $data[$key]['reduction_type'] = $specificprice['reduction_type'];
            if ($data[$key]['reduction_type'] != 'amount') {
                $data[$key]['reduction_type'] = '%';
            } else {
                $data[$key]['reduction_type'] = '(amount)';
            }
            if ($specificprice['reduction_type'] != 'amount') {
                $data[$key]['reduction'] = round($specificprice['reduction'] * 100);
            } else {
                $data[$key]['reduction'] = round($specificprice['reduction']);
            }
                $data[$key]['status'] = DayDeal::checkEntity($data[$key]['id_specific_price']);
        }
        die(Tools::jsonEncode(array('status' => true, 'data' => $data)));
    }
}
