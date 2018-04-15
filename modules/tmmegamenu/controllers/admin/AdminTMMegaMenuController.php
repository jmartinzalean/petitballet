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

class AdminTMMegaMenuController extends ModuleAdminController
{
    public $styles = '';

    public function ajaxProcessTabupdate()
    {
        $tmmegamenu = new Tmmegamenu();
        $id_tab = Tools::getValue('id_tab');
        $megamenu = new MegaMenu($id_tab);
        if (Tools::isEmpty(Tools::getValue('data'))) {
            $data = 'empty'; // send if menu is empty for remove it from databese
        } else {
            $data = Tools::getValue('data');
        }

        if (!$megamenu->addMenuItem($data)) {
            die(Tools::jsonEncode(array('error_status' => $this->l('Update Fail'))));
        }
        $tmmegamenu->clearCache();
        die(Tools::jsonEncode(array('success_status' => $this->l('Update Success !'), 'error' => false)));
    }

    public function ajaxProcessUpdatePosition()
    {
        $tmmegamenu = new Tmmegamenu();
        $items = Tools::getValue('item');
        $total = count($items);
        $id_shop = (int)$this->context->shop->id;
        $success = true;
        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'tmmegamenu',
                array('sort_order' => $i),
                '`id_item` = '.preg_replace('/(item_)([0-9]+)/', '${2}', $items[$i - 1]).'
                AND `id_shop` ='.$id_shop
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Update Fail')));
        }
        $tmmegamenu->clearCache();
        die(Tools::jsonEncode(array('success' => 'Update Success !', 'error' => false)));
    }

    public function ajaxProcessGenerateStyles()
    {
        $gdata = Tools::getValue('data');
        $gcssname = Tools::getValue('cssname');
        $result = true;
        foreach ($gdata as $data) {
            $data = explode('|', $data);
            // check if class has value
            if (!Tools::isEmpty(trim($data[1]))) {
                $this->styles .= '.tmmegamenu_item.'.$data[0].' {';
                $data_values = explode('^,', $data[1]);
                foreach ($data_values as $value) {
                    $val = explode(':', str_replace('^', '', $value));
                    if (isset($val[1]) && !Tools::isEmpty($val[1])) {
                        $this->styles .= str_replace('^', '', $value).';';
                    }
                }
                $this->styles .= "}\n";
            }
        }
        // check is something to write in css
        if (!Tools::isEmpty($this->styles)) {
            $file = fopen(Tmmegamenu::stylePath().$gcssname.'.css', 'w');
            fwrite($file, $this->styles);
            $result &= fclose($file);
            $result &= Tmmegamenu::generateUniqueStyles();
        }
        if ($result) {
            die(Tools::jsonEncode(array('status' => 'success', 'message' => $this->l('Update Success !'))));
        }
        die(Tools::jsonEncode(array('status' => 'error', 'message' => $this->l('Update Fail'))));
    }

    public function ajaxProcessResetStyles()
    {
        $gcssname = Tools::getValue('cssname');
        $result = true;

        if (file_exists(Tmmegamenu::stylePath().$gcssname.'.css')) {
            $result &= @unlink(Tmmegamenu::stylePath().$gcssname.'.css');
            $result &= Tmmegamenu::generateUniqueStyles();
        }

        if ($result) {
            die(Tools::jsonEncode(array('status' => 'success', 'message' => $this->l('Reset success !'))));
        }
        die(Tools::jsonEncode(array('status' => 'error', 'message' => $this->l('Reset Fail'))));
    }
}
