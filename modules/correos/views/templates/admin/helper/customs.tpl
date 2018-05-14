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
<div class="panel-heading">{l s='Shipping with Customs' mod='correos'}</div>
   <div class="panel-body">
   <form class="form clearfix " enctype="multipart/form-data" method="post">
      <div class="form-group">
         <label class="control-label">{l s='Please select Shipping Zones which require Customs documents' mod='correos'}</label>
      </div>
      <table class="table">
         <tbody>
      {if !empty($correos_config.customs_zone)}   
         {assign var="customs_zone" value=$correos_config.customs_zone|@json_decode}
      {else}
         {assign var="customs_zone" value=array()}
      {/if}
      {foreach from=$zones item=zone}
      <tr>
         <td>
            <input type="checkbox" name="customs_zone[]" value="{$zone.id_zone|escape:'htmlall':'UTF-8'}" id="zone_{$zone.id_zone|escape:'htmlall':'UTF-8'}" {if in_array($zone.id_zone, $customs_zone)}checked{/if}/> 
         </td>
         <td>
				<label for="zone_{$zone.id_zone|escape:'htmlall':'UTF-8'}" class="col-sm-12">{$zone.name|escape:'htmlall':'UTF-8'}</label>
         </td>
      </tr>
		{/foreach}
        </tbody>
      </table>
      <br>
      <div class="form-group">
         <label class="control-label">{l s='Message to warn customers about Customs charges' mod='correos'}</label>
         <p class="help-block">{l s='The shipment involves customs procedures. Shipping price may increase' mod='correos'}</p>
         <p class="help-block"><a style="text-decoration:underline" target="_blank" href="{$link->getAdminLink('AdminTranslations')|escape:'html':'UTF-8'}&lang=es&type=modules&theme=#correos">{l s='Edit module messages' mod='correos'}</a>
             </p>
      </div>
      <div class="form-group">
        <label class="control-label">{l s='Set default customs description' mod='correos'}</label>
            <div class="">
            <select autocomplete="off" class="form-control" name="customs_description">
                <option value="">{l s='Select' mod='correos'}</option>
                {foreach from=$customs_categories key='id' item='name'}
                    <option value="{$id|escape:'htmlall':'UTF-8'}"{if isset($correos_config.customs_default_category) and $correos_config.customs_default_category eq $id} selected{/if}>{$name|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>
            </div>
     </div>
      <div class="nopadding clear clearfix">
         <hr>
         <button class="btn btn-primary pull-right has-action btn-save-general" name="form-customs" type="submit">
         <i class="fa fa-save nohover"></i>
         {l s='Save' mod='correos'}
         </button>
      </div>
      
   </form>
   </div>
 