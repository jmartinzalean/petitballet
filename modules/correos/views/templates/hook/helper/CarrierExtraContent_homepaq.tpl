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
{if $params.correos_config.presentation_mode == 'popup'} 
<div style="text-align: right;">
<span class="selected_paq"></span> <a data-toggle="modal" href="#paqmodal-correos{$params.id_carrier|intval}" class="correos_popuplink"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt office">{l s='Find Terminal' mod='correos'}</span><span class="arrow_rightafter"></span></a>
</div>


<div class="modal fade modal-correos" id="paqmodal-correos{$params.id_carrier|intval}"  role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='Selected Terminal for the order' mod='correos'}</h4>
        </div>
        <div class="modal-body">
{/if}

<span id="paq_search{$params.id_carrier|intval}" class="paq_search">
<span class="label">{l s='Introduce your User name' mod='correos'}:</span> <input type="text" id="paquser{$params.id_carrier|intval}" name="paquser" class="paquser" value="{if isset($params.request_data->homepaq_user)}{$params.request_data->homepaq_user}{/if}" /> <span id="paq_loading{$params.id_carrier|intval}" style="display:none"> <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /></span>
	 <a href="#" class="paqsearch" id="paqsearch{$params.id_carrier|intval}" onclick="Correos.searchPaqs({$params.id_carrier|intval});return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
		</br>
     <span style="font-size:13px; color:red; margin:20px 0 0 0 !important; padding:0;">¡{l s='Not compatible with Cash on Delivery' mod='correos'}!</span> <br>
		<span class="paq_register_link" style="margin-top:15px; font-weight:bold">
	    <a href="http://www.correospaq.es/ss/Satellite?c=Page&cid=1363189180749&pagename=CorreosPaqSite/Page/A_Layout_PAQ" target="_blank" style="text-decoration:underline"> {l s='What is Homepaq and CityPaq?' mod='correos'}</a>
			<a href="https://online.correospaq.es/pages/registro.xhtml" class="paqurl" target="_blank">{l s='Free register!' mod='correos'}</a>
		</span>
	</span>
  
   
	<span id="paq_result{$params.id_carrier|intval}" class="paq_result" style="display:none;">
		<a href="#" onclick="Correos.paqSearchShow({$params.id_carrier|intval});return false;" id="paqback{$params.id_carrier|intval}" class="paqback">{l s='Back to search again' mod='correos'}</a>
		<span id="paq_result_ok{$params.id_carrier|intval}" class="paq_result_ok top" style="display:none;">
			{l s='Select your terminal' mod='correos'}:
			<select id="correospaqs{$params.id_carrier|intval}" name="correospaqs" class="correospaqs" onchange="Correos.setSelectedPaq('correospaqs', {$params.id_carrier|intval});Correos.updatePaq({$params.id_carrier|intval})">
			</select>
		
		</span>
      <span id="paq_result_fail{$params.id_carrier|intval}" class="paq_register_link paq_result_fail" style="display:none;">
			<span id="paq_result_fail_message{$params.id_carrier|intval}" class="paq_result_fail_message"></span>
			<a href="https://online.correospaq.es/pages/registro.xhtml" class="paqurl" target="_blank">{l s='Free register!' mod='correos'}</a>
		</span>
		<span class="paq_result_ok" style="display:none;">
      <br clear="left" />
         <span style="font-size:13px; color:red; margin:5px 0 0 0 !important; padding:0;">¡{l s='Not compatible with Cash on Delivery' mod='correos'}!</span> 
		 <br>
         <p style="padding-top:5px">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
         <p>E-mail:
         <input type="text" name="paq_email" id="paq_email{$params.id_carrier|intval}" class="paq_email" style="width:180px; margin-left:10px; margin-right:25px" value="{if isset($params.request_data->email) and $params.request_data->email != ''}{$params.request_data->email}{else}{$params.cr_client_email}{/if}" onchange="Correos.updatePaq({$params.id_carrier|intval})" onkeyup="Correos.tooglePaymentModules({$params.id_carrier|intval});" value="{$params.cr_client_email|escape:'htmlall':'UTF-8'}" />  
        {l s='Mobile number' mod='correos'}:
          <input type="text" name="paq_mobile"  class="paq_mobile" style="width:100px; margin-left:10px;"  id="paq_mobile{$params.id_carrier|intval}" value="{if isset($params.request_data->mobile->number) and $params.request_data->mobile->number != ''}{$params.request_data->mobile->number}{else}{$params.cr_client_mobile}{/if}" onchange="Correos.updatePaq({$params.id_carrier|intval})" onkeyup="Correos.tooglePaymentModules({$params.id_carrier|intval});" value="{$params.cr_client_mobile|escape:'htmlall':'UTF-8'}"/>
         </p>
      
      
		   <a href="#" class="paqsearch"  onclick="$( '#citypaqs_options{$params.id_carrier|intval}' ).toggle();$( this ).toggle();return false;" title="{l s='Search other' mod='correos'}">{l s='Search other' mod='correos'}</a>
         
         <div id="citypaqs_options{$params.id_carrier|intval}" style="display:none">
      
            
            <div class="radio_citypaq_searchtype">
               <div class="radio_citypaq_searchtype_row">
                  <a href="#" class="paqsearch paqsearch_other" id="paqsearch_other{$params.id_carrier|intval}" onclick="Correos.cityPaqSearch({$params.id_carrier|intval});return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
               </div>
               <div class="radio_citypaq_searchtype_row">
                    <input type="text" name="citypaq_cp" class="citypaq_cp" id="citypaq_cp{$params.id_carrier|intval}">
                    <select id="citypaq_state{$params.id_carrier|intval}" class="citypaq_state" style="display:none"></select>
                    <span id="citypaq_searchtype_state_loading" style="display:none"> <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
               </div>
               <div class="radio_citypaq_searchtype_row">
                    <select id="citypaqsearchby{$params.id_carrier|intval}" class="citypaqsearchby">
                        <option value="postcode">{l s='Search by Postal Code' mod='correos'}</option>
                        <option value="state">{l s='Search by State' mod='correos'}</option>
                    </select>
               </div>
               

            </div>
            <span id="citypaq_search_loading{$params.id_carrier|intval}" class="citypaq_search_loading" style="display:none"> <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
            <span id="citypaq_search_fail{$params.id_carrier|intval}" class="citypaq_search_fail" style="display:none"></span>
            
            <div id="citypaqs_map_options{$params.id_carrier|intval}" class="citypaqs_map_options" style="display:none">             
               <div id="citypaqs_map_wrapper{$params.id_carrier|intval}" class="citypaqs_map_wrapper">   
               <div id="citypaqs_map{$params.id_carrier|intval}" class="citypaqs_map"></div>
               </div>
               <div id="citypaqs_info{$params.id_carrier|intval}" class="citypaqs_info">
               <p><strong>{l s='Terminal' mod='correos'}:</strong></p>
                  <select id="citypaqs{$params.id_carrier|intval}" name="citypaqs" class="citypaqs" onchange="Correos.CityPaq_setGoogleMaps({$params.id_carrier|intval});Correos.setSelectedPaq('citypaqs', {$params.id_carrier|intval});Correos.updatePaq({$params.id_carrier|intval})"></select>
                 <br>
                 
                  <p><strong>{l s='Address' mod='correos'}:</strong></p>
                  <p id="citypaqs_address{$params.id_carrier|intval}"></p>
                  <p><strong>{l s='Schedule' mod='correos'}: </strong><span id="citypaqs_schedule{$params.id_carrier|intval}"></span></p>
                  <!--
                  <a href="#" onclick="setselectedpaq('citypaqs');update_paq();return false;"><span class="arrow_leftbefore"></span><span class="correos_popuplinktxt">{l s='Select this Terminal' mod='correos'}</span><span class="arrow_leftafter"></span></a>
                  -->
                  <p style="font-size:13px; margin-top:10px;"><strong>{l s='Selected Terminal for the order' mod='correos'}:</strong> <span class="selected_paq"></span></p>
                  <br>
                  <span id="addtofavorites{$params.id_carrier|intval}" style="display:none">
                   
                   <a href="#" onclick="Correos.addToFavorites({$params.id_carrier|intval});return false;" id="addtofavorites_btn{$params.id_carrier|intval}">
                     <span class="arrow_rightbefore"></span><span id="addtofavorites_txt" class="addtofavorites_txt">{l s='Add to favourites' mod='correos'}</span><span class="arrow_rightafter"></span> 
                   </a>
                    <span id="addtofavorites_loading{$params.id_carrier|intval}" style="display:none"> <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
                   
                 
                 </span>
                   
               </div>
            
            </div>
        
         </div>
		
      
      </span>
		
	</span>
	{if $params.correos_config.presentation_mode == 'popup'}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='correos'}</button>
        </div>
      </div>
   </div>
</div>
{/if}
    <input type="hidden" id="selectedpaq_code{$params.id_carrier|intval}" name="selectedpaq_code" value="{if isset($params.request_data->homepaq_code)}{$params.request_data->homepaq_code|escape:'htmlall':'UTF-8'}{/if}"/>
    
</div>
<style>
.paqsearch {
    color: #000;
    font-style: italic;
}
</style>