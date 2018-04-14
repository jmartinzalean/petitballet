<?php
/**
* 2002-2016 TemplateMonster
*
* TM Product Videos
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

class AdminTMProductVideosController extends ModuleAdminController
{
    public function ajaxProcessUpdatePosition()
    {
        $items = Tools::getValue('item');
        $total = count($items);
        $id_lang = Tools::getValue('id_lang');
        $success = true;
        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'product_video_lang',
                array('sort_order' => (int)$i),
                '`id_video` = '.(int)preg_replace('/(video_)([0-9]+)/', '${2}', $items[$i - 1]).'
                AND `id_lang` ='.(int)$id_lang
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Position Update Fail')));
        }
        die(Tools::jsonEncode(array('success' => 'Position Updated Success !', 'error' => false)));
    }

    public function ajaxProcessUpdateStatus()
    {
        $id_video = Tools::getValue('id_video');
        $id_lang = Tools::getValue('id_lang');
        $video_status = Tools::getValue('video_status');
        $success = true;
        if ($video_status == 1) {
            $video_status = 0;
        } else {
            $video_status = 1;
        }

        $success &= Db::getInstance()->update(
            'product_video_lang',
            array('status'=> (int)$video_status),
            ' id_video = '.(int)$id_video.'
            AND id_lang = '.(int)$id_lang
        );

        if (!$success) {
            die(Tools::jsonEncode(array('error_status' => 'Status Update Fail')));
        }
        die(Tools::jsonEncode(array('success_status' => 'Status Update Success!', 'error' => false)));
    }

    public function ajaxProcessUpdateItem()
    {
        $id_video = Tools::getValue('id_video');
        $id_lang = Tools::getValue('id_lang');
        $video_name = Tools::getValue('video_name');
        $video_description = Tools::getValue('video_description');
        $success = true;

        $success &= Db::getInstance()->update(
            'product_video_lang',
            array('name'=> pSql($video_name), 'description'=> pSql($video_description)),
            ' id_video = '.(int)$id_video.'
            AND id_lang = '.(int)$id_lang
        );

        if (!$success) {
            die(Tools::jsonEncode(array('error_status' => 'Information Update Fail')));
        }
        die(Tools::jsonEncode(array('success_status' => 'Information Update Success!', 'error' => false)));
    }

    public function ajaxProcessRemoveItem()
    {
        $id_video = Tools::getValue('id_video');
        $id_lang = Tools::getValue('id_lang');
        $success = true;

        $success &= Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_video_lang
                                              WHERE id_video = '.(int)$id_video.' AND id_lang = '.(int)$id_lang);

        $any_video = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'product_video_lang
                                                WHERE id_video = '.(int)$id_video);

        if (!$any_video) {
            $success &= Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_video
                                                WHERE id_video = '.(int)$id_video);
        }

        if (!$success) {
            die(Tools::jsonEncode(array('error_status' => 'Removing Fail')));
        }
        die(Tools::jsonEncode(array('success_status' => 'Video removed success!', 'error' => false)));
    }
}
