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
<div id="preregister">
  <!-- Modal -->
  <div class="modal large fade"  id="CorreosModalPreregister" role="dialog">
    <div class="modal-dialog">
    
<!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='Shipment details. Can Modify registered data before sending' mod='correos'}</h4>
        </div>
        
        <div class="modal-body" id="CorreosModalContentPreregister">
         <div class="col-xs-6 form-horizontal" style="padding-right: 10px">
         
            <h2>{l s='Sender details' mod='correos'}</h2>
            <hr>
             
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Sender name' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_firstname" name="sender[firstname]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Sender surname' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_lastname" name="sender[lastname]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
               {l s='DNI' mod='correos'}
               </label>
               <div class="col-lg-7">
                <input type="text" value="" id="sender_dni" name="sender[dni]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Sender company' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_company" name="sender[company]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                   {l s='Contact person' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_contact_person" name="sender[contact_person]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Address' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_address" name="sender[address]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='City' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_city" name="sender[city]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Postal Code' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_postcode" name="sender[postcode]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Province' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_state" name="sender[state]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Land line' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_phone" name="sender[phone]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Mobile phone' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_mobile" name="sender[mobile]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='E-mail' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="sender_email" name="sender[email]" class="form-control" autocomplete="off">
               </div>
            </div>  
            

         </div>
         <div class="col-xs-6 form-horizontal" style="padding-left: 10px">
             <h2>{l s='Recipient details' mod='correos'}</h2>
             <hr>
             
             <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Recipient name' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.customer_firstname|escape:'html':'UTF-8'}" name="recipient[firstname]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                 {l s='Recipient surname' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.customer_lastname|escape:'html':'UTF-8'}" name="recipient[lastname]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
               {l s='DNI' mod='correos'}
               </label>
               <div class="col-lg-7">
                <input type="text" value="{$order_data.customer_dni|escape:'html':'UTF-8'}" name="recipient[dni]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                 {l s='Recipient company' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.customer_company|escape:'html':'UTF-8'}" name="recipient[company]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                   {l s='Contact person' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{if !empty($order_data.customer_company)}{$order_data.customer_firstname|escape:'html':'UTF-8'} {$order_data.customer_lastname|escape:'html':'UTF-8'}{/if}" name="recipient[contact_person]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Address' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.delivery_address|escape:'html':'UTF-8'}" id="recipient_address" name="recipient[address]" class="form-control" autocomplete="off"{if $is_office || $is_correospaq} readonly="readonly"{/if}>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Address' mod='correos'} 2
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.delivery_address2|escape:'html':'UTF-8'}" id="recipient_address2" name="recipient[address2]" class="form-control" autocomplete="off"{if $is_office || $is_correospaq} readonly="readonly"{/if}>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='City' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.delivery_city|escape:'html':'UTF-8'}" id="recipient_city" name="recipient[city]" class="form-control" autocomplete="off"{if $is_office || $is_correospaq} readonly="readonly"{/if}>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Postal Code' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.delivery_postcode|escape:'html':'UTF-8'}" id="recipient_postcode" name="recipient[postcode]" class="form-control" autocomplete="off"{if $is_office || $is_correospaq} readonly="readonly"{/if}>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Province' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.delivery_state|escape:'html':'UTF-8'}" id="recipient_state" name="recipient[state]" class="form-control" autocomplete="off"{if $is_correospaq} readonly="readonly"{/if}>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Land line' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.phone|escape:'html':'UTF-8'}" name="recipient[phone]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Mobile phone' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.mobile|escape:'html':'UTF-8'}" id="recipient_mobile" name="recipient[mobile]" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Language SMS' mod='correos'}
               </label>
               <div class="col-lg-7">
                    <select style="width:95px" name="recipient[mobile_lang]" >
                        <option value="1">Castellano</option>
                        <option value="2"{if $order_data.mobile_lang eq 2} selected{/if}>Catalá</option>
                        <option value="3"{if $order_data.mobile_lang eq 3} selected{/if}>Euskera</option>
                        <option value="4"{if $order_data.mobile_lang eq 4} selected{/if}>Gallego</option>
                    </select>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='E-mail' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$order_data.email|escape:'html':'UTF-8'}" name="recipient[email]" class="form-control" autocomplete="off">
               </div>
            </div>
             
            
            
      </div>
      
         <br style="clear:left">
   
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >{l s='Close' mod='correos'}</button>
        </div>
      </div>
      
    </div>
  </div>
  </div>  
<style>
#CorreosModalPreregister .modal-dialog
{
    width: 830px; 
}
</style>
