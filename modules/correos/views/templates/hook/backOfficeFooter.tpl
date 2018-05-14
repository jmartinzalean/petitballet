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
 var CorreosMessage = {
   mobileError: "{l s='Check your mobile phone. Is required to inform you when your package is ready to be picked up' mod='correos'}",
   mobileErrorInternational: "{l s='Check your mobile phone. Is required to inform you about your package. Egample: +34777777777' mod='correos'}",
   emailError: "{l s='Check your E-mail. Is required to inform you when your package is ready to be picked up' mod='correos'}",
   officeResultError: "{l s='We are sorry, this postcode has no offices nearby. Please try another postcode' mod='correos'}",
   badPostcode: "{l s='If you wish to send your order outside the state you have registered you should add another address' mod='correos'}",
   noPaqsSelected: "{l s='We are sorry, you must select a Homepaqs/Citypaq terminal in order to continue' mod='correos'}",
   mustSelectOffice: "{l s='Please select Office' mod='correos'}",
   waitForServer: "{l s='Please wait for the server to respond' mod='correos'}",
   emptyUsername: "{l s='Please itroduce your username' mod='correos'}",
   noCityPaqTypeSelected: "{l s='Please select how you want to search CitiPaqs' mod='correos'}",
   loading: "{l s='Loading...' mod='correos'}",
   userInvalid: "{l s='User no valid' mod='correos'}",
   noPaqsFound: "{l s='We are sorry, no results was found' mod='correos'}",
   invalidPostCode: "{l s='Invalid Post Code' mod='correos'}",
   noCityPaqsFound: "{l s='We are sorry, no results was found. Please search your terminal by state or Postal Code' mod='correos'}",
   schedule_1: "{l s='In opening hours' mod='correos'}",
   schedule_0: "{l s='24 hours' mod='correos'}",
}
var CorreosConfig = {
	moduleDir: "{$cr_module_dir|escape:'htmlall':'UTF-8'}",
   selectedCarrier: 0,
   CityPaqs: '',
   Paqs: '',
   HomePaqs: '',
   homePaq: false,
   Offices: [],
   id_cart: 0,
   token: '{$correos_token|escape:'htmlall':'UTF-8'}',
   url_call: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}"
} 
var message_mobile_error = "{l s='Check your mobile phone. Is required to inform you when your package is ready to be picked up' mod='correos'}";
var message_email_error = "{l s='Check your E-mail. Is required to inform you when your package is ready to be picked up' mod='correos'}";
var message_resultados_error = "{l s='We are sorry, this postcode has no offices nearby. Please try another postcode' mod='correos'}"; 
var message_no_paqsselected = "{l s='We are sorry, you must select a Homepaqs/Citypaq terminal in order to continue' mod='correos'}";
var CR_CARRIERSOFFICE = [{$cr_carrieroffice|escape:'htmlall':'UTF-8'}];	
var CR_CARRIERSHOMEPAQ = [{$cr_carrierhomepag|escape:'htmlall':'UTF-8'}];
var cr_module_dir = "{$cr_module_dir|escape:'htmlall':'UTF-8'}";
var isapi = typeof google === 'object';
if (!isapi)
	document.write("<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'><\/script>");

</script>
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/proj4js-compressed.js"></script>
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/admin/admin_v422.js"></script>
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/front/correos_v422.js"></script>
<link href="{$module_dir|escape:'htmlall':'UTF-8'}views/css/front/style.css" rel="stylesheet" type="text/css" media="all" />

   <div id="correospaq" style="display:none;text-align:left;padding-left:85px">
 
      <span id="paq_search" >
      {l s='Introduce User name' mod='correos'}: <input type="text" id="paquser" name="paquser"  style="width:120px;margin-top:0; display: inline;"/> <span id="paq_loading" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/opc-ajax-loader.gif" alt="" /></span>
       <a href="#" class="paqsearch" id="paqsearch" onclick="correospaqsearch();return false;" title="Buscar paqs">{l s='Search' mod='correos'}</a>
         
      </span>
      
   <div id="paq_result" class="row" style="display:none;">
      <div class="col-xs-4">
         <a href="#" onclick="paqsearchshow();return false;" id="paqback"> {l s='Back to search again' mod='correos'}</a>
         <span id="paq_result_ok" class="paq_result_ok" style="display:none;">
            {l s='Select terminal' mod='correos'}:
            <select id="correospaqs" name="correospaqs" style="width:120px; display: inline;" onchange="updatePaq()">
            </select>
         
         </span>
         <span class="paq_result_ok" style="display:none;">
            <br clear="left" />
            <p style="font-size:13px; font-weight:bold; padding-top:20px">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
         
         <p class="cr_contact_details"> <strong>E-mail:</strong> 
         <input type="text" name="paq_email" id="paq_email" style="width:180px; margin-left:10px; margin-right:25px"  value="" onchange="updatePaq()" />  </p>
            
            <p class="cr_contact_details"> <strong>{l s='Mobile number' mod='correos'}:</strong> 
            <input type="text" style="width:100px; border: 1px solid #ffd300; padding:2px" name="paq_mobile" id="paq_mobile" value="" onchange="updatePaq()" /></p>
         
         <br clear="both">
         
         </span>
         <span id="paq_result_fail" class="paq_register_link" style="display:none;">
            {l s='User no valid' mod='correos'}
         </span>
         
         <a href="#" class="paqsearch"  onclick="$( '#citypaqs_options' ).toggle();$( this ).toggle();return false;" title="{l s='Search other' mod='correos'}">{l s='Search other' mod='correos'}</a>
      </div>
      <div class="col-xs-8">
         <div id="citypaqs_options" style="display:none">
         
               
               <div class="radio_citypaq_searchtype" style="float:right">
                  <div class="radio_citypaq_searchtype_row">
                     <a href="#" class="paqsearch" id="paqsearch_other" onclick="cityPaqSearch();return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
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
                     <input type="radio" name="radio_citypaq_searchtype" class="radio_citypaq_searchtype radio_citypaq" value="state" id="citypaq_searchtype_state" onchange="getStatesWithCitypaq()">
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
                     <select id="citypaqs" style="width:360px" name="citypaqs" onchange="Correos.CityPaq_setGoogleMaps();Correos.setSelectedPaq('citypaqs');updatePaq()"></select>
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
                      
                      <a href="#" onclick="addToFavorites();return false;" id="addtofavorites_btn">
                        <span class="arrow_rightbefore"></span><span id="addtofavorites_txt">{l s='Add to favourites' mod='correos'}</span><span class="arrow_rightafter"></span> 
                      </a>
                       <span id="addtofavorites_loading" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
                      
                    
                    </span>
                      
                  </div>
               
               </div>
           
            </div>
      </div>   
   </div>
      
      <input type="hidden" id="paq_token" name="paq_token" value=""/>
      <input type="hidden" id="paq_data" name="paq_data" value=""/>
      <input type="hidden" id="selectedpaq_code" name="selectedpaq_code"/>
   </div>

<div id="correos_content" style="padding-left:235px">
	<div id="message_no_office_error" style="display: none;">{$message_no_office_error|escape:'htmlall':'UTF-8'}</div> 
	<div id="loadingmask" style="display: none;"><img src="{$cr_module_dir|escape:'htmlall':'UTF-8'}/views/img/opc-ajax-loader.gif" alt="" />{l s='Loading...' mod='correos'}</div>
	<div id="oficinas_correos_content" style="display: none;">

		<div class="correos_actions" >
			<div class="correos_button_search">
			{l s='Enter Post Code to find office' mod='correos'}: 
			<input type="text" id="correos_postcode"  name="correos_postcode" value="" style="display:inline-block" />     	
			<input type="button" value="Buscar" class="btn_correos" onclick="GetcorreosPoint(); return false; " />
			</div>
		</div>
		 <select id="correosOfficesSelect" name="correosOfficesSelect" onchange="correosInfo();updateOfficeInfo();"></select>
		 <input type="hidden" id="oficina_correos_data" name="oficina_correos_data" value="" />
		 <input type="hidden" id="oficina_correos_id" name="oficina_correos_id" value="" />
		 <br />
		 <div id="correo_info_nombre">
			<p><strong>{l s='Office' mod='correos'}</strong></p>
			<p id="correos_nombreoficina"></p>
		 </div>
		 <div>
			<p><strong>{l s='Address' mod='correos'}</strong></p>
			<p id="correosOfficeAddress"></p>
		 </div>
		<div id="correosInfoMap"></div>
		 <div id="correos_info_horarios">
			<p><strong>{l s='Opening hours' mod='correos'}</strong></p>
			<p>{l s='Opening hours Monday to Friday' mod='correos'}: <span id="horariolv"></span></p>
			<p>{l s='Opening hours Saturday' mod='correos'}: <span id="horarios"></span></p>
			<p>{l s='Opening hours Sunday' mod='correos'}: <span id="horariof"></span></p>
		 </div>
	   <br clear="left" />
		<p style="padding-top:20px" id="correos_maplink_info"><a id="correos_maplink" style="text-decoration:underline" href="" target="_blank">{l s='Open in new window' mod='correos'}</a></p>

		<br clear="left" />
		<p style="font-size:13px; font-weight:bold">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
		<p style="font-size:13px"><strong>E-mail:</strong> <input type="text" name="correos_email" id="correos_email" style="width:200px; margin:0 50px 0 10px; display:inline-block" onchange="updateOfficeInfo();" value=""  />  <strong>{l s='Mobile number' mod='correos'}:</strong> <input type="text" style="width:100px; margin:0 10px; display:inline-block" name="correos_mobile" id="correos_mobile" onchange="updateOfficeInfo();" value="" /><strong>{l s='Language SMS' mod='correos'}:</strong> 
 <select style="width:85px; display:inline-block" name="correos_mobile_lang" id="correos_mobile_lang" onchange="updateOfficeInfo();">
 <option value="1">Castellano</option>
 <option value="2">Catalá</option>
 <option value="3">Euskera</option>
 <option value="4">Gallego</option>
 </select>
</p>
	</div>
</div>
