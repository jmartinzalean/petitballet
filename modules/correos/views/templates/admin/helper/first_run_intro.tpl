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
<div class="correos-intro row-eq-height">
   <div class="col-xs-4" >
      <p class="">
      El módulo de Correos para Prestashop, permite la posibilidad de generar etiquetas de sus pedidos de manera sencilla para los siguientes productos de Paquetería Comercial:
      </p>
       <ul class="">
         <li>Paq72 y Paq48 en sus modalidades de Entrega a Domicilio, Entrega en Oficina Elegida, y CorreosPaq</li>
         <li>Paquete Internacional Prioritario</li>

      </ul>
      
      <p class="size_l">Funcionalidades del m&oacute;dulo</p>
      <ul class="">
         <li>Creación de etiquetas de los pedidos</li>
         <li>Creación de etiquetas de devolución</li>
         <li>Ordenar recogidas desde el propio módulo</li>
         <li>Crear pedidos desde el back-office</li>
         <li>Seguimiento de Envios</li>
         <li>Gestión de multitiendas</li>
         <li>Generación de documentación Aduanera para los envíos que la requieren</li>
      </ul>
      <form style="margin:20px 0" method="post">
         <button class="btn btn-primary pull-right" id="is-client" name="is-client" type="submit">Ya Soy Cliente <small>con contrato</small></button>
         <button class="btn btn-primary" id="want-be-client" type="button">Quiero ser Cliente</button>
            
      </form>
   </div>
   <div class="col-xs-8"> 
      <img  class="img-responsive" src="{$img_dir|escape:'htmlall':'UTF-8'}admin/intro.jpg"/>
   </div>
</div>
<div style="display:none">
   
   <form id="correos-intro-form" class="form-horizontal" method="post">
   <h2 class="text-center">{l s='Please complete this information and our commercial services will contact with you shortly' mod='correos'}</h2>
   <hr>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
            {l s='Company' mod='correos'}
            </label>
         <div class="col-lg-9">
            <input type="text" name="customer_company" class="form-control" autocomplete="off" required>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
            {l s='Contact person' mod='correos'}
            </label>
         <div class="col-lg-9">
            <input type="text" name="customer_contact_person" class="form-control" autocomplete="off" required>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
            {l s='State' mod='correos'}
            </label>
         <div class="col-lg-9">
            <input type="text" name="customer_state" class="form-control" autocomplete="off" required>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
            {l s='Contact number' mod='correos'}
            </label>
         <div class="col-lg-9">
            <input type="text" name="customer_phone" class="form-control" autocomplete="off" required>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
            {l s='E-mail' mod='correos'}
            </label>
         <div class="col-lg-9">
            <input type="text" name="customer_email" class="form-control" autocomplete="off" required>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
            {l s='Comments' mod='correos'}
            </label>
         <div class="col-lg-9">
            <textarea name="customer_comments" rows="3" class="form-control" required></textarea>
         </div>
      </div>
      <div class="form-errors"></div>
      <hr>
      <button class="btn btn-primary pull-right has-action btn-save-general" name="customer_send_request" type="submit">
            <i class="fa fa-save nohover"></i>
            {l s='Send request' mod='correos'}
        </button>
        <br style="clear:right">
   </form>
</div>
<style>
   .row-eq-height {
    display: flex;
}
.correos-intro .col-xs-4{
   background-color:#006fc0;
   color:#ffd966;
   padding: 20px 20px 70px 20px;
   border-radius: 5px 0px 0px 5px;
   position: relative;
  
}
.correos-intro p, .correos-intro li {
 font-size: 1.1em;
}
.correos-intro .btn{
   font-size: 1.2em;
}
.correos-intro .btn small{
text-transform: lowercase;
}
.correos-intro .col-xs-4 form{
position: absolute;
left: 0;
bottom: 0;
width: 100%;
padding: 0 20px;
}
.correos-intro .col-xs-8{
   padding-left: 0;
}
.correos-intro .col-xs-8 .img-responsive {
   width:100%;
   border-radius: 0px 5px 5px 0px;
}
#want-be-client { 
   background-color: #ffd966;
   color:#006fc0;
   box-shadow: 0 -2px 0 #ffd966 inset;
   font-weight: bold;
}
#is-client {
   background-color: #bcd6ed;
   color:#44536a;
   box-shadow: 0 -2px 0 #bcd6ed inset;
   font-weight: bold;
}
@media (max-width: 1300px) { 
   .row-eq-height {
     display: block;
   }
   .correos-intro .col-xs-4, .correos-intro .col-xs-8 {
      width: 100%;
   }
   .correos-intro .col-xs-4{
   border-radius: 5px 5px 0px 0px;
}
.correos-intro .col-xs-8 .img-responsive {
   border-radius: 0px 0px 5px 5px;
}
.correos-intro .col-xs-8 {
  padding-right: 0;
}
  .correos-intro p, .correos-intro li {
    font-size: 12px;
   }
   .correos-intro .btn{
   font-size: 1.2em;
}
}

@media (min-width: 1300px) { 
  .correos-intro p, .correos-intro li {
    font-size: 11px;
   }
   .correos-intro .btn{
   font-size: 0.9em;
   
}

}

@media (min-width: 1400px) { 
  .correos-intro p, .correos-intro li {
    font-size: 12px;
   }
   .correos-intro .btn{
   font-size: 1.0em;
}
}

@media (min-width: 1500px) { 
  .correos-intro p, .correos-intro li {
   /* font-size: 1.1em;*/
    font-size: 13px;
   }
   .correos-intro .btn{
   font-size: 1.2em;
}
.correos-intro .size_l {
   padding-top: 20px
}
.correos-intro li {
   padding: 1px 0;
}
}
@media (min-width: 1600px) { 
  .correos-intro p, .correos-intro li {
    font-size: 1.2em;
   }
   .correos-intro .btn{
   font-size: 1.3em;
}
}
@media (min-width: 1700px) { 
  .correos-intro p, .correos-intro li {
    font-size: 1.3em;
   }
   .correos-intro .btn{
   font-size: 1.3em;
}
}

</style>