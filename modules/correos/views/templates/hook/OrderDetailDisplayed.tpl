{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}

<h1 class="page-heading">{l s='Correos package information' mod='correos'}</h1>
<div class="table_block">
	<table class="detail_step_by_step table table-bordered">
{if $has_tracking}
		<thead>
			<tr>
				<th class="first_item">{l s='Date' mod='correos'}</th>
				<th class="last_item">{l s='State' mod='correos'}</th>
			</tr>
		</thead>
		<tbody>
            {foreach from=$tracking item=t} 
                <tr class="alternate_item">
                    <td class="step-by-step-date">{$t->Estado|escape:'htmlall':'UTF-8'}</td>
                    <td>{$t->Fecha|escape:'htmlall':'UTF-8'}</td>
                </tr>   
            {/foreach}
    
		</tbody>
{else}
 <tr>
	<th colspan="2">{$tracking|escape:'htmlall':'UTF-8'}</th>
</tr>
{/if}
	</table>
</div>
{if !empty($rma_label_path)}
<h1 class="page-heading">{l s='Correos RMA information' mod='correos'}</h1>
<div class="info-order box">
		<i class="icon-file-text"></i>
		<a href="{$rma_label_path|escape:'htmlall':'UTF-8'}" target="_blank">{l s='Download RMA label' mod='correos'}</a>
	</p>
</div>
{/if}