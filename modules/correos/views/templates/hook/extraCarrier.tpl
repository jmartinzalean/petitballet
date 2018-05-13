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
 <script type="text/javascript">
CorreosConfig.showCustomsMessage = {$show_customs_message|escape:'htmlall':'UTF-8'};
</script>
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/front/correos_jq_v422.js"></script>
<div id="aduana_content" style="display:none; font-weight:bold; color:red">{l s='The shipment involves customs procedures. Shipping price may increase' mod='correos'}</div>
<div id="timetable" style="display:none;font-weight:bold">
{if $S0236_enabletimeselect eq '1'}
	<div id="timetable_inner">
	{l s='Select delivery time' mod='correos'}: 
	<input type="radio" name="cr_timetable" id="9_12" value="01" onchange="Correos.updateHoursSelect('01');"/><label for="9_12">09:00 - 12:00 </label>
	<input type="radio" name="cr_timetable" id="12_15" value="02" onchange="Correos.updateHoursSelect('02');"/><label for="12_15">12:00 - 15:00</label>
	<input type="radio" name="cr_timetable" id="15_18" value="03" onchange="Correos.updateHoursSelect('03');"/><label for="15_18">15:00 - 18:00</label> 
	<input type="radio" name="cr_timetable" id="18_21" value="04" onchange="Correos.updateHoursSelect('04');"/><label for="18_21">18:00 - 21:00</label>
	</div>
{/if}
</div>
<div id="cr_internacional" style="display:none">
      <p>{l s='Check your mobile phone' mod='correos'}: <input type="text" id="cr_international_mobile" name="cr_international_mobile" value="{$cr_client_mobile|escape:'htmlall':'UTF-8'}" onchange="Correos.updateInternationalMobile();" onkeyup="Correos.tooglePaymentModules();" /> </p>
</div>
<div id="correospaq" style="display:none;">
	<span id="paq_search" >
	<span class="span_txt"><strong>{l s='Introduce your User name' mod='correos'}:</strong></span> <input type="text" id="paquser" name="paquser" /> <span id="paq_loading" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /></span>
	 <a href="#" class="paqsearch" id="paqsearch" onclick="Correos.searchPaqs();return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
		</br>
     <span style="font-size:13px; font-weight:bold; color:red; margin:20px 0 0 0 !important; padding:0; float: left">¡{l s='Not compatible with Cash on Delivery' mod='correos'}!</span> 
		<span class="paq_register_link" style="margin-top:15px; font-weight:bold">
	    <a href="http://www.correospaq.es/ss/Satellite?c=Page&cid=1363189180749&pagename=CorreosPaqSite/Page/A_Layout_PAQ" target="_blank" style="text-decoration:underline"> {l s='What is Homepaq and CityPaq?' mod='correos'}</a>
			<a href="https://online.correospaq.es/pages/registro.xhtml" class="paqurl" target="_blank">{l s='Free register!' mod='correos'}</a>
		</span>
	</span>
  
   
	<span id="paq_result" style="display:none;">
		<a href="#" onclick="Correos.paqSearchShow();return false;" id="paqback">{l s='Back to search again' mod='correos'}</a>
		<span id="paq_result_ok" class="paq_result_ok" style="display:none;">
			{l s='Select your terminal' mod='correos'}:
			<select id="correospaqs" name="correospaqs" onchange="Correos.setSelectedPaq('correospaqs');Correos.updatePaq()" style="width:120px">
			</select>
		
		</span>
      <span id="paq_result_fail" class="paq_register_link" style="display:none;">
			<span id="paq_result_fail_message"></span>
			<a href="https://online.correospaq.es/pages/registro.xhtml" class="paqurl" target="_blank">{l s='Free register!' mod='correos'}</a>
		</span>
		<span class="paq_result_ok" style="display:none;">
      <br clear="left" />
         <span style="font-size:13px; font-weight:bold; color:red; margin:5px 0 0 0 !important; padding:0; float: left">¡{l s='Not compatible with Cash on Delivery' mod='correos'}!</span> 
			
         <p style="font-size:13px; font-weight:bold; padding-top:5px">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
         <p><strong>E-mail:</strong> 
         <input type="text" name="paq_email" id="paq_email" style="width:180px; margin-left:10px; margin-right:25px" onchange="Correos.updatePaq()" onkeyup="Correos.tooglePaymentModules();" value="{if isset($request_data.email) and $request_data.email != ''}{$request_data.email|escape:'htmlall':'UTF-8'}{else}{$cr_client_email|escape:'htmlall':'UTF-8'}{/if}" />   
          <strong>{l s='Mobile number' mod='correos'}:</strong>  
          <input type="text" name="paq_mobile" style="width:100px; margin-left:10px;"  id="paq_mobile" onchange="Correos.updatePaq()" onkeyup="Correos.tooglePaymentModules();" value="{if isset($request_data.mobile) and $request_data.mobile->number != ''}{$request_data.mobile->number|escape:'htmlall':'UTF-8'}{else}{$cr_client_mobile|escape:'htmlall':'UTF-8'}{/if}"/>
         </p>
      
      
		   <a href="#" class="paqsearch"  onclick="$( '#citypaqs_options' ).toggle();$( this ).toggle();return false;" title="{l s='Search other' mod='correos'}">{l s='Search other' mod='correos'}</a>
         
         <div id="citypaqs_options" style="display:none">
      
            
            <div class="radio_citypaq_searchtype">
               <div class="radio_citypaq_searchtype_row">
                  <a href="#" class="paqsearch" id="paqsearch_other" onclick="Correos.cityPaqSearch();return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
               </div>
               <div class="radio_citypaq_searchtype_row">
                  <div id="citypaq_searchtype_state_container">
                     <span id="citypaq_searchtype_state_loading" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
                     <select id="citypaq_state"></select>
                     <input type="text" name="citypaq_cp" id="citypaq_cp">
                  </div>
               </div>
                <div class="radio_citypaq_searchtype_row">
                  <label for="citypaq_searchtype_state" class="radio_citypaq_searchtype">{l s='Search by State' mod='correos'}</label>
                  <label for="citypaq_searchtype_cp" class="radio_citypaq_searchtype">{l s='Search by Postal Code' mod='correos'}</label>
               </div>
               <div class="radio_citypaq_searchtype_row">
                  <input type="radio" name="radio_citypaq_searchtype" class="radio_citypaq_searchtype radio_citypaq" value="state" id="citypaq_searchtype_state" onchange="Correos.getStatesWithCitypaq()">
                  <input type="radio" name="radio_citypaq_searchtype" class="radio_citypaq_searchtype radio_citypaq" value="cp" id="citypaq_searchtype_cp">
               </div>
               
            </div>
            <span id="citypaq_search_loading" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
            <span id="citypaq_search_fail" style="display:none"></span>
            
            <div id="citypaqs_map_options" style="display:none">             
               <div id="citypaqs_map_wrapper">   
               <div id="citypaqs_map"></div>
               </div>
               <div id="citypaqs_info">
               <p><strong>{l s='Terminal' mod='correos'}:</strong></p>
                  <select id="citypaqs" name="citypaqs" onchange="Correos.CityPaq_setGoogleMaps();Correos.setSelectedPaq('citypaqs');Correos.updatePaq()"></select>
                 <br>
                 
                  <p><strong>{l s='Address' mod='correos'}:</strong></p>
                  <p id="citypaqs_address"></p>
                  <p><strong>{l s='Schedule' mod='correos'}: </strong><span id="citypaqs_schedule"></span></p>
                  <!--
                  <a href="#" onclick="setselectedpaq('citypaqs');update_paq();return false;"><span class="arrow_leftbefore"></span><span class="correos_popuplinktxt">{l s='Select this Terminal' mod='correos'}</span><span class="arrow_leftafter"></span></a>
                  -->
                  <p style="font-size:13px; margin-top:10px;"><strong>{l s='Selected Terminal for the order' mod='correos'}:</strong> <span class="selected_paq"></span></p>
                  <br>
                  <span id="addtofavorites" style="display:none">
                   
                   <a href="#" onclick="Correos.addToFavorites();return false;" id="addtofavorites_btn">
                     <span class="arrow_rightbefore"></span><span id="addtofavorites_txt">{l s='Add to favourites' mod='correos'}</span><span class="arrow_rightafter"></span> 
                   </a>
                    <span id="addtofavorites_loading" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
                   
                 
                 </span>
                   
               </div>
            
            </div>
        
         </div>
		
      
      </span>
		
	</span>
	
   <br clear="left">
	<button class="closepopup btn_correos" onclick="$.fancybox.close()">{l s='Accept' mod='correos'}</button>
   <input type="hidden" id="selectedpaq_code" name="selectedpaq_code" value="{if isset($request_data.homepaq_code) and $request_data.homepaq_code != ''}{$request_data.homepaq_code|escape:'htmlall':'UTF-8'}{/if}"/>
</div>
<span style="display:none">
<div id="correos_popuplinkcontent">
<span id="correosOfficeName_"></span> <a href="#correos_content" id="correos_popuplink"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt office">{l s='Select office' mod='correos'}</span><span class="arrow_rightafter"></span></a>
</div>
<div id="correos_popuplinkcontentpaq">
<span class="selected_paq"></span> <a href="#correospaq" id="correos_popuplinkpaq"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt citypaq">Buscar Terminal</span><span class="arrow_rightafter"></span></a>
</div>
</span>
{if $ps_version eq '1.4'}
<tr id="correos_content">
<td colspan="4">
	<div>
{else}
<div id="correos_content">	
{/if}
	
	<div id="message_no_office_error" style="display: none;">{l s='We are sorry, this postcode has no offices nearby. Please try another postcode' mod='correos'}</div> 
	<div style="display: none;" id="correosOffices_content">

		<div class="correos_actions" >
			<div class="correos_button_search">
			{l s='Enter Post Code to find office' mod='correos'}: 
			<input type="text" id="correos_postcode" value="{$cr_client_postcode|escape:'htmlall':'UTF-8'}" />     	
			<input type="button" class="btn_correos" value="Buscar" id="btn_office_search" />
			</div>

		</div>
		<select id="correosOfficesSelect" name="correosOfficesSelect" onchange="Correos.setOfficeInfo();Correos.updateOfficeInfo();"></select>
		<input type="hidden" id="registered_correos_postcode" name="registered_correos_postcode" value="{$cr_client_postcode|escape:'htmlall':'UTF-8'}" />
		<br clear="left">
		<div id="correo_info_nombre">
			<p><strong>{l s='Office' mod='correos'}</strong></p>
			<p id="correosOfficeName"></p>
		</div>
		<div>
			<p><strong>{l s='Address' mod='correos'}</strong></p>
			<p id="correosOfficeAddress"></p>
		</div>
		<div id="correosInfoMap"></div>
		<div id="correos_info_horarios">
			<p><strong>{l s='Opening hours' mod='correos'}</strong></p>
			<p>{l s='Opening hours Monday to Friday' mod='correos'}: <span id="correosOfficeHoursMonFri"></span></p>
			<p>{l s='Opening hours Saturday' mod='correos'}: <span id="correosOfficeHoursSat"></span></p>
			<p>{l s='Opening hours Sunday' mod='correos'}: <span id="correosOfficeHoursSun"></span></p>
		</div>
		<br clear="left" />
		<p id="correos_maplink_info">
			<a id="correos_maplink" style="text-decoration:underline" href="" target="_blank">{l s='Open in new window' mod='correos'}</a>
		</p>
		<br clear="left" />
		<p style="font-size:13px; font-weight:bold">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
		<p class="cr_contact_details correos_email"><strong>E-mail:</strong> <input type="text" name="correos_email" id="correos_email" style="width:180px;" onchange="Correos.updateOfficeInfo();" onkeyup="Correos.tooglePaymentModules();" value="{$cr_client_email|escape:'htmlall':'UTF-8'}" />  </p>
		<button class="closepopup btn_correos" onclick="$.fancybox.close()">{l s='Accept' mod='correos'}</button>
		<p class="cr_contact_details"> <strong>{l s='Mobile number' mod='correos'}:</strong> <input type="text" style="width:100px; " name="correos_mobile" id="correos_mobile" onchange="Correos.updateOfficeInfo();" onkeyup="Correos.tooglePaymentModules();" value="{$cr_client_mobile|escape:'htmlall':'UTF-8'}" /></p>
		
		<p class="cr_contact_details"><strong>{l s='Language SMS' mod='correos'}:</strong> 
			<select style="width:95px" name="correos_mobile_lang" id="correos_mobile_lang" onchange="Correos.updateOfficeInfo();">
				<option value="1">Castellano</option>
				<option value="2">Catalá</option>
				<option value="3">Euskera</option>
				<option value="4">Gallego</option>
			</select>
		</p>
		<br clear="left">
		
	</div>
{if $ps_version eq '1.4'}
	</div>
<style>
{literal}
#correos_info_map {width:350px}
#correosOfficeHoursMonFri, #correosOfficeHoursSat, #correosOfficeHoursSun {display:block}
{/literal}
</style>
	</td>
</tr>

{else}
</div>
{/if}
{if $ps_version eq '1.6'}
<style>
{literal}
#correosOffices { float:left; margin:10px 20px 0 0}
#correos_info_map {
  
    height: 270px;
    margin: 15px 20px 15px 0;
    width:400px;
}

#correosOffices_content p {padding: 0 5px 10px 0}
#correos_email, #correos_mobile{margin-right:20px}
{/literal}

</style>
{/if}
