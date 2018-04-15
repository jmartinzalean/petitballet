{*
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
*}

{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'block_specific'}
        {addJsDefL name=tmdd_msg}{l s='This product has specific price' mod='tmdaydeal'}{/addJsDefL}
        {addJsDefL name=tmdd_msg_period}{l s='Period:' mod='tmdaydeal'}{/addJsDefL}
        {addJsDefL name=tmdd_msg_sale}{l s='Sale:' mod='tmdaydeal'}{/addJsDefL}
        {addJsDefL name=tmdd_msg_use}{l s='use' mod='tmdaydeal'}{/addJsDefL}
		<div class="daydeal-alert-container">
            {foreach from=$specific_prices_data item=specific_prices name=specific_prices}
                <div class="daydeal-prices alert alert-warning">
                    <p>{l s='This product has specific price' mod='tmdaydeal'}</p>
                    <p>{l s='Period:' mod='tmdaydeal'}&nbsp;{$specific_prices['from']|escape:'htmlall':'UTF-8'} - {$specific_prices['to']|escape:'htmlall':'UTF-8'}</p>
                    <p>{l s='Sale:' mod='tmdaydeal'}&nbsp;{$specific_prices['reduction']|escape:'htmlall':'UTF-8'} {$specific_prices['reduction_type']|escape:'htmlall':'UTF-8'}</p>
						{if !$specific_prices['status']}
                        <label>
                            <input type="checkbox" class="daydeal-checkbox" value="{$specific_prices['id_specific_price']|escape:'htmlall':'UTF-8'}" name="specific_price_old" />
                            {l s='use' mod='tmdaydeal'}
                        </label>
                        {/if}
                </div>
            {/foreach}
		</div>
    {/if}
	{$smarty.block.parent}
{/block}
