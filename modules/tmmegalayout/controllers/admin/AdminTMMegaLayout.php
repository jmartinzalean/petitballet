<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Mega Layout
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
 *  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class AdminTMMegaLayoutController extends ModuleAdminController
{

    public function ajaxProcessUpdateLayoutItem()
    {
        $errors = array();
        $item_data = Tools::getValue('data');
        $id_item = Tools::getValue('id_item');

        if ($id_item != 'false') {
            $item = new TMMegaLayoutItems($id_item);
        } else {
            $item = new TMMegaLayoutItems();
            $item->id_unique = 'it_' . Tools::passwdGen(12, 'NO_NUMERIC');
        }

        $item->id_layout = $item_data['id_layout'];
        $item->id_parent = $item_data['id_parent'];
        $item->sort_order = $item_data['sort_order'];
        $item->col_xs = $item_data['col_xs'];
        $item->col_sm = $item_data['col_sm'];
        $item->col_md = $item_data['col_md'];
        $item->col_lg = $item_data['col_lg'];
        $item->module_name = $item_data['module_name'];
        $item->specific_class = $item_data['specific_class'];

        if ($item->module_name == 'logo' || $item->module_name == 'copyright' || $item->module_name == 'tabs') {
            $item->type = 'block';
        } else {
            if (!empty($item_data['origin_hook'])) {
                $item->origin_hook = $item_data['origin_hook'];
            }
            $item->type = $item_data['type'];
        }

        if ($id_item == 'false') {
            if (!$item->add()) {
                $errors[] = $this->l('Error occurred while adding an item!');
            }
        } else {
            if (!$item->update()) {
                $errors[] = $this->l('Error occurred while saving an item!');
            }
        }

        if (count($errors)) {
            die(Tools::jsonEncode(array('status' => 'false', 'response_msg' => $this->l('Oops...something went wrong!'))));
        }

        $item->id_item = $item->id;
        $tmmegalayout = new Tmmegalayout();
        $item_content = null;

        switch ($item_data['type']) {
            case 'module':
                $this->context->smarty->assign(array(
                    'elem' => get_object_vars($item),
                    'preview' => false,
                    'position' => ''
                ));
                $item_content = $tmmegalayout->display($tmmegalayout->getLocalPath(), '/views/templates/admin/layouts/module.tpl');
                break;
            case 'wrapper':
                $this->context->smarty->assign(array(
                    'elem' => get_object_vars($item),
                    'preview' => false,
                    'position' => ''
                ));
                $item_content = $tmmegalayout->display($tmmegalayout->getLocalPath(), '/views/templates/admin/layouts/wrapper.tpl');
                break;
            case 'row':
                $this->context->smarty->assign(array(
                    'elem' => get_object_vars($item),
                    'preview' => false,
                    'position' => ''
                ));
                $item_content = $tmmegalayout->display($tmmegalayout->getLocalPath(), '/views/templates/admin/layouts/row.tpl');
                break;
            case 'col':
                $class = $item_data['col_xs'] . ' ' . $item_data['col_sm'] . ' ' . $item_data['col_md'] . ' ' . $item_data['col_lg'] . ' ';
                $this->context->smarty->assign(array(
                    'elem' => get_object_vars($item),
                    'preview' => false,
                    'position' => '',
                    'class' => $class
                ));
                $item_content = $tmmegalayout->display($tmmegalayout->getLocalPath(), '/views/templates/admin/layouts/col.tpl');
                break;
            case 'block':
                $this->context->smarty->assign(array(
                    'elem' => get_object_vars($item),
                    'preview' => false,
                    'position' => '',
                ));
                $item_content = $tmmegalayout->display($tmmegalayout->getLocalPath(), '/views/templates/admin/layouts/module.tpl');
                break;
        }

        die(Tools::jsonEncode(array('status' => 'true', 'id_item' => $item->id, 'id_unique' => $item->id_unique, 'response_msg' => $this->l('Changes were saved successfully'), 'content' => $item_content)));
    }

    public function ajaxProcessDeleteLayoutItem()
    {
        $id_items = Tools::getValue('id_item');

        if (count($id_items) < 1) {
            die(Tools::jsonEncode(array('status' => 'error', 'response_msg' => $this->l('Bad ID value'))));
        }

        foreach ($id_items as $id_item) {
            $item = new TMMegaLayoutItems($id_item);

            if (!$item->delete()) {
                die(Tools::jsonEncode(array('status' => 'error', 'response_msg' => $this->l('Can\'t delete item(s)'))));
            }
        }

        die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Item(s) was/were deleted successfully'))));
    }

    public function ajaxProcessUpdateLayoutItemsOrder()
    {
        $data = Tools::getValue('data');

        if (count($data) > 1) {
            foreach ($data as $id_item => $sort_order) {
                $item = new TMMegaLayoutItems($id_item);

                if (!Validate::isLoadedObject($item)) {
                    die(Tools::jsonEncode(array('status' => 'error', 'response_msg' => $this->l('Bad ID value'))));
                }

                $item->sort_order = $sort_order;

                if (!$item->update()) {
                    die(Tools::jsonEncode(array('status' => 'error', 'response_msg' => $this->l('Sort order changes were not saved successfully'))));
                }
            }

            die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Changes were saved successfully'))));
        }
    }

    public function ajaxProcessLayoutPreview()
    {
        $id_layout = Tools::getValue('id_layout');

        $item = new Tmmegalayout();

        die(Tools::jsonEncode(array('status' => 'true', 'msg' => $item->getLayoutAdmin($id_layout, true))));
    }

    public function ajaxProcessLayoutExport()
    {
        $id_layout = Tools::getValue('id_layout');
        $obj = new TMMegalayoutExport();
        $href = $obj->init($id_layout);

        die(Tools::jsonEncode(array('status' => true, 'href' => $href)));
    }

    public function ajaxProcessLoadTool()
    {
        $tool_name = Tools::getValue('tool_name');
        $tools = new Tmmegalayout();
        $tool_content = $tools->renderToolContent($tool_name);

        die(Tools::jsonEncode(array('status' => 'true', 'content' => $tool_content)));
    }

    public function ajaxProcessGetItemStyles()
    {
        $id_unique = Tools::getValue('id_unique');
        $tools = new Tmmegalayout();
        $styles = $tools->getItemStyles($id_unique);

        die(Tools::jsonEncode(array('status' => 'true', 'content' => $styles)));
    }

    public function ajaxProcessSaveItemStyles()
    {
        $id_unique = Tools::getValue('id_unique');
        $data = Tools::getValue('data');

        if (!$data || Tools::isEmpty($data)) {
            die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('Nothing to save'))));
        }

        $tools = new Tmmegalayout();

        if ($tools->saveItemStyles($id_unique, $data)) {
            die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('Saved'))));
        }

        die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred'))));
    }

    public function ajaxProcessClearItemStyles()
    {
        $id_unique = Tools::getValue('id_unique');
        $tools = new Tmmegalayout();

        if ($tools->deleteItemStyles($id_unique)) {
            die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('Item styles are removed'))));
        }

        die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred while removing styles'))));
    }

    public function ajaxProcessAddLayoutForm()
    {
        $hook_name = Tools::getValue('hook_name');
        $layout = new Tmmegalayout();

        if (!$result = $layout->showMessage($hook_name, 'addLayout')) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $result)));
    }

    public function ajaxProcessAddLayout()
    {
        $hook_name = Tools::getValue('hook_name');
        $layout_name = Tools::getValue('layout_name');
        $layout = new Tmmegalayout();

        if ((bool)TMMegaLayoutLayouts::getLayoutByName($layout_name)) {
            die(Tools::jsonEncode(array('status' => 'false', 'type' => 'popup', 'message' => $this->l('You have preset with this name'))));
        }

        if (!$id_layout = $layout->addLayout($hook_name, $layout_name)) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred'))));
        }

        $new_layouts = Tools::jsonEncode(TMMegaLayoutLayouts::getLayoutsForHook($hook_name, $this->context->shop->id));

        die(Tools::jsonEncode(array('status' => 'true', 'id_layout' => $id_layout, 'message' => $this->l('The layout is successfully added.'), 'new_layouts' => $new_layouts)));
    }

    public function ajaxProcessAddModuleConfirmation()
    {
        $hook_name = Tools::getValue('hook_name');
        $id_layout = (int) Tools::getValue('id_layout');

        $tmmegalayout = new Tmmegalayout();

        if (!$form = $tmmegalayout->addModuleForm($hook_name, $id_layout)) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some error occurred'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $form)));
    }

    public function ajaxProcessLoadLayoutContent()
    {
        $id_layout = Tools::getValue('id_layout');
        $layout = new Tmmegalayout();

        if (!$result = $layout->renderLayoutContent($id_layout)) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'layout' => $result[0], 'layout_buttons' => $result[1])));
    }

    public function ajaxProcessGetLayoutRemoveConfirmation()
    {
        $id_layout = Tools::getValue('id_layout');
        $layout = new Tmmegalayout();

        if (!$result = $layout->showMessage($id_layout, 'layoutRemoveConf')) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $result)));
    }

    public function ajaxProcessRemoveLayout()
    {
        $id_layout = (int) Tools::getValue('id_layout');
        $layouts = TMMegaLayoutItems::getItems($id_layout);
        $tmmegalayout = new Tmmegalayout();
        $tmmegalayout->deleteFilesOfLayout($id_layout);

        if ($layouts && count($layouts) > 0) {
            foreach ($layouts as $layout) {
                $item = new TMMegaLayoutItems($layout['id_item']);

                if (!$item->delete()) {
                    die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some error occurred. Can\'t delete layout item') . $item->id)));
                }
            }
        }

        $tab = new TMMegaLayoutLayouts($id_layout);

        if (!$tab->delete() || !$tab->dropLayoutFromPages()) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => 'Can\'t delete layout')));
        }

        $hook_name = Tools::getValue('hook_name');
        $new_layouts = Tools::jsonEncode(TMMegaLayoutLayouts::getLayoutsForHook($hook_name, $this->context->shop->id));

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('Layout is successfully removed'), 'new_layouts' => $new_layouts)));
    }

    public function ajaxProcessGetLayoutRenameConfirmation()
    {
        $id_layout = Tools::getValue('id_layout');
        $layout = new Tmmegalayout();
        $tab = new TMMegaLayoutLayouts($id_layout);

        if (!$result = $layout->showMessage($id_layout, 'layoutRenameConf', $tab->layout_name)) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some errors occurred'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $result)));
    }

    public function ajaxProcessRenameLayout()
    {
        $id_layout = (int) Tools::getValue('id_layout');
        $layout_name = Tools::getValue('layout_name');
        $tmmegalayout = new Tmmegalayout();

        if ((bool)TMMegaLayoutLayouts::getLayoutByName($layout_name)) {
            die(Tools::jsonEncode(array('status' => 'false', 'type' => 'popup', 'message' => $this->l('You have preset with this name'))));
        } else {
            if (!$tmmegalayout->renameFilesOfLayout($id_layout, $layout_name)) {
                die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t rename a layouts files'))));
            }
        }

        $tab = new TMMegaLayoutLayouts($id_layout);
        $tab->layout_name = $layout_name;

        if (!$tab->update()) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t update a layout name'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('Layout name is successfully changed'))));
    }

    public function ajaxProcessDisableLayout()
    {
        $id_layout = (int) Tools::getValue('id_layout');
        $tmmegalayout = new Tmmegalayout();
        $tab = new TMMegaLayoutLayouts($id_layout);
        $tab->status = 0;

        if (!$tab->update() || !$tab->disableLayoutForAllPages()) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t disable layout'))));
        }

        if (!$tmmegalayout->combineAllItemsStyles()) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t regenerate layout styles'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('Layout is disabled'))));
    }

    public function ajaxProcessEnableLayout()
    {
        $id_layout = (int) Tools::getValue('id_layout');
        $hook_name = Tools::getValue('hook_name');
        $pages = Tools::getValue('pages'); // pages list if assigned not for all
        $status = Tools::getValue('layout_status'); // current status of layout
        $type = ''; // set type for different responses after updating

        $tmmegalayout = new Tmmegalayout();
        // do if assigned for all pages
        if (!$pages) {
            $tabs = TMMegaLayoutLayouts::getLayoutsForHook($hook_name, $this->context->shop->id);

            if ($tabs) {
                foreach ($tabs as $layout) {
                    $tab = new TMMegaLayoutLayouts($layout['id_layout']);
                    $tab->status = 0;

                    if (!$tab->update()) {
                        die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t disable previous layout'))));
                    }
                }
            }

            $item = new TMMegaLayoutLayouts($id_layout);
            $item->status = 1;

            if (!$item->update() || !$item->dropLayoutFromPages()) {
                die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t enable this layout'))));
            }

            $type = 'all'; // set type for response
            $response_pages = $this->l('All pages');
        } else {
            $item = new TMMegaLayoutLayouts($id_layout);

            // do if layout was just cleared but not enabled/disabled
            if ($pages == 'empty') {
                if ($item->dropLayoutFromPages()) {
                    die(Tools::jsonEncode(array('status' => 'true', 'clear', 'message' => $this->l('Layout is saved'))));
                }
            }

            $item->status = 0;

            if (!$item->update()) {
                die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t enable this layout'))));
            }

            $item->setLayoutToPage($pages, $hook_name, $status);
            $response_pages = implode(', ', $pages);

            if ($status) {
                $type = 'sub';// set type for response
            }
        }

        if (!$tmmegalayout->combineAllItemsStyles()) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t regenerate layout styles'))));
        }

        // do if different pages assigned
        if ($pages) {
            $relations = $this->l('assigned');
            if ($status) {
                $relations = $this->l('enabled');
            }
            die(Tools::jsonEncode(
                array(
                    'status' => 'true',
                    'type' => $type,
                    'message' => sprintf(
                        $this->l('Layout(s) is(are) %s for %s'),
                        $relations,
                        $response_pages
                    )
                )
            ));
        } else {
            die(Tools::jsonEncode(
                array(
                    'status' => 'true',
                    'type' => $type,
                    'message' => $this->l('Layout is enabled for All Pages')
                )
            ));
        }
    }

    public function ajaxProcessGetImportInfo()
    {
        $import = new TMMegaLayoutImport();
        $import_path = new Tmmegalayout();

        Tmmegalayout::cleanFolder($import_path->getLocalPath() . 'import/');
        $file_name = basename($_FILES['file']['name']);
        $upload_file = $import_path->getLocalPath() . 'import/' . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
        $preview = $import->layoutPreview($import_path->getLocalPath() . 'import/', $file_name);

        die(Tools::jsonEncode(array('status' => 'true', 'preview' => $preview)));
    }

    public function ajaxProcessImportLayout()
    {
        $import = new TMMegaLayoutImport();
        $import_path = new Tmmegalayout();
        Tmmegalayout::cleanFolder($import_path->getLocalPath() . 'import/');
        $file_name = basename($_FILES['file']['name']);
        $upload_file = $import_path->getLocalPath() . 'import/' . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
        $name_layout = Tools::getValue('name_layout');

        if ((bool)TMMegaLayoutLayouts::getLayoutByName($name_layout)) {
            die(Tools::jsonEncode(array('status' => 'false', 'type' => 'popup', 'message' => $this->l('You have preset with this name'))));
        }

        $import->importLayout($import_path->getLocalPath() . 'import/', $file_name, false, $name_layout);

        die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Successful import'))));
    }

    public function ajaxProcessLoadLayoutTab()
    {
        $layout_list = new Tmmegalayout();
        $hook_name = Tools::getValue('tab_name');
        $old_layouts = Tools::jsonDecode(Tools::getValue('old_layouts'), true);
        $new_layouts = Tools::jsonEncode($layout_list->checkNewLayouts($hook_name, $old_layouts));

        die(Tools::jsonEncode(array('status' => 'true', 'new_layouts' => $new_layouts, 'old_layouts' => Tools::jsonEncode(TMMegaLayoutLayouts::getLayoutsForHook($hook_name, $this->context->shop->id)))));
    }

    public function ajaxProcessAfterImport()
    {
        $import_path = new Tmmegalayout();
        $path = $import_path->getLocalPath() . 'import/';
        Tmmegalayout::cleanFolder($path);

        die(Tools::jsonEncode(array('status' => 'true')));
    }

    public function ajaxProcessAfterExport()
    {
        die(Tools::jsonEncode(array('status' => 'true')));
    }

    public function ajaxProcessResetToDefault()
    {
        // get all tabs for this store
        $layouts = TMMegaLayoutLayouts::getShopLayoutsIds();

        if ($layouts) {
            foreach ($layouts as $layout) {
                // if no layouts for this tab delete it immediately
                if (!$items = TMMegaLayoutItems::getItems($layout['id_layout'])) {
                    $current_layout = new TMMegaLayoutLayouts($layout['id_layout']);

                    if (!$current_layout->dropLayoutFromPages() || !$current_layout->delete()) {
                        die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some error occurred. Can\'t delete layout') . $current_layout->id)));
                    }
                    // if there is layouts for this tab delete all and delete tab after
                } else {
                    foreach ($items as $item) {
                        $current_item = new TMMegaLayoutItems($item['id_item']);
                        if (!$current_item->delete()) {
                            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some error occurred. Can\'t delete layout item') . $current_item->id)));
                        }
                    }

                    $current_layout = new TMMegaLayoutLayouts($layout['id_layout']);

                    if (!$current_layout->dropLayoutFromPages() || !$current_layout->delete()) {
                        die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Some error occurred. Can\'t delete layout item') . $current_layout->id)));
                    }
                }
            }
        }

        $tmmegalayout = new Tmmegalayout();
        // install default layouts from "default" folder
        if (!$tmmegalayout->installDefLayouts() || !$tmmegalayout->setDefaultPageInfoLayout()) {
            die(Tools::jsonEncode(array('status' => 'false', 'message' => $this->l('Can\'t load default layouts'))));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'message' => $this->l('All data is successfully removed'))));
    }

    public function ajaxProcessReloadTab()
    {
        $tab_name = Tools::getValue('tab_name');
        $tab = new Tmmegalayout();
        $layouts_list = Tools::jsonEncode(TMMegaLayoutLayouts::getLayoutsForHook($tab_name, $this->context->shop->id));

        if (!$tab_content = $tab->renderLayoutTab($tab_name)) {
            die(Tools::jsonEncode(array('status' => 'false')));
        }

        die(Tools::jsonEncode(array('status' => 'true', 'tab_content' => $tab_content, 'layouts_list' => $layouts_list)));
    }

    public function ajaxProcessUpdateOptionOptimize()
    {
        $optimize = new TMMegaLayoutOptimize();
        $optimize->optimize();
        Configuration::updateValue('TMMEGALAYOUT_OPTIMIZE', true);

        die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Optimization is complete'))));
    }

    public function ajaxProcessUpdateOptionDeoptimize()
    {
        $optimize = new TMMegaLayoutOptimize();
        $optimize->deoptimize();
        Configuration::updateValue('TMMEGALAYOUT_OPTIMIZE', false);

        die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Optimization is disabled'))));
    }

    public function ajaxProcessOptimizeMessage()
    {
        $optimize = new TMMegaLayoutOptimize();
        $optimize->deoptimize();
        Configuration::updateValue('TMMEGALAYOUT_OPTIMIZE', false);

        die(Tools::jsonEncode(array('status' => 'true')));
    }

    public function ajaxProcessShowMessages()
    {
        $value = Configuration::get('TMMEGALAYOUT_SHOW_MESSAGES');

        if ((bool)$value) {
            $optimize = new TMMegaLayoutOptimize();
            $optimize->deoptimize();
            Configuration::updateValue('TMMEGALAYOUT_OPTIMIZE', false);
            Configuration::updateValue('TMMEGALAYOUT_SHOW_MESSAGES', false);
            die(Tools::jsonEncode(array('status' => 'false', 'response_msg' => $this->l('Optimization is disabled'))));
        } else {
            $optimize = new TMMegaLayoutOptimize();
            $optimize->optimize();
            Configuration::updateValue('TMMEGALAYOUT_OPTIMIZE', true);
            Configuration::updateValue('TMMEGALAYOUT_SHOW_MESSAGES', true);
            die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Optimization is complete'))));
        }
    }

    public function ajaxProcessUpdateProductInfoPage()
    {
        $theme_name = Tools::getValue('theme_name');
        if (Configuration::updateValue('tmmegalayout_poduct_layout', $theme_name)) {
            die(Tools::jsonEncode(array('status' => 'true', 'response_msg' => $this->l('Template successfully changed'))));
        }
        die(Tools::jsonEncode(array('status' => 'false', 'response_msg' => $this->l('Error'))));
    }
}
