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

<div class="panel-heading">{l s='Order states' mod='correos'}</div>
   <div class="panel-body">
   <form class="form clearfix " enctype="multipart/form-data" method="post">
      <div class="form-group">
         <label class="control-label">{l s='Please select Order states which you wish to use to generate shipping label' mod='correos'}</label>
      </div>
      <table class="table">
         <tbody>
      {if !empty($correos_config.order_states)}   
         {assign var="order_states" value=$correos_config.order_states|@json_decode}
      {else}
         {assign var="order_states" value=array()}
      {/if}
	
      {foreach from=$states item=state}
      <tr>
         <td>
            {if $state.module_name eq 'correos'} <input type="hidden" name="order_state[]" value="{$state.id_order_state|escape:'htmlall':'UTF-8'}" /> {/if}
            <input type="checkbox" name="order_state[]" value="{$state.id_order_state|escape:'htmlall':'UTF-8'}" {if $state.module_name eq 'correos'} disabled {/if} id="order_state_{$state.id_order_state|escape:'htmlall':'UTF-8'}" {if $state.id_order_state|in_array:$order_states} checked {/if}/> 
         </td>
         <td>
				<label for="order_state_{$state.id_order_state|escape:'htmlall':'UTF-8'}" class="col-sm-12">{$state.name|escape:'htmlall':'UTF-8'}</label>
         </td>
      </tr>
		{/foreach}
        </tbody>
      </table>
      <br>
       <div class="nopadding clear clearfix">
        
         <button class="btn btn-primary pull-right has-action btn-save-general" name="form-order_state" type="submit">
         <i class="fa fa-save nohover"></i>
         {l s='Save' mod='correos'}
         </button>
      </div>
   </form>
   </div>
