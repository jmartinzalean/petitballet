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
<div class="panel-heading">{l s='Request RMA' mod='correos'}</div>
   <div class="panel-body" style="width:100%">
      
         
         <div class="form-group" id="search-customer-form-group">
            <label class="control-label col-lg-3">
				<span data-original-title="{l s='Search an existing customer typing first letters of his name' mod='correos'}." class="label-tooltip" data-toggle="tooltip" title="">
					{l s='Search customer' mod='correos'}
				</span>
			</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="col-lg-6">
						<div class="input-group">
							<input type="text" value="{if isset($smarty.post.customer_name)}{$smarty.post.customer_name|escape:'htmlall':'UTF-8'}{/if}" id="customer">
							<span class="input-group-addon">
								<i class="icon-search"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
       <div class="row">
			<div id="customers"></div>
		</div>
       <div id="lastOrders" style="display:none">
         <table class="table">
            <thead>
					<tr>
						<th><span class="title_box">{l s='ID' mod='correos'}</span></th>
						<th><span class="title_box">{l s='Date' mod='correos'}</span></th>
						<th><span class="title_box">{l s='Products' mod='correos'}</span></th>
						<th><span class="title_box">{l s='Total paid' mod='correos'}</span></th>
						<th><span class="title_box">{l s='Status' mod='correos'}</span></th>
                  <th><span class="title_box">{l s='RMA Label' mod='correos'}</span></th>
						<th></th>
					</tr>
				</thead>
            <tbody></tbody>
         </table>
      </div>
       <div class="col-lg-10" id="loading-mask" style="display:none">
				<div class="row text-center">
                <img  src="{$img_dir|escape:'htmlall':'UTF-8'}admin/dashboard_loading.gif"/>
    
            </div>
      </div>      
     <div class="panel-body">
      <form class="form clearfix " enctype="multipart/form-data" method="post" id="rma_form" style="display:none">
         <h2>{l s='Sender details' mod='correos'}</h2>
         <hr>
         <div class="form-group">
            <label class="control-label">
               {l s='Sender name' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_nombre" name="customer_sender_nombre" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_nombre.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Sender surname' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_apellidos" name="customer_sender_apellidos" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_apellidos.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
            {l s='DNI' mod='correos'}
            </label>
             <input type="text" value="" id="customer_sender_dni" name="customer_sender_dni" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Sender company' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_empresa" name="customer_sender_empresa" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_empresa.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
                {l s='Contact person' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_presona_contacto" name="customer_sender_presona_contacto" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_presona_contacto.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Address' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_direccion" name="customer_sender_direccion" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='City' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_localidad" name="customer_sender_localidad" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Postal Code' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_cp" name="customer_sender_cp" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Province' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_provincia" name="customer_sender_provincia" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Land line' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_tel_fijo" name="customer_sender_tel_fijo" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Mobile phone' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_movil" name="customer_sender_movil" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='E-mail' mod='correos'}
            </label>
            <input type="text" value="" id="customer_sender_email" name="customer_sender_email" class="form-control" autocomplete="off">
         </div>
         
         <br/><br/>
          <h2>{l s='Recipient details' mod='correos'}</h2>
          <hr>
          <div class="form-group">
             <div>
               {if isset($sender_form.options.select_sender.data)}
               <select name="recipient_sender" id="recipient_sender" class="form-control" autocomplete="off">
                  {foreach from=$sender_form.options.select_sender.data key="key" item="item"}
                     <option {if $sender_form.options.select_sender.default_option eq $key}selected="true"{/if} value="{$key|escape:'htmlall':'UTF-8'}">{$item|escape:'htmlall':'UTF-8'}</option>
                  {/foreach}
               </select>
               {/if}
            </div>
            <p class="help-block">{l s='Select Recipient' mod='correos'}</p>
         </div>
          
         <div class="form-group">
            <label class="control-label">
               {l s='Recipient name' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_nombre.value|escape:'htmlall':'UTF-8'}" id="recipient_nombre" name="recipient_nombre" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_nombre.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Recipient surname' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_apellidos.value|escape:'htmlall':'UTF-8'}" id="recipient_apellidos" name="recipient_apellidos" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_apellidos.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
            {l s='DNI' mod='correos'}
            </label>
             <input type="text" value="{$sender_form.options.sender_dni.value|escape:'htmlall':'UTF-8'}" id="recipient_dni" name="recipient_dni" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Recipient company' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_empresa.value|escape:'htmlall':'UTF-8'}" id="recipient_empresa" name="recipient_empresa" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_empresa.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
                {l s='Contact person' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_presona_contacto.value|escape:'htmlall':'UTF-8'}" id="recipient_presona_contacto" name="recipient_presona_contacto" class="form-control" autocomplete="off">
            <p class="help-block">{$sender_form.options.sender_presona_contacto.help|escape:'htmlall':'UTF-8'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Address' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_direccion.value|escape:'htmlall':'UTF-8'}" id="recipient_direccion" name="recipient_direccion" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='City' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_localidad.value|escape:'htmlall':'UTF-8'}" id="recipient_localidad" name="recipient_localidad" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Postal Code' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_cp.value|escape:'htmlall':'UTF-8'}" id="recipient_cp" name="recipient_cp" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Province' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_provincia.value|escape:'htmlall':'UTF-8'}" id="recipient_provincia" name="recipient_provincia" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Land line' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_tel_fijo.value|escape:'htmlall':'UTF-8'}" id="recipient_tel_fijo" name="recipient_tel_fijo" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Mobile phone' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_movil.value|escape:'htmlall':'UTF-8'}" id="recipient_movil" name="recipient_movil" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='E-mail' mod='correos'}
            </label>
            <input type="text" value="{$sender_form.options.sender_email.value|escape:'htmlall':'UTF-8'}" id="recipient_email" name="recipient_email" class="form-control" autocomplete="off">
         </div>  
         
         <br><br>
          <h2>{l s='E-mail message' mod='correos'}</h2>
          <hr>
         <div class="form-group">
         <textarea id="mail_message" name="mail_message" rows="3" class="form-control">{l s='Please find attached, the new label you need to print out put it on the parcel. Please take it to the nearest Post Office or contact us if you wish to organize a parcel collection' mod='correos'}</textarea>
         <p class="help-block">{l s='Message which the customer will receive when you generate de RMA Label' mod='correos'}</p>
      </div>
      
         <div class="nopadding clear clearfix">
              <hr>
                                                        
            <button class="btn btn-primary pull-right has-action btn-save-general" name="form-request_rma" type="submit">
            <i class="fa fa-save nohover"></i>
            {l s='Create RMA Label' mod='correos'}
        </button>
    
         </div>
         
        <input type="hidden" name="customer_name" id="customer_name"/>
        <input type="hidden" name="customer_id" id="customer_id"/>
         <input type="hidden" name="customer_id_order" id="customer_id_order"/>
      </form>
      
     </div>
   

  <!-- Modal -->
  <div class="modal fade"  id="CorreosModalRmaCollection" role="dialog">
    <div class="modal-dialog">
    
<!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='COLLECTION ADDRESS. Modify only if not match with the Sender address' mod='correos'}</h4>
        </div>
        <div class="modal-body">
        
         <form method="post">
         <div class="nopadding clear clearfix ">
         <div class="form-horizontal">
         
            <div class="form-group">
               <div class="col-xs-4 text-right">
               <label for="collection_address" class="control-label">
                   {l s='Name and surname' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_clientname" name="collection_clientname" class="form-control" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
               <label for="collection_address" class="control-label">
                   {l s='Address' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_address" name="collection_address" class="form-control" autocomplete="off">
               </div>
            </div>
     
     
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_city" class="control-label">
                     {l s='City' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_city" name="collection_city" class="form-control" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_postalcode" class="control-label">
                  {l s='Postal Code' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_postalcode" name="collection_postalcode" class="form-control" autocomplete="off">
               </div>   
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_state" class="control-label">
                     {l s='Province' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_state" name="collection_state" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label">
                     {l s='Land line' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_phone" name="collection_phone" class="form-control" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_mobile_phone" class="control-label">
                     {l s='Mobile phone' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_mobile_phone" name="collection_mobile_phone" class="form-control" autocomplete="off">
               </div>
            </div>

            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label">
                     {l s='E-mail CC' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="" id="collection_email_cc" name="collection_email_cc" class="form-control" autocomplete="off">
               </div>
            </div> 
         </div>
         <div class="form-group pull-left col-xs-4">
            <label class="control-label">
               {l s='Time of collection' mod='correos'}  
            </label>
            <input type="text" value="" id="collection_tome" name="collection_time" class="form-control" placeholder="{l s='eg, 10:00-12:00' mod='correos'}" autocomplete="off">
         </div> 
         <div class="form-group pull-left col-xs-4">
            <label class="control-label">
               {l s='Date of collection' mod='correos'}  
            </label>
            <input type="text" value="" id="collection_date" name="collection_date" class="datepicker date-input form-control" autocomplete="off">
            
         </div> 
         <div class="form-group pull-left col-xs-4">
            <label class="control-label">
               {l s='Number of pieces' mod='correos'}  
            </label>
            <input type="text" value="1" id="collection_pieces" name="collection_pieces" class="form-control" autocomplete="off">
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Comments' mod='correos'}
            </label>
            <textarea class="form-control" name="collection_comments" rows="5"></textarea>
         </div>
         <hr>
         <button class="btn btn-primary pull-right has-action btn-save-general" name="form-request_rma_collection" type="submit">
            <i class="fa fa-save nohover"></i>
            {l s='Request collection' mod='correos'}
        </button>
      </div>
   </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
<script>
      {if isset($smarty.post.customer_name)}
         searchCustomers()
      {/if}   
      
      {if isset($smarty.post.customer_id)}
         setupCustomer({$smarty.post.customer_id|escape:'htmlall':'UTF-8'});
      {/if} 
</script>