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
<div class="row" id="correos-admin-forms">
{if $CORREOS_CONFIG.show_config eq 0}

   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/first_run_intro.tpl' 
      correos_config=$CORREOS_CONFIG
      img_dir=$paramsBack.CORREOS_IMG
      }

{/if}
   
{if $CORREOS_CONFIG.show_config eq 1}
<div class="tabbable  tabs-left">
   <div class="col-md-4 col-lg-3">
      <div class="panel" style="padding: 5px;">
     <ul class="nav nav-tabs" data-tabs="tabs">
   {foreach from=$paramsBack.HELPER_FORM.tabs item='tab' name='tabs'}
      <li class="">
         <span class="{if isset($tab.sub_tab)}has-sub{/if}">
            <img class="icon" src="{$paramsBack.CORREOS_IMG|escape:'htmlall':'UTF-8'}admin/{$tab.icon|escape:'htmlall':'UTF-8'}"/>&nbsp;{$tab.label|escape:'htmlall':'UTF-8'}
         </span>
      </li>                             
      {if isset($tab.sub_tab)}
      
         {foreach from=$tab.sub_tab item='sub_tab' name='sub_tabs'}
            <li class="{if (isset($CURRENT_FORM) && $CURRENT_FORM eq $sub_tab.href) || (not isset($CURRENT_FORM) && $smarty.foreach.sub_tabs.first && $smarty.foreach.tabs.first)}active{/if}">
               <a href="#tab-{$sub_tab.href|escape:'htmlall':'UTF-8'}" data-toggle="tab">
                   <img class="icon" src="{$paramsBack.CORREOS_IMG|escape:'htmlall':'UTF-8'}admin/{$sub_tab.icon|escape:'htmlall':'UTF-8'}"/>&nbsp;{$sub_tab.label|escape:'htmlall':'UTF-8'}
               </a>
            </li>
         {/foreach}
                                        
      {/if}
    {/foreach}   
     </ul>
  </div>
 </div> 
  
  
 <div class="col-xs-9"> 
   <div class="tab-content">
   {if isset($paramsBack.HELPER_FORM)}
      {if isset($paramsBack.HELPER_FORM.forms) and is_array($paramsBack.HELPER_FORM.forms) and count($paramsBack.HELPER_FORM.forms)}
         {foreach from=$paramsBack.HELPER_FORM.forms key='key' item='form' name='forms'}
            {if isset($form.modal) and $form.modal}{assign var='modal' value=1}{else}{assign var='modal' value=0}{/if}
            <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq $form.tab) || (not isset($CURRENT_FORM) && $smarty.foreach.forms.first)}active in{/if}" id="tab-{$form.tab|escape:'htmlall':'UTF-8'}"> 
               <div class="panel-heading">{$form.title|escape:'htmlall':'UTF-8'}</div>
               <div class="panel-body">
               <form method="post" class="form clearfix {if isset($form.class)}{$form.class|escape:'htmlall':'UTF-8'}{/if}" {if isset($form.id)}id="{$form.id|escape:'htmlall':'UTF-8'}"{/if} {if isset($form.enctype)}enctype="{$form.enctype|escape:'htmlall':'UTF-8'}"{/if}>
               
            
               {foreach from=$form.options item='option'}
                       
                        <div class="form-group">
                                 {if isset($option.label)}
                                  
                                          <label class="control-label">
                                                {$option.label|escape:'quotes':'UTF-8'}
                                          </label>
                             
                                {/if}
                                 {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/form.tpl' option=$option}
                       
                          {if isset($option.help)}
                           <p class="help-block">{$option.help|escape:'htmlall':'UTF-8'}</p>
                          {/if}      
                        </div>
          
               {/foreach}
               <div class="form-errors"></div>
              <div class="nopadding clear clearfix">
              <hr />
                                                        
{if isset($form.actions) and is_array($form.actions) and count($form.actions)}
    {foreach from=$form.actions item='action'}
        <button type="{if isset($form.method) and $form.method eq 'post'}submit{else}button{/if}"
                {if isset($action.name)}
                    name="{$action.name|escape:'htmlall':'UTF-8'}" id="btn-{$action.name|escape:'htmlall':'UTF-8'}"
                {else}
                    name="form-{$key|escape:'htmlall':'UTF-8'}"
                {/if}
                class="btn btn-primary pull-right has-action {if isset($action.class)}btn-{$action.class|escape:'htmlall':'UTF-8'}{/if}">
            <i class="fa fa-save nohover"></i>
            {$action.label|escape:'htmlall':'UTF-8'}
        </button>
    {/foreach}
{/if}

               </div>
                                                  
             </form>      
              </div>
            </div>
         
         {/foreach}   
      {/if}
   {/if} 
   
   <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'carriers')}active in{/if}" id="tab-carriers"> 
   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/carriers.tpl' 
      correos_carriers=$paramsBack.CORREOS_CARRIERS
      carriers=$paramsBack.CARRIERS
      path_module=$paramsBack.CORREOS_TPL 
      path_logo=$paramsBack.CORREOS_IMG
      logo_prefix=$paramsBack.LOGO_PREFIX
      correos_config=$CORREOS_CONFIG
      
      }
   </div>
   <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'customs')}active in{/if}" id="tab-customs"> 
   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/customs.tpl' 
      correos_config=$CORREOS_CONFIG
      zones=$paramsBack.ZONES
      customs_categories=$paramsBack.CUSTOMS_CAT
      }
   </div>
   
   <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'search_shipping')}active in{/if}" id="tab-search_shipping"> 
   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/search_shipping.tpl' 
      correos_config=$CORREOS_CONFIG
      orders=$paramsBack.ORDERS
      pagination=$search_shipping_pagination
      img_dir=$paramsBack.CORREOS_IMG
      sender_form=$paramsBack.HELPER_FORM.forms.sender
      }
   </div>
   
   <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'query_collections')}active in{/if}" id="tab-query_collections"> 
   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/query_collections.tpl' 
      collections=$paramsBack.COLLECTIONS
      path_module=$paramsBack.CORREOS_TPL
      }
   </div>
   
   <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'request_rma')}active in{/if} form-horizontal" id="tab-request_rma"> 
   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/request_rma.tpl' 
     img_dir=$paramsBack.CORREOS_IMG
     tpl_dir=$paramsBack.CORREOS_TPL
     sender_form=$paramsBack.HELPER_FORM.forms.sender
      }
   </div>


   <div class="tab-pane fade panel {if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'order_state')}active in{/if}" id="tab-order_state"> 
   {include file=$paramsBack.CORREOS_TPL|cat:'views/templates/admin/helper/order_state.tpl' 
      correos_config=$CORREOS_CONFIG
      states=$paramsBack.ORDER_STATES
      }
   </div>
   
</div> <!-- End tab content-->
</div> 
{/if} {* $CORREOS_CONFIG.first_run eq 0 *}

  <!-- Modal -->
  <div class="modal fade"  id="CorreosModal" role="dialog">
    <div class="modal-dialog">
    
<!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body" id="CorreosModalContent">
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>
</div>
