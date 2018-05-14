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
  <!-- Modal -->
  <div class="modal large fade"  id="CorreosModal" role="dialog">
    <div class="modal-dialog">
    
<!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='COLLECTION ADDRESS. Modify only if not match with the Sender address' mod='correos'}</h4>
        </div>
        <div class="modal-body" id="CorreosModalContent">
         <form class="form clearfix " enctype="multipart/form-data" method="post" id="rma_form" >
         <div class="col-xs-6 form-horizontal" style="padding-right: 10px">
         
            <h2>{l s='Sender details' mod='correos'}</h2>
            <hr>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Sender name' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->firstname|escape:'htmlall':'UTF-8'}" id="customer_sender_nombre" name="customer_sender_nombre" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Sender surname' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->lastname|escape:'htmlall':'UTF-8'}" id="customer_sender_apellidos" name="customer_sender_apellidos" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
               {l s='DNI' mod='correos'}
               </label>
               <div class="col-lg-7">
                <input type="text" value="{$address->dni|escape:'htmlall':'UTF-8'}" id="customer_sender_dni" name="customer_sender_dni" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Sender company' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->company|escape:'htmlall':'UTF-8'}" id="customer_sender_empresa" name="customer_sender_empresa" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                   {l s='Contact person' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->firstname|escape:'htmlall':'UTF-8'} {$address->lastname|escape:'htmlall':'UTF-8'}" id="customer_sender_presona_contacto" name="customer_sender_presona_contacto" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Address' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->address1|escape:'htmlall':'UTF-8'} {$address->address2|escape:'htmlall':'UTF-8'}" id="customer_sender_direccion" name="customer_sender_direccion" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='City' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->city|escape:'htmlall':'UTF-8'}" id="customer_sender_localidad" name="customer_sender_localidad" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Postal Code' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->postcode|escape:'htmlall':'UTF-8'}" id="customer_sender_cp" name="customer_sender_cp" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Province' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->state|escape:'htmlall':'UTF-8'}" id="customer_sender_provincia" name="customer_sender_provincia" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Land line' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->phone|escape:'htmlall':'UTF-8'}" id="customer_sender_tel_fijo" name="customer_sender_tel_fijo" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Mobile phone' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$address->phone_mobile|escape:'htmlall':'UTF-8'}" id="customer_sender_movil" name="customer_sender_movil" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='E-mail' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="{$customer->email|escape:'htmlall':'UTF-8'}" id="customer_sender_email" name="customer_sender_email" class="form-control" autocomplete="off">
               </div>
            </div>
         
         </div>
         <div class="col-xs-6 form-horizontal" style="padding-left: 10px">
             <h2>{l s='Recipient details' mod='correos'}</h2>
             <hr>
             <div class="form-group">
                <label class="control-label col-lg-5">
                  {l s='Select Recipient' mod='correos'}
                </label>
                <div class="col-lg-7">
                  <select name="recipient_sender" id="recipient_sender" class="form-control" autocomplete="off">
                     {foreach from=$senders_select key='sender' item='sender_name'}
                     <option value="{$sender|escape:'htmlall':'UTF-8'}">{$sender_name|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                  </select>
                </div>
            </div>
             
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Recipient name' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_nombre" name="recipient_nombre" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Recipient surname' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_apellidos" name="recipient_apellidos" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
               {l s='DNI' mod='correos'}
               </label>
               <div class="col-lg-7">
                <input type="text" value="" id="recipient_dni" name="recipient_dni" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Recipient company' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_empresa" name="recipient_empresa" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                   {l s='Contact person' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_presona_contacto" name="recipient_presona_contacto" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Address' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_direccion" name="recipient_direccion" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='City' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_localidad" name="recipient_localidad" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Postal Code' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_cp" name="recipient_cp" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Province' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_provincia" name="recipient_provincia" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Land line' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_tel_fijo" name="recipient_tel_fijo" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='Mobile phone' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_movil" name="recipient_movil" class="form-control" autocomplete="off">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-lg-5">
                  {l s='E-mail' mod='correos'}
               </label>
               <div class="col-lg-7">
               <input type="text" value="" id="recipient_email" name="recipient_email" class="form-control" autocomplete="off">
               </div>
            </div>  
            
            
      </div>
      
         <br style="clear:left">
             <h2>{l s='E-mail message' mod='correos'}</h2>
             <hr>
            <div class="form-group">
            <textarea id="mail_message" name="mail_message" rows="3" class="form-control">{l s='Please find attached, the new label you need to print out put it on the parcel. Please take it to the nearest Post Office or contact us if you wish to organize a parcel collection' mod='correos'}</textarea>
            <p class="help-block">{l s='Message which the customer will receive when you generate de RMA Label' mod='correos'}</p>
         </div>
                                                        
            <button class="btn btn-primary pull-right has-action btn-save-general" name="request_rmalabel" type="submit">
            <i class="fa fa-save nohover"></i>
            {l s='Create RMA Label' mod='correos'}
        </button>
    

         <input type="hidden" name="customer_id_order" id="customer_id_order" value="{$id_order|escape:'htmlall':'UTF-8'}"/>
      </form>
         
         
         
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >{l s='Cerrar' mod='correos'}</button>
        </div>
      </div>
      
    </div>
  </div>
<style>
#CorreosModal .modal-dialog
{
    width: 830px; 
}
</style>
<script>
var senders_json = {$senders|json_encode};
$(function() {
   $('#recipient_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
            
                if(sender)  {
                    $("#recipient_nombre").val(sender.nombre);
                    $("#recipient_apellidos").val(sender.apellidos);
                    $("#recipient_dni").val(sender.dni);
                    $("#recipient_empresa").val(sender.empresa);
                    $("#recipient_presona_contacto").val(sender.presona_contacto);
                    $("#recipient_direccion").val(sender.direccion);
                    $("#recipient_localidad").val(sender.localidad);
                    $("#recipient_cp").val(sender.cp);
                    $("#recipient_provincia").val(sender.provincia);
                    $("#recipient_tel_fijo").val(sender.tel_fijo);
                    $("#recipient_movil").val(sender.movil);
                    $("#recipient_email").val(sender.email);
                }
               
            }
            
         });
    });
   $('#recipient_sender').trigger("change");
});
</script>