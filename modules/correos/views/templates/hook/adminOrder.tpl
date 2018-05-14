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
<div class="row">
<div class="col-lg-7">
	<div class="panel" id="correos-block">
      <div class="panel-heading">
         <i class="icon-truck"></i>
         {l s='Correos package information' mod='correos'}
      </div>
      <ul id="tabCorreos" class="nav nav-tabs">
         <li {if $tab eq 'label'}class="active"{/if}>
				<a href="#label" class="preventDefault">
					<i class="icon-print"></i>
					{l s='Label' mod='correos'}
				</a>
			</li>
			<li {if $tab eq 'customs'}class="active"{/if}>
				<a href="#customs" class="preventDefault">
					<i class="icon-file-text"></i>
					{l s='Customs' mod='correos'} 
				</a>
			</li>
         <li {if $tab eq 'carrier_change'}class="active"{/if}>
				<a href="#carrier_change" class="preventDefault">
					<i class="icon-truck"></i>
					{l s='Carrier change' mod='correos'} 
				</a>
			</li>
         <li {if $tab eq 'rma_returns'}class="active"{/if}>
				<a href="#rma_returns" class="preventDefault">
					<i class="icon-exchange"></i>
					{l s='Merchandise returns' mod='correos'} 
				</a>
                </li>
		</ul>
      <div class="tab-content panel">
         <!-- Tab labels -->
			<div id="label" class="tab-pane {if $tab eq 'label'}active{/if}">
				<h4 class="visible-print">{l s='Label' mod='correos'}</h4>
					<!-- Labels block -->
            {if $shipping_code OR file_exists('../modules/correos/pdftmp/exp-'|cat:$id_order|cat:'.txt')}
            <div class="well hidden-print form-horizontal">
               {if $shipping_code}
               <div class="form-group">
                  <label class="control-label col-lg-3"> {l s='Shipping code' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     {$shipping_code|escape:'htmlall':'UTF-8'}
                  </label>
               </div>
             
               <div class="form-group">
                  <label class="control-label col-lg-3"> {l s='Label Request' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     <a href="#" style="text-decoration:underline" class="preventDefault"
                  onClick="window.open('{$cr_module_dir|escape:'htmlall':'UTF-8'}/get_label.php?order={$id_order|escape:'htmlall':'UTF-8'}&codenv={$shipping_code|escape:'htmlall':'UTF-8'}&id_preregister={$id_preregister|escape:'htmlall':'UTF-8'}&correos_token={$correos_token|escape:'htmlall':'UTF-8'}','mywindow','width=500,height=500')">{l s='Download Label' mod='correos'}</a>
                  </label>
               </div>
               
               <div class="form-group">
						<label class="control-label col-lg-3"> {l s='Last state' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     {$last_tracking|escape:'htmlall':'UTF-8'}
                     <a href="#" style="text-decoration:underline" class="preventDefault" onClick="window.open('{$cr_module_dir|escape:'htmlall':'UTF-8'}/track_order.php?codenv={$shipping_code|escape:'htmlall':'UTF-8'}&correos_token={$correos_token|escape:'htmlall':'UTF-8'}','mywindow','width=550,height=500')">{l s='Show tracking history' mod='correos'}</a>
                  </label>
               </div>
               {/if}
               
               {if file_exists('../modules/correos/pdftmp/exp-'|cat:$id_order|cat:'.txt')}
                  <div class="form-group">
                     <label class="control-label col-lg-3"> {l s='TXT file' mod='correos'}: </label>
                     <label class="control-label col-lg-9" style="text-align: left !important">
                        <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}/pdftmp/exp-{$id_order|escape:'htmlall':'UTF-8'}.txt" style="text-decoration:underline" 
                        download="exp-{$id_order|escape:'htmlall':'UTF-8'}.txt">
                          {l s='Download' mod='correos'}
                        </a>
                     </label>
                  </div>
                  {/if}
                  {if $collection}
                  <div class="form-group">
                    <label class="control-label col-lg-3"> {l s='Collection code' mod='correos'}: </label>
                    <label class="control-label col-lg-9" style="text-align: left !important">
                       {$collection.confirmation_code|escape:'htmlall':'UTF-8'}
                    </label>
                 </div>
                 <div class="form-group">
                    <label class="control-label col-lg-3"> {l s='Collection date' mod='correos'}: </label>
                    <label class="control-label col-lg-9" style="text-align: left !important">
                       {dateFormat date=$collection.collection_date}
                    </label>
                 </div>
                 {/if}
                    
            </div>
            {/if}
            
            {if $preregister_error}
            <div class="alert alert-danger">
               <button data-dismiss="alert" class="close" type="button">×</button>
                     Error: {$preregister_error|escape:'htmlall':'UTF-8'}
				</div>
            {/if}
            
            
            {if $has_correos_carrier}
            <form id="correos_form_label" method="post">
            <div id="correos_form_label_content">
               <div class="well form-horizontal hidden-print">
					   
                     
                     {if $senders_select|@count gt 0}
                     <div class="form-group">
								<label class="control-label col-lg-3">{l s='Select sender' mod='correos'}</label>
								<div class="col-lg-6">
                           <select id="correos_sender" name="correos_sender">
                           {foreach from=$senders_select key='sender' item='sender_name'}
                              <option value="{$sender|escape:'htmlall':'UTF-8'}">{$sender_name|escape:'htmlall':'UTF-8'}</option>
                           {/foreach}
                           </select>
                          
								</div>
							</div>
                     {/if}
                     {if $require_customs}
                        <div class="form-group">
                           <h4 class="col-lg-6 text-center" >
                              <strong>{l s='Customs information' mod='correos'}:</strong> 
                           </h4>
                        </div>
                        <div class="form-group">
                           <label class="control-label col-lg-3">{l s='Select package description' mod='correos'}: </label>
                           <div class="col-lg-6">
                              <select id="goods_type" name="goods_type">
                              {foreach from=$customs_categories key='id' item='name'}
                                 <option value="{$id|escape:'htmlall':'UTF-8'}"{if $correos_config.customs_default_category eq $id} selected{/if}>{$name|escape:'htmlall':'UTF-8'}</option>
                              {/foreach}
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
								<label class="control-label col-lg-3 required"> {l s='First product value' mod='correos'}: </label>
                        <div class="col-lg-6">
                           <input type="text" class="form-control fixed-width-sm required" name="customs_firstproductvalue" id="customs_firstproductvalue" value="{$first_prduct.unit_price_tax_excl|number_format:2|replace:".":","|escape:'htmlall':'UTF-8'}">
                        </div>
                     </div>
                     
                     {/if}
                     <div class="form-group">
                        
                        <h4 class="col-lg-6 text-center" >
                           <strong>{l s='Insert package information' mod='correos'}:</strong> 
                        </h4>
                     </div>
                     <div class="form-group">
								<label class="control-label col-lg-3 required"> {l s='Weight' mod='correos'}: </label>
                        <div class="input-group col-lg-2">
                           <input type="text" id="correos_package_weight" name="correos_package_weight" 
                           value="{if isset($request_data->parcel_details->weight)}{$request_data->parcel_details->weight|escape:'htmlall':'UTF-8'}{else}{$weight|escape:'htmlall':'UTF-8'}{/if}" class="form-control required" title="{l s='Weight' mod='correos'} (Kg)"/>
							<span class="input-group-addon">Kg<span></span></span>
						</div>
                     </div>
                     <div class="form-group">
								<label class="control-label col-lg-3 {if $is_correospaq}required{/if}"> {l s='Long' mod='correos'}: </label>
                        <div class="input-group col-lg-2">
                           <input type="text" id="correos_package_long" name="correos_package_long" 
                            {if isset($request_data->parcel_details->long)}
                                value="{$request_data->parcel_details->long|escape:'htmlall':'UTF-8'}"
							{elseif $order_prducts|@count eq 1}
								{assign var=first_prduct_key value = $order_prducts|@key}
								
								 value="{$order_prducts.$first_prduct_key.depth|intval}"
							{else}
							 value="0"
						   {/if}
						   class="form-control {if $is_correospaq}required{/if}" title="{l s='Long' mod='correos'} (cm.)" />
							<span class="input-group-addon">cm<span></span></span>
						</div>
                     </div>
                     <div class="form-group">
								<label class="control-label col-lg-3 {if $is_correospaq}required{/if}"> {l s='Width' mod='correos'}: </label>
                        <div class="input-group col-lg-2">
                           <input type="text" id="correos_package_width" name="correos_package_width" 
                           {if isset($request_data->parcel_details->width)}
                            value="{$request_data->parcel_details->width|escape:'htmlall':'UTF-8'}"
						   {elseif  $order_prducts|@count eq 1}
								{assign var=first_prduct_key value = $order_prducts|@key}
								
								 value="{$order_prducts.$first_prduct_key.width|intval}"
							{else}
							 value="0"
						   {/if}
							  
						   class="form-control {if $is_correospaq}required{/if}" title="{l s='Width' mod='correos'} (cm.)" />
							
							<span class="input-group-addon">cm<span></span></span>
						</div>
                     </div>
                     <div class="form-group">
								<label class="control-label col-lg-3 {if $is_correospaq}required{/if}"> {l s='height' mod='correos'}: </label>
                        <div class="input-group col-lg-2">
                           <input type="text" id="correos_package_height"  name="correos_package_height" 
							 {if isset($request_data->parcel_details->height)}
                                value="{$request_data->parcel_details->height|escape:'htmlall':'UTF-8'}"
                            {elseif $order_prducts|@count eq 1}
								{assign var=first_prduct_key value = $order_prducts|@key}
								
								 value="{$order_prducts.$first_prduct_key.height|intval}"
							{else}
							 value="0"
						   {/if}
						
						   title="{l s='High' mod='correos'}" class="form-control {if $is_correospaq}required{/if}" />
							<span class="input-group-addon">cm<span></span></span>
						</div>
                     </div>
                     <div class="form-group">
								<label class="control-label col-lg-3"> {l s='Calculated Bulk' mod='correos'}:</label>
                        <div class="input-group col-lg-2">
                           <input type="text" id="correos_package_bult"  name="correos_package_bult" value="10" title="{l s='Bult' mod='correos'}" class="form-control" disabled/>
							<span class="input-group-addon">Kg<span></span></span>
						</div>
                     </div>
                     <div class="form-group">
								<label class="control-label col-lg-3"> {l s='All-risks insurance' mod='correos'}: </label>
                        <div class="input-group col-lg-2">
                           <input type="text" id="correos_package_insurance"  name="correos_package_insurance" 
                           value="{if isset($request_data->parcel_details->insurance_value)}{$request_data->parcel_details->insurance_value|escape:'htmlall':'UTF-8'}{else}0{/if}" title="{l s='All-risks insurance' mod='correos'}" class="form-control "/>
                           <span class="input-group-addon">€<span></span></span>
						   
						  
                        </div>
						 <p class="help-block" style="padding-left:25%">
											{l s='Max. 6000€' mod='correos'}
									</p>
                     </div>
					 <div class="form-group">
								<label class="control-label col-lg-3"> {l s='Observations' mod='correos'}: </label>
                        <div class="col-lg-7">
                           <input type="text" id="correos_package_observations"  name="correos_package_observations" value="{if isset($request_data->parcel_details->observations)}{$request_data->parcel_details->observations|escape:'htmlall':'UTF-8'}{/if}" maxlength="45" title="{l s='All-risks insurance' mod='correos'}" class="form-control" />
                           <p class="help-block">
											{l s='For the shipping label' mod='correos'}. {l s='Max. 45 characters' mod='correos'}
									</p>
                        </div>
                     </div>
                     
                     {if $is_office}
                        <div class="form-group">
                           <label class="control-label col-lg-3"> {l s='Selected Office' mod='correos'}: </label>
                           <label class="control-label col-lg-9" style="text-align: left !important">
                             <span id="selected_office">
                              {if $office}
                                 {$office->nombre|escape:'htmlall':'UTF-8'} - {$office->direccion|escape:'htmlall':'UTF-8'}, {$office->localidad|escape:'htmlall':'UTF-8'} 
                              {else}
                                 {l s='The customer has not selected a collection office. Please select one' mod='correos'}
                              {/if}	
                              </span>
                           </label>
                        </div>
                        <div class="form-group">
                           <label class="control-label col-lg-3"> {l s='Postal Code' mod='correos'}: </label>
                           <div style="float:left; margin-right:5px">
                           
                              <input type="text" id="correos_postcode" name="correos_postcode" value="{$postcode|escape:'htmlall':'UTF-8'}" title="{l s='Postal Code' mod='correos'}" class="form-control fixed-width-sm" />
                              <label class="control-label fixed-width-md" id="loadingmask" style="text-align: left !important; display:none;"><i class="icon-refresh icon-spin icon-fw icon-4x "></i> {l s='Loading...' mod='correos'}</label>
                              
                              <select id="offices_correos" name="offices_correos" style="display:none" onchange="correosInfo()" class="fixed-width-xl"></select> 
                              
                           </div>
                           <div>
                              <a id="searchoffices" class="btn btn-primary preventDefault" href="#" onclick="GetcorreosPoint()">
                                 {l s='Change Office' mod='correos'}
                              </a>
                              <a id="searchoffices_back" class="btn btn-primary preventDefault" href="#" style="display: none;" onclick="$(this).hide();$('#correos_postcode').show();$('#searchoffices').show();$('#offices_correos').hide();" style="display:none">
                                 {l s='Search again' mod='correos'}
                              </a>
                           </div>
                        </div>
                      
                     
                     {/if}
                     
                     {if $is_correospaq}
                        <div class="form-group">
                           <label class="control-label col-lg-3"> {l s='Selected CorreoPaq' mod='correos'}: </label>
                           <label class="control-label col-lg-9" style="text-align: left !important">
                             <span id="selected_paq">
                              {if $correos_paq}
                                 {$correos_paq->alias|escape:'htmlall':'UTF-8'} - {if !empty($correos_paq->streetType)}{$correos_paq->streetType|escape:'htmlall':'UTF-8'} {/if}{$correos_paq->address|escape:'htmlall':'UTF-8'}, 
                                 {$correos_paq->city|escape:'htmlall':'UTF-8'} 
                                
                              {else}
                                 {l s='The customer has not selected a CorreosPaq terminal. Please select one' mod='correos'}
                              {/if}	
                              </span>
                           </label>
                        </div>
                        <div class="alert alert-danger" id="homepaq_search_fail" style="display:none">
                        </div>
                        <div class="form-group">
                           <label class="control-label col-lg-3"> {l s='CorreosPaq user' mod='correos'}: </label>
                           <div style="float:left; margin-right:5px">
                           
                              <input type="text" id="homepaq_user" name="homepaq_user" value="{$homepaq_user|escape:'htmlall':'UTF-8'}" title="{l s='CorreosPaq user' mod='correos'}" class="form-control" />
                              <label class="control-label fixed-width-md" id="loadingmask" style="text-align: left !important; display:none;"><i class="icon-refresh icon-spin icon-fw icon-4x"></i> {l s='Loading...' mod='correos'}</label>
                              
                              <select id="paqs_favourites" name="paqs_favourites" style="display:none" onchange="correosPaqInfo();updateCorreosPaqInfo()" class="fixed-width-xl"></select> 
                              
                           </div>
                           <div>
                              <a id="searchpaqs" class="btn btn-primary preventDefault" href="#" onclick="GetPaqsFavourites()" >
                                 {l s='Change terminal' mod='correos'}
                              </a>
                              <a id="searchpaqs_back" class="btn btn-primary preventDefault" href="#" style="display: none;" onclick="$(this).hide();$('#homepaq_user').show();$('#searchpaqs').show();$('#paqs_favourites').hide();" style="display:none">
                                 {l s='Search again' mod='correos'}
                              </a>
                           </div>
                        </div>
                        
                     {/if}
                     
                     <div class="form-group">
								<label class="control-label col-lg-3"> {l s='Data check' mod='correos'}: </label>
                        <div class="col-lg-7">
                           <a data-toggle="modal" class="btn btn-default preventDefault" href="#CorreosModalPreregister" >
                         {l s='Check preregister data' mod='correos'}
                         <i class="icon-external-link"></i>
                         </a>
                         
                        </div>
                     </div>
                        
                  <br>
                  <button name="preregisterAgain" id="preregisterAgain" class="btn btn-primary pull-right" type="submit">
                     {if $shipping_code}
                     <i class="icon-refresh"></i> {l s='Register again' mod='correos'}
                     {else}
                     <i class="icon-file-pdf-o"></i> {l s='Register' mod='correos'}
                     {/if}
                  </button>
                  <button name="exportToFile" class="btn btn-primary" type="submit"> <i class="icon-file-text"></i> {l s='Export to a TXT file' mod='correos'} </button>
               </div>
               
                 
                  <input type="hidden" class="customs_firstproductvalue_hidden" name="customs_firstproductvalue_hidden" value="{$first_prduct.unit_price_tax_excl|number_format:2|replace:".":","|escape:'htmlall':'UTF-8'}" />
                  <input type="hidden" class="id_collection_office" name="id_collection_office" />
                  <input type="hidden" class="offices" name="offices"/>
                  <input type="hidden" class="homepaq_code" name="homepaq_code"/>
                  <input type="hidden" class="homepaq_token" name="homepaq_token" />
                  <input type="hidden" class="homepaqs" name="homepaqs"/>
               
               {include file=$correos_tpl|cat:'views/templates/hook/helper/adminOrder_preregister_form.tpl' 
               order_data=$order_data
               is_office=$is_office
               is_correospaq=$is_correospaq
                }
            </div>
            </form>
            {else}
               <div class="list-empty hidden-print">
                  <div class="list-empty-msg">
							<i class="icon-warning-sign list-empty-icon"></i>
							{l s='The carrier selected for the order is not Correos' mod='correos'}
						</div>
					</div>
				{/if}
			</div>
         <!-- End Tab labels -->
         
         
         <!-- Tab Customs -->
			<div id="customs" class="tab-pane {if $tab eq 'customs'}active{/if}">
				<h4 class="visible-print">{l s='Customs' mod='correos'}</h4>
					<!-- Customs block -->
        
   	
         {if $require_customs or $require_ddp}
            {if (file_exists('../modules/correos/pdftmp/customs_'|cat:$id_order|cat:'.pdf') and '../modules/correos/pdftmp/customs_'|cat:$id_order|cat:'.pdf'|@filesize > 0) OR
                 (file_exists('../modules/correos/pdftmp/customs_dcaf_'|cat:$id_order|cat:'.pdf')) OR 
                 (file_exists('../modules/correos/pdftmp/customs_ddp_'|cat:$id_order|cat:'.pdf'))
               }
            <div class="well hidden-print form-horizontal">
            
               {if file_exists('../modules/correos/pdftmp/customs_'|cat:$id_order|cat:'.pdf') and '../modules/correos/pdftmp/customs_'|cat:$id_order|cat:'.pdf'|@filesize > 0}
               <div class="form-group">
                  <label class="control-label col-lg-3"> {l s='Content Declaration' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     <a href="#" style="text-decoration:underline" class="preventDefault" onClick="window.open('{$cr_module_dir|escape:'htmlall':'UTF-8'}/pdftmp/customs_{$id_order|escape:'htmlall':'UTF-8'}.pdf','mywindow','width=500,height=500')">
                        {l s='Download documents' mod='correos'}
                     </a>
                     
                  </label>
               </div>
               {/if}
               
               {if file_exists('../modules/correos/pdftmp/customs_dcaf_'|cat:$id_order|cat:'.pdf')}
               <div class="form-group">
                  <label class="control-label col-lg-3"> {l s='DUA Documents' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     <a href="#" style="text-decoration:underline" class="preventDefault" onClick="window.open('{$cr_module_dir|escape:'htmlall':'UTF-8'}/pdftmp/customs_dcaf_{$id_order|escape:'htmlall':'UTF-8'}.pdf','mywindow','width=500,height=500')">
                        {l s='Download DUA' mod='correos'}
                     </a>
                  </label>
               </div>
               {/if}
               
               {if file_exists('../modules/correos/pdftmp/customs_ddp_'|cat:$id_order|cat:'.pdf')}
               <div class="form-group">
                  <label class="control-label col-lg-3"> {l s='DDP Documents' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     <a href="#" style="text-decoration:underline;" class="preventDefault" onClick="window.open('{$cr_module_dir|escape:'htmlall':'UTF-8'}/pdftmp/customs_ddp_{$id_order|escape:'htmlall':'UTF-8'}.pdf','mywindow','width=500,height=500')">
                      {l s='Download DDP' mod='correos'}
                      </a>
                  </label>
               </div>
               {/if}
            </div> 
            {/if}
            
            {if isset($request_customs_content_error_desc)}
            <div class="alert alert-danger">
               <button data-dismiss="alert" class="close" type="button">×</button>
                     Error: {$request_customs_content_error_desc|escape:'htmlall':'UTF-8'}
				</div>
            {/if}
            
            <form id="correos_form_customs" method="post">
               <div class="well form-horizontal hidden-print">
               
               {if $shipping_code}
                  <div class="form-group">
                     <h4 class="col-lg-6 text-center" >
                        <strong>{l s='Customs documents' mod='correos'}</strong> 
                     </h4>
                  </div>
				  
                  <div class="form-group">
                     <label class="control-label col-lg-3"> {l s='Number of pieces' mod='correos'}: </label>
                     <div class="col-lg-6">
                        <input type="text" name="number_pieces" value="1" title="{l s='Number of pieces' mod='correos'}" class="form-control fixed-width-xs" style="display: inline; margin-right: 5px" />
                        <button class="btn btn-primary" name="requestCustomsDocuments_DCAF" type="submit">{l s='Request DUA' mod='correos'}</button>
                     </div>
                  </div>
				  {if $require_ddp}
                  <div class="form-group">
                     <label class="control-label col-lg-3"> {l s='Number of pieces' mod='correos'}: </label>
                     <div class="col-lg-6">
                        <input type="text" name="number_pieces" value="1" title="{l s='Number of pieces' mod='correos'}" class="form-control fixed-width-xs" style="display: inline; margin-right: 5px"/>
                         <button class="btn btn-primary" name="requestCustomsDocuments_DDP"  type="submit">{l s='Request DDP' mod='correos'}</button>
                     </div>
                  </div>
				  {/if}
                  <div class="form-group">
                     <label class="control-label col-lg-3"> {l s='Content Declaration' mod='correos'}: </label>
                     <div class="col-lg-6">
                    
                         <button class="btn btn-primary" name="requestCustomsContent"  type="submit">{l s='Request documents' mod='correos'}</button>
                     </div>
                  </div>
               {else}
                  <div class="list-empty hidden-print">
                     <div class="list-empty-msg">
                        <i class="icon-warning-sign list-empty-icon"></i>
									{l s='Need to generate shipping label first' mod='correos'}
                     </div>
                  </div>
               {/if}
                 
               </div>
               
            </form>
         {else}
               <div class="list-empty hidden-print">
                  <div class="list-empty-msg">
							<i class="icon-warning-sign list-empty-icon"></i>
							{l s='The Customs documents are not required' mod='correos'}
						</div>
					</div>
				
         {/if}
         </div>
         
         <!-- Tab Carrier Change -->
			<div id="carrier_change" class="tab-pane {if $tab eq 'carrier_change'}active{/if}">
				<h4 class="visible-print">{l s='Change Carrier' mod='correos'} </h4>
					<!--  Carrier Change block -->
				<form id="change_carrier_form" method="post"> 
               <div class="well form-horizontal hidden-print">
               <input type="hidden" id="tax" name="tax" value="{$order->carrier_tax_rate|number_format:2|escape:'htmlall':'UTF-8'}" />
		
		
               <div class="form-group">
                  <label class="control-label col-lg-3">{l s='Carrier:' mod='correos'}</label>
                  <div class="col-lg-6" style="margin-left: -5px;" >
                     <select id="new_id_carrier" name="new_id_carrier">
                        
                        {foreach $carriers as $carrier}
                     <option id="{$tax|escape:'htmlall':'UTF-8'}" value="{$carrier.id_carrier|escape:'htmlall':'UTF-8'}" {if $carrier.id_carrier == $order->id_carrier}selected="selected"{/if}>
                        {$carrier.name|escape:'htmlall':'UTF-8'}
                        {if $tax}({Tax::getCarrierTaxRate($carrier.id_carrier, null)|escape:'htmlall':'UTF-8'}%){/if}
                     </option>
                     {/foreach}
                     </select>
                  </div>
                  <div class="col-lg-3">
                     <button id="carrier_change" type="submit" class="btn btn-primary" name="carrier_change">
                     {l s='Update' mod='correos'} </button>
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-lg-3">{l s='Shipping cost' mod='correos'}:</label>
                  <div class="input-group col-lg-3">
                     <input type="text" name="new_total_shipping_tax_incl" class="form-control" value="{$order->total_shipping_tax_incl|number_format:2|escape:'htmlall':'UTF-8'}" />
                     <span class="input-group-addon">{$currency->sign|escape:'htmlall':'UTF-8'} {if $tax_enabled > 0}{l s='(Tax incl.)' mod='correos'}{/if}<span>
                  </div>
               </div>
               <div class="form-group" style="{if $tax_enabled < 1}display:none;{/if}margin-top:10px">
                  <label class="control-label col-lg-3">{l s='Shipping cost' mod='correos'}:</label>
                  <div class="input-group col-lg-3">
                     <input type="text" name="new_total_shipping_tax_excl" class="form-control" value="{$order->total_shipping_tax_excl|number_format:2|escape:'htmlall':'UTF-8'}" />
                     <span class="input-group-addon">{$currency->sign|escape:'htmlall':'UTF-8'} {if $tax_enabled > 0}{l s='(Tax excl.)' mod='correos'}{/if}<span>
                  </div>
               </div>
               <div class="form-group" style="{if $tax_enabled < 1}display:none;{/if}margin-top:10px">
                  <label class="control-label col-lg-3">{l s='Enable custom car. Tax rate:' mod='correos'}</label>

                  <div class="input-group col-lg-3" style="float: left;" >
                     <input type="text" class="form-control" name="taxvalue" id="taxvalue" value="" disabled="disabled"/>
                     <span class="input-group-addon">%</span>
                     
                  </div>
                  
                  <span class="switch prestashop-switch fixed-width-lg" style="float: left;margin-left: 10px;" >
                     <input type="radio" name="chkTax" id="chkTax_on" value="1">
                     <label for="chkTax_on" class="radioCheck">{l s='on' mod='correos'}</label>
                     <input type="radio" name="chkTax" id="chkTax_off" value="0" checked="checked">
                     <label for="chkTax_off" class="radioCheck">{l s='off' mod='correos'}</label>
                     <a class="slide-button btn"></a>
                  </span>
                  
               </div>
               <div class="form-group">
                  <label class="control-label col-lg-3">{l s='Shipping weight:' mod='correos'}</label>
                  <div class="input-group col-lg-3">
                     <input type="text" name="new_order_carrier_weight" class="form-control" value="{$order_carrier->weight|number_format:3|escape:'htmlall':'UTF-8'}" />
                     <span class="input-group-addon">{$weight_unit|escape:'htmlall':'UTF-8'}<span>
                  </div>
               </div>
            </div>
         </form>
			</div>
         <!-- End Tab Carrier Change -->
         
			<!-- Tab returns -->
			<div id="rma_returns" class="tab-pane {if $tab eq 'rma_returns'}active{/if}">
				<h4 class="visible-print">{l s='Merchandise returns' mod='correos'}</h4>
					<!-- Return block -->
             {if $rma_error}
            <div class="alert alert-danger">
               <button data-dismiss="alert" class="close" type="button">×</button>
                     Error: {$rma_error|escape:'htmlall':'UTF-8'}
				</div>
            {/if}
             <form id="change_carrier_form" method="post"> 
               <div class="well form-horizontal hidden-print">
               <br>
               {if file_exists('../modules/correos/pdftmp/d-'|cat:$id_order|cat:'.pdf')}
               <div class="form-group">
                  <label class="control-label col-lg-3"> {l s='RMA Label' mod='correos'}: </label>
                  <label class="control-label col-lg-9" style="text-align: left !important">
                     <a href="#" style="text-decoration:underline" class="preventDefault" onClick="window.open('{$cr_module_dir|escape:'htmlall':'UTF-8'}/pdftmp/d-{$id_order|escape:'htmlall':'UTF-8'}.pdf','mywindow','width=500,height=500')">
                        {l s='Download RMA Label' mod='correos'}
                     </a>
                  </label>
               </div>
               {/if}
               
                  <div class="form-group">
                     <label class="control-label col-lg-2"></label>
                     <div class="input-group col-lg-3">
                        <a data-toggle="modal" class="btn btn-default preventDefault" href="#CorreosModal" >
                         {l s='Fill the form to request RMA' mod='correos'}
                         <i class="icon-external-link"></i>
                         </a>
                        
                     </div>
                  </div>
               </div>
            </form>
			</div>
      </div>
            
	</div>
</div>
</div>
 {include file=$correos_tpl|cat:'views/templates/hook/helper/adminOrder_rma_form.tpl' 
   senders_select=$senders_select
   senders=$senders
   address=$address
   customer=$customer
   id_order=$id_order
      }
<script>
   $('#correos-block a.preventDefault').click(function (e) {
      e.preventDefault()
	})
   $('#correos-block button[type="submit"]').click(function (e) {
      $(this).blur() 
 	})
   
   $('#tabCorreos a').click(function (e) {
      e.preventDefault()
		$(this).tab('show')
	})
   
   var correos_order_states = [{$cr_order_states|escape:'htmlall':'UTF-8'}];
   var HomePaqs = '';
   var noPaqsFound = "{l s='We are sorry, no results was found' mod='correos'}";
	$(document).ready(function()
	{
            
				$('button[name=submitState]').live('click',function(event) {
               
              
               
            
                   
                     var id_order_state = parseInt(document.getElementById('id_order_state').value);
                    
                   if(correos_order_states.indexOf(id_order_state) >= 0) {
                        
						var valid = true;
						var fields = "";
						$("#correos_form_label :input.redborder").each(function() {
							$(this).removeClass("redborder");
						});
						$("#correos_form_label :input.required").each(function() {
                            if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0"  ) {
                                $(this).addClass("redborder");
                                fields += " " + $(this).attr("title") + ",";
                                valid = false;
                            }
                        });
								
							var lastChar = fields.slice(-1);
							if(lastChar == ',') 
							fields = fields.slice(0, -1);


                        if(!valid)
                            alert("{l s='Please fill out all required fields' mod='correos'}:" + fields);
                        else {
                            var cloned =  $( "#correos_form_label_content" ).clone().hide();
                            $("select[name=id_order_state]").parent().append(cloned);
                        }
                               
                            return valid;
			
					}
							
                });
                   $('#preregisterAgain').live('click',function(event) {
                   
                        var valid = true;
                        var fields = "";
                        $("#correos_form_label :input.redborder").each(function() {
                            $(this).removeClass("redborder");
                        });
                            
                        $("#correos_form_label :input.required").each(function() {
                            if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0"  ) {
                                    $(this).addClass("redborder");
                                    fields += " " + $(this).attr("title") + ",";
                                    valid = false;
                                }
                            });
                                    
                        var lastChar = fields.slice(-1);
                        if(lastChar == ',') 
                            fields = fields.slice(0, -1);
       

                        if(!valid)
                            alert("{l s='Please fill out all required fields' mod='correos'}:" + fields);
                        else
                            $(this).find('i').prop('class', 'icon-refresh icon-spin');
                        return valid;
                        
                    });
							
					
			
					$("#correos_package_weight").keyup(function () { 
                    calcutaleBult();
                 });
					$("#correos_package_long").keyup(function () { 
                  calcutaleBult();
                    });
                    $("#correos_package_width").keyup(function () { 
                  calcutaleBult();
                 
                  });
					$("#correos_package_height").keyup(function () { 
                  calcutaleBult();
                 
                  });
            
			
      
			

					$("#correos_form :input.redborder").removeClass("redborder");	
					$( "#correos_form :input.required" ).change(function() {
						if($(this).val() != "")
							$(this).removeClass("redborder");
						
					});
				
				});
				
				var correosOffices;
				function GetcorreosPoint()
				{
					$("#loadingmask").show();
					$( "#correos_postcode" ).hide();
         $( "#searchoffices" ).hide();

					$.ajax(
					{
						type: 'POST',
						url: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}",
						data: {
              ajax: true,
              action: 'GetPointAdminOrder',
              postcode : $("#correos_postcode").val(),
              id_order: {$id_order|escape:'htmlall':'UTF-8'}
            },
						dataType: "json",
						async: true,
						success: function(result) 
						{
							correosOffices = result;
							if(correosOffices.length != 0) {
                        $(".offices").val(JSON.stringify(result));
								correos_fillDropDown(correosOffices);
							} else {
								
							}
							$( "#loadingmask" ).hide();
                     
                     
							$("#searchoffices_back").show();
						},
						error: function(xhr, ajaxOptions, thrownError) 
						{
							console.log(thrownError);
						
			
						}
								
					});	
				}
            function GetPaqsFavourites()
            {
               $("#loadingmask").show();
					$( "#homepaq_user" ).hide();
               $( "#searchpaqs" ).hide();
               $("#homepaq_search_fail").hide();
               /*
                    var _data = {
                        ajax: true,
                        action: 'GetPaqsFavourites',
                        homepaq_user : $("#homepaq_user").val()
                    };
               */
                   
                    var _data = {
                        ajax: true,
                        action: 'GetCorreosPaqs',
                        user: $("#homepaq_user").val(),
                        paq_mobile: $("#recipient_mobile").val(),
                        email: $("#recipient_email").val(),
                        id_order: {$id_order|escape:'htmlall':'UTF-8'}
                    };
    
					$.ajax(
					{
						type: 'POST',
						url: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}",
						data: _data,
						dataType: "json",
						async: true,
						success: function(result) 
						{
							if (typeof result.errorCode == 'undefined') {
                   
                        HomePaqs = result.homepaqs;
                        
                        $(".homepaq_token").val(result.token);
                        
                        
                        if(HomePaqs.length > 0){
                           $(".homepaqs").val(JSON.stringify(result.homepaqs));
                           var field = document.getElementById('paqs_favourites');
                        
                           for(i=field.options.length-1;i>=0;i--) { field.remove(i); } 
                           jQuery.each(HomePaqs, function() {
                              if (typeof this.alias != 'undefined')
                                 var name = this.alias;
                              else   
                                 var name = this.streetType + " " + this.address + " " + this.number + " " + this.city;
                              
                              var _option = new Option(name,this.code);
                              field.options.add(_option);
                                 
                           });
                          
                        
                        $("#paqs_favourites").show();
                        $("#homepaq_search_fail").hide();
                        correosPaqInfo()
                     
                     } else {
                   
                        $("#homepaq_search_fail").show();
                        
                        $("#homepaq_search_fail").html(noPaqsFound);
                     }
                  
                     
                     } else {
                     
                     $("#homepaq_search_fail").html(result.description);
                     $("#homepaq_search_fail").show();
                     
                  
                   }
							$( "#loadingmask" ).hide();
                     
                     
							$("#searchpaqs_back").show();
						},
						error: function(xhr, ajaxOptions, thrownError) 
						{
							console.log(thrownError);
						
			
						}
								
					});	
            }
            function correosPaqInfo()
				{
					var code = document.getElementById('paqs_favourites').value;

					jQuery.each(HomePaqs, function() {
						 if (this.code == code)
						 {
							$('.homepaq_code').val(this.code);
							
							$('#selected_paq').html(this.alias + " - " + this.streetType + " " + this.address + " " + this.number + ", " + this.city);
							$('#recipient_address').val(this.streetType + " " + this.address + " " + this.number);
                            $('#recipient_postcode').val(this.postalCode);
                            $('#recipient_city').val(this.city);
                            $('#recipient_state').val(this.state);
						 }		  
					});
				}
            
				function correos_fillDropDown(data)
				{
					if(document.getElementById('offices_correos'))
					{
						var field = document.getElementById('offices_correos');
						for(i=field.options.length-1;i>=0;i--) { field.remove(i); }       
						jQuery.each(data, function() {
							var _option = new Option(this.direccion+" - "+this.localidad,this.unidad);
							if (this.unidad == "{if $office}{$office->unidad|escape:'htmlall':'UTF-8'}{else}0{/if}")
								_option.setAttribute("selected", "selected");
							field.options.add(_option);
						});
						$("#offices_correos").show();
						correosInfo();
					}
				}
				function correosInfo()
				{
					var puntoActual = document.getElementById('offices_correos').value;

					jQuery.each(correosOffices, function() {
						 if (this.unidad == puntoActual)
						 {
							$('.id_collection_office').val(this.unidad);
							
							$('#selected_office').html(this.nombre + " - " + this.direccion + ", " + this.localidad);
                            $('#recipient_address').val(this.direccion);
                            $('#recipient_postcode').val(this.cp);
                            $('#recipient_city').val(this.localidad);

						 }		  
					});
				}
        function updateOfficeInfo()
        {
        /*Not in use now to not ovveride client's selection*/ 
               var _data = {
                    ajax: true,
                    action: 'updateOfficeInfoFromOrder',
                    selected_office : $("#offices_correos").val()
               };
               $.ajax(
               {
                  type: 'POST',
                  url: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}",
                  data: _data,
               });
      
        }
            function updateCorreosPaqInfo()
            {
            }
            function calcutaleBult()
            {
               
               var p_long = $("#correos_package_long").val();
               var width = $("#correos_package_width").val();
               var height = $("#correos_package_height").val();
               $("#correos_package_bult").val(parseInt(p_long) * parseInt(width) * parseInt(height) / 6000);
           
            }
            calcutaleBult();
		{if $senders|@count gt 0}

    
            var senders_json = {$senders|json_encode};
            $(function() {
               $('#correos_sender').change(function () {
                  var selected_key =  $(this).val();
                  jQuery.each(senders_json, function(key, sender) {
                        if(key == selected_key) {
                        
                            if(sender)  {
                                $("#sender_firstname").val(sender.nombre);
                                $("#sender_lastname").val(sender.apellidos);
                                $("#sender_dni").val(sender.dni);
                                $("#sender_company").val(sender.empresa);
                                $("#sender_contact_person").val(sender.presona_contacto);
                                $("#sender_address").val(sender.direccion);
                                $("#sender_city").val(sender.localidad);
                                $("#sender_postcode").val(sender.cp);
                                $("#sender_state").val(sender.provincia);
                                $("#sender_phone").val(sender.tel_fijo);
                                $("#sender_mobile").val(sender.movil);
                                $("#sender_email").val(sender.email);
                            }
                           
                        }
                        
                     });
                });
               $('#correos_sender').trigger("change");
            });
        {/if}

            </script>
			
         
<style>
.redborder { 
border: 1px solid red !important; 
}
</style>

