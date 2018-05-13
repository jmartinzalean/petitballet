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
 
<div class="panel-heading">{l s='Available carriers' mod='correos'}</div>
   <div class="panel-body">
    <table class="table table-condensed">
    <tbody>
      {assign var="botton_placed" value=0}
      {foreach from=$correos_carriers item=correos_carrier}
      {$botton_placed=0}
      <tr>
        <td>
        {if file_exists($path_module|cat:'views/img/'|cat:$logo_prefix|cat:$correos_carrier.code|cat:'.jpg'|lower)}
        <img class="imgm img-thumbnail" style="vertical-align:middle" src="{$path_logo|escape:'htmlall':'UTF-8'}{$logo_prefix|escape:'htmlall':'UTF-8'}{$correos_carrier.code|lower|escape:'htmlall':'UTF-8'}.jpg" alt="" title="" /></td>
        {/if}
        <td>{$correos_carrier.title|escape:'htmlall':'UTF-8'}</td>
        <td>
        
            
            {if $correos_carrier.id_reference eq '0'}
            {$botton_placed=1}
            <form method="post">
              <button class="btn btn-success" name="form-carriers" type="submit">
                  <i class="icon-plus-sign-alt"></i>
                  {l s='Install' mod='correos'}
               </button>
               <input type="hidden" name="correos_carrier_code" value="{$correos_carrier.code|escape:'htmlall':'UTF-8'}" />
             </form>  
             {else}
             
               {foreach from=$carriers item=carrier}
                  {if $correos_carrier.id_reference eq $carrier.id_reference}
                  {$botton_placed=1}
                   <a class="edit btn btn-default" title="{if $carrier.active eq 1}{l s='Configure' mod='correos'}{else}{l s='Configure & Active' mod='correos'} {/if}" 
                   href="{$link->getAdminLink('AdminCarrierWizard')|escape:'html':'UTF-8'}&id_carrier={$carrier.id_carrier|escape:'htmlall':'UTF-8'}">
                  <i class="icon-pencil"></i>
                     {if $carrier.active eq 1} {l s='Configure' mod='correos'} {else} {l s='Configure & Active' mod='correos'} {/if}
                  </a>
                  {/if}
               {/foreach}
               
              {if $botton_placed==0}
               <form method="post">
              <button class="btn btn-success" name="form-carriers" type="submit">
                  <i class="icon-plus-sign-alt"></i>
                  {l s='Install' mod='correos'}
               </button>
               <input type="hidden" name="correos_carrier_code" value="{$correos_carrier.code|escape:'htmlall':'UTF-8'}" />
             </form>  

             {/if}
              
      {/if}
        
        </td>
      </tr>
      {/foreach}
    </tbody>
  </table>
  <br/>
  <form class="form clearfix " enctype="multipart/form-data" method="post">
      <div class="form-group">
         <label class="control-label">{l s='Enable timetable selection for "Paq Premium Domicilio"' mod='correos'}</label>
         <span class="switch prestashop-switch fixed-width-lg">
            <input id="S0236_enabletimeselect_on" type="radio" {if $correos_config.S0236_enabletimeselect eq 1}checked="checked"{/if} value="on" name="S0236_enabletimeselect">
            <label for="S0236_enabletimeselect_on">{l s='Yes' mod='correos'}</label>
            <input id="S0236_enabletimeselect_off" type="radio" {if $correos_config.S0236_enabletimeselect eq 0}checked="checked"{/if} value="off" name="S0236_enabletimeselect">
            <label for="S0236_enabletimeselect_off">{l s='No' mod='correos'}</label>
            <a class="slide-button btn"></a>
         </span>
         <p class="help-block">{l s='Correos will charge you an addition price for this service. Take that in mind when you establish shipping costs for his carrier' mod='correos'}</p>
      </div>
      <div class="form-group">
         <label class="control-label">
         {l s='Postal Codes for time slot for "Paq 48 domicilio"' mod='correos'}
         </label>
          <input type="file" name="S0236_postalcodes" class="file optional">
           <p class="help-block">{l s='Coma separated. Do not modify unless Correos say so' mod='correos'}</p>
      </div>
                        
      
      <div class="nopadding clear clearfix">
         <hr>
         <button class="btn btn-primary pull-right has-action btn-save-general" name="form-carriers" type="submit">
         <i class="fa fa-save nohover"></i>
         {l s='Save' mod='correos'}
         </button>
      </div>

   </form>
   </div>