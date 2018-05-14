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
<div class="correos-carrer-content">
{if $params.cr_client_postcode == '00000'}
  <div class="center">{l s='Please register a valid address to search a collection point' mod='correos'}</div>
{else}

{if $params.correos_config.presentation_mode == 'popup'} 
<div style="text-align: right;">
<span id="correosOfficeName_{$params.id_carrier|intval}" class="correosOfficeName_"></span> <a data-toggle="modal" href="#modal-correos{$params.id_carrier|intval}" class="correos_popuplink"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt office">{l s='Select office' mod='correos'}</span><span class="arrow_rightafter"></span></a>
</div>


<div class="modal fade modal-correos" id="modal-correos{$params.id_carrier|intval}"  role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='Select office' mod='correos'}</h4>
        </div>
        <div class="modal-body">
{/if}

<div id="loadingmask{$params.id_carrier|intval}">
 <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" />{l s='Loading...' mod='correos'}
 </div>
<div id="message_no_office_error{$params.id_carrier|intval}" style="display: none;">{l s='We are sorry, this postcode has no offices nearby. Please try another postcode' mod='correos'}</div> 
<div style="display: none;" id="correosOffices_content{$params.id_carrier|intval}" class="correosOfficescontent">
	<div class="correos_actions" >
		<div class="correos_button_search">
            {l s='Enter Post Code to find office' mod='correos'}: 
			<input type="text" id="correos_postcode{$params.id_carrier|intval}" class="correos_postcode" value="{$params.cr_client_postcode|escape:'htmlall':'UTF-8'}" />     	
			<input type="button" class="btn_correos" value="Buscar" onclick="Correos.getOffices({$params.id_carrier|intval}); return false; " />
		</div>

	</div>
	<select id="correosOfficesSelect{$params.id_carrier|intval}" name="correosOfficesSelect" onchange="Correos.setOfficeInfo({$params.id_carrier|intval});Correos.updateOfficeInfo({$params.id_carrier|intval});"></select>
	<input type="hidden" id="registered_correos_postcode{$params.id_carrier|intval}" name="registered_correos_postcode" value="{$params.cr_client_postcode|escape:'htmlall':'UTF-8'}" />
	<br clear="left">
	<div id="correo_info_nombre{$params.id_carrier|intval}" class="correo_info_nombre">
    <h6 class="h6">{l s='Office' mod='correos'}</h6>
		<span id="correosOfficeName{$params.id_carrier|intval}"></span>
	</div>
	<div>
    <h6 class="h6">{l s='Address' mod='correos'}</h6>
		<span id="correosOfficeAddress{$params.id_carrier|intval}"></span>
	</div>
	<div id="correosInfoMap{$params.id_carrier|intval}" class="correosInfoMap"></div>
	<div id="correos_info_horarios{$params.id_carrier|intval}">
    <h6 class="h6">{l s='Opening hours' mod='correos'}</h6>
		<span class="control-label d-block">{l s='Opening hours Monday to Friday' mod='correos'}:<br> <span id="correosOfficeHoursMonFri{$params.id_carrier|intval}"></span></span><br>
		<span class="control-label d-block">{l s='Opening hours Saturday' mod='correos'}:<br> <span id="correosOfficeHoursSat{$params.id_carrier|intval}"></span></span><br>
		<span class="control-label d-block">{l s='Opening hours Sunday' mod='correos'}:<br> <span id="correosOfficeHoursSun{$params.id_carrier|intval}"></span></span>
	</div>
	<br clear="left" />
	<span id="correos_maplink_info{$params.id_carrier|intval}" class="correos_maplink_info">
		<a id="correos_maplink{$params.id_carrier|intval}" style="text-decoration:underline" href="" target="_blank">{l s='Open in new window' mod='correos'}</a>
	</span>
	<br clear="left" /><br>
	<span>{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</span><br>
	

    <span class="cr_contact_details correos_email"><strong>E-mail:</strong> </span>
    
    <input type="text" name="correos_email" id="correos_email{$params.id_carrier|intval}" style="width:180px;" onchange="Correos.updateOfficeInfo({$params.id_carrier|intval});" onkeyup="Correos.tooglePaymentModules({$params.id_carrier|intval});" value="{$params.cr_client_email|escape:'htmlall':'UTF-8'}" />   <br>
    
	<button class="closepopup btn_correos" onclick="$.fancybox.close()">{l s='Accept' mod='correos'}</button>
    
	<span class="cr_contact_details mobile_number"> <strong>{l s='Mobile number' mod='correos'}:</strong> </span>
    
    <input type="text" style="width:100px; " name="correos_mobile" id="correos_mobile{$params.id_carrier|intval}" onchange="Correos.updateOfficeInfo({$params.id_carrier|intval});" onkeyup="Correos.tooglePaymentModules({$params.id_carrier|intval});" value="{$params.cr_client_mobile|escape:'htmlall':'UTF-8'}" />
		
	<span class="cr_contact_details"><strong>{l s='Language SMS' mod='correos'}:</strong> 
		<select style="width:95px" name="correos_mobile_lang" id="correos_mobile_lang{$params.id_carrier|intval}" onchange="Correos.updateOfficeInfo({$params.id_carrier|intval});">
			<option value="1">Castellano</option>
			<option value="2">Catalá</option>
			<option value="3">Euskera</option>
			<option value="4">Gallego</option>
		</select>
	</span>
	<br clear="left">
		
</div>

{if $params.correos_config.presentation_mode == 'popup'}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='correos'}</button>
        </div>
      </div>
   </div>
</div>
{/if}

{/if} {* end if $params.cr_client_postcode == '00000' *}
</div>
{if $params.delivery_option == $params.id_carrier}
<script>
document.addEventListener( 'DOMContentLoaded', function () {
  Correos.getOffices({$params.id_carrier|intval});
});
</script>
{/if}


