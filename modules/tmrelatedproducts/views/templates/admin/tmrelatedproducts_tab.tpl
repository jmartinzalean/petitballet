{*
* 2002-2016 TemplateMonster
*
* TM Related Products
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

{if isset($error) && $error}
	{$error|escape:'html':'UTF-8'}
{/if}

<div class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="ModuleTmrelatedproducts" />
	<h3 class="tab">{l s='Related Products' mod='tmrelatedproducts'}</h3>
	{if isset($multishop_edit) && $multishop_edit}
    	<div class="alert alert-danger">
        	{l s='You cannot manage related products from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit.' mod='tmrelatedproducts'}
        </div>
    {else}
    	<div class="form-group">
            <label class="control-label col-lg-3" for="product_autocomplete_input_new">
                <span class="label-tooltip" data-toggle="tooltip"
                title="{l s='You can indicate existing products as related for this product.' mod='tmrelatedproducts'}{l s='Start by typing the first letters of the product\'s name, then select the product from the drop-down list.' mod='tmrelatedproducts'}{l s='Do not forget to save the product afterwards!' mod='tmrelatedproducts'}">
                {l s='Related products' mod='tmrelatedproducts'}
                </span>
            </label>
            <div class="col-lg-5">
                <input type="hidden" name="inputRelated" id="inputRelated" value="{foreach from=$related item=rel}{$rel.id_product|escape:'html':'UTF-8'}-{/foreach}" />
                <input type="hidden" name="nameRelated" id="nameRelated" value="{foreach from=$related item=rel}{$rel.name|escape:'html':'UTF-8'}{if !empty($rel.reference)}({$rel.reference|escape:'html':'UTF-8'}){/if}¤{/foreach}" />
                <div id="ajax_choose_product">
                    <div class="input-group">
                        <input type="text" id="product_autocomplete_input_new" name="product_autocomplete_input_new" />
                        <span class="input-group-addon"><i class="icon-search"></i></span>
                    </div>
                </div>
    
                <div id="divRelated">
                    {foreach from=$related item=rel}
                        <div class="form-control-static">
                            <button type="button" class="btn btn-default delRelated" name="{$rel.id_product|escape:'html':'UTF-8'}">
                                <i class="icon-remove text-danger"></i>
                            </button>
                            {$rel.name|escape:'html':'UTF-8'}{if !empty($rel.reference)}({$rel.reference|escape:'html':'UTF-8'}){/if}
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='tmrelatedproducts'}</a>
            <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='tmrelatedproducts'}</button>
            <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='tmrelatedproducts'}</button>
        </div>
    {/if}
</div>