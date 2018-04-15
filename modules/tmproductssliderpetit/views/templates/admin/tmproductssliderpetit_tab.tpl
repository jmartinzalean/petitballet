{*
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div class="panel product-tab">
	<h3 class="tab">{l s='Display this product in slider?' mod='tmproductssliderpetit'}</h3>
	{include file="controllers/products/multishop/check_fields.tpl" product_tab="ModuleTmproductssliderpetit"}
	<div class="form-group">
    	<div class="col-lg-1">
            <span class="pull-right">
                {if isset($display_multishop_checkboxes) && $display_multishop_checkboxes}
                    {include file="controllers/products/multishop/checkbox.tpl" only_checkbox="true" field="is_slide" type="default"}
                {/if}
            </span>
        </div>
        <div class="col-lg-10">
            <div class="checkbox">
                <label for="is_slide">
                    <input type="checkbox" name="is_slide" id="is_slide" value="1" {if $is_slide}checked="checked"{/if} >
                        {l s='Display in TM Products Slider Petit' mod='tmproductssliderpetit'}</label>
            </div>
        </div>
    </div>
    <div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='tmproductssliderpetit'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" {if $higher_ver}disabled="disabled"{/if}>{if $higher_ver}<i class="process-icon-loading"></i>{else}<i class="process-icon-save"></i>{/if} {l s='Save' mod='tmproductssliderpetit'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" {if $higher_ver}disabled="disabled"{/if}>{if $higher_ver}<i class="process-icon-loading"></i>{else}<i class="process-icon-save"></i>{/if} {l s='Save and stay' mod='tmproductssliderpetit'}</button>
	</div>
</div>