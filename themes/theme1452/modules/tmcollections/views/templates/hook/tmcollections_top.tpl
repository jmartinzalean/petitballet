{*
* 2002-2016 TemplateMonster
*
* TM Collections
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
*}

{strip}
  {addJsDefL name=loggin_collection_required}{l s='You must be logged in to manage your collection.' mod='tmcollections' js=1}{/addJsDefL}
  {addJsDefL name=added_to_collection}{l s='The product was successfully added to your collection.' mod='tmcollections' js=1}{/addJsDefL}
  {addJsDefL name=change_name_collection}{l s='Change name' mod='tmcollections' js=1}{/addJsDefL}
  {addJsDefL name=btn_collection}{l s='My collections' mod='tmcollections' js=1}{/addJsDefL}
  {addJsDefL name=share_btn_text}{l s='Share' mod='tmcollections'}{/addJsDefL}
  {addJsDefL name=back_btn_text}{l s='Back' mod='tmcollections'}{/addJsDefL}
  {addJsDefL name=collection_title_step_1}{l s='Step 1' mod='tmcollections'}{/addJsDefL}
  {addJsDefL name=collection_title_step_1_desc}{l s='(Select a layout to create an image that you post it)' mod='tmcollections'}{/addJsDefL}
  {addJsDefL name=collection_title_step_2_desc}{l s='(To add to the image of the cell)' mod='tmcollections'}{/addJsDefL}
  {addJsDefL name=collection_title_step_2}{l s='Step 2' mod='tmcollections'}{/addJsDefL}
  {addJsDefL name=collection_no_product}{l s='No products in this collection' mod='tmcollections'}{/addJsDefL}
  {addJsDef mycollections_url = $link->getModuleLink('tmcollections', 'collections', array(), true)}
  {addJsDef logo_url = $logo_url}
{/strip}
