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
<div class="panel-heading">{l s='Search shipping' mod='correos'}</div>
   <div class="panel-body" style="width:100%">
      <form class="form clearfix" id="correos_orders" enctype="multipart/form-data" method="post">
        <table class="table order">
         <thead>
            <tr class="nodrag nodrop">
               <th class="center fixed-width-xs"></th>
               <th class=" text-center fixed-width-xs">
                  <span class="title_box"> ID </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Customer' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Date' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Shipping code' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Collection requested' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Exported' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Printed' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Manifest' mod='correos'} </span>
               </th>
               <th class="center fixed-width-xs"></th>
            </tr>
            <tr class="nodrag nodrop filter row_hover">
               <th class="text-center"> -- </th>
               <th class="text-center">
                  <input class="filter" type="text" value="{if isset($smarty.post.orderFilter_id_order) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_id_order|escape:'htmlall':'UTF-8'}{/if}" name="orderFilter_id_order">
               </th>
               <th>
                  <input class="filter" type="text" value="{if isset($smarty.post.orderFilter_customer) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_customer|escape:'htmlall':'UTF-8'}{/if}" name="orderFilter_customer">
               </th>
               <th class="text-right">
                  <div class="date_range row">
                     <div class="input-group fixed-width-md">
                        <input id="local_orderFilter_dateFrom" class="filter datepicker date-input form-control" type="text" placeholder="{l s='Date' mod='correos'}" 
                        value="{if isset($smarty.post.local_orderFilter_dateFrom) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.local_orderFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}" name="local_orderFilter_dateFrom">
                        <input type="hidden" id="orderFilter_dateFrom" name="orderFilter_dateFrom" value="{if isset($smarty.post.orderFilter_dateFrom) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}">
                        
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                     <div class="input-group fixed-width-md">
                  
                        <input id="local_orderFilter_dateTo" class="filter datepicker date-input form-control" type="text" placeholder="Hasta" 
                           value="{if isset($smarty.post.local_orderFilter_dateTo) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.local_orderFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}"                         
                           name="local_orderFilter_dateTo">
                        <input type="hidden" id="orderFilter_dateTo" value="{if isset($smarty.post.orderFilter_dateTo) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}" name="orderFilter_dateTo" value="">
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                  </div>
               </th>
               <th class="text-center"> -- </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_collected">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_collected) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_collected == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_collected) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_collected == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_exported">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_exported) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_exported == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_exported) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_exported == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_printed">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_printed) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_printed == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_printed) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_printed == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_manifest">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_manifest) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_manifest == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_manifest) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_manifest == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="actions">
                  <span class="pull-right">
                     <button id="submitFilterButtonorder" class="btn btn-default" data-list-id="order" name="form-search_shipping_filter" type="submit">
                     <i class="icon-search"></i>
                     {l s='Search' mod='correos'}
                     </button>
                     {if isset($smarty.post['form-search_shipping_filter'])}
                     <button class="btn btn-warning" name="form-search_shipping_reset" type="submit">
						<i class="icon-eraser"></i>
                        {l s='Reset' mod='correos'}
					</button>
                    {/if}
         
                  </span>
               </th>
            </tr>
         </thead>
         {if $orders}
         <tbody>
         {foreach from=$orders item=order}
            <tr>
               <td class="row-selector">
                <input type="checkbox" class="id_order" name="id_order[{$order.id_order|escape:'htmlall':'UTF-8'}]" value="{$order.shipment_code|escape:'htmlall':'UTF-8'}" data-reference="{$order.reference|escape:'htmlall':'UTF-8'}">
               </td>
               <td>{$order.id_order|escape:'htmlall':'UTF-8'}</td>
               <td>{$order.firstname|escape:'htmlall':'UTF-8'} {$order.lastname|escape:'htmlall':'UTF-8'}</td>
               <td>{dateFormat date=$order.date_add full=1} </td>
               <td>
                  {$order.shipment_code|escape:'htmlall':'UTF-8'} 
               </td>
               <td>
                {dateFormat date=$order.collection_date full=1}
               </td>
               <td>
                    {dateFormat date=$order.exported full=1}
               </td>
               <td>
                   {dateFormat date=$order.label_printed full=1}
               </td>
               <td>
                  {dateFormat date=$order.manifest full=1}
               </td>
               <td>
                  
               </td>
            </tr>
         
         {/foreach}
         </tbody>
         {/if}
         </table>
         
         
         
   {if $orders}      
   <div class="row">
      <div class="col-lg-6">
			<div class="btn-group bulk-actions dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
               {l s='Selected Option' mod='correos'} <span class="caret"></span>
            </button>
			<ul class="dropdown-menu">
				<li>
					<a onclick="javascript:$('#correos_orders input:checkbox').attr ( 'checked' , true );return false;" href="#">
						<i class="icon-check-sign"></i>&nbsp;{l s='Check all' mod='correos'}
					</a>
				</li>
				<li>
					<a onclick="javascript:$('#correos_orders input:checkbox').attr ( 'checked' , false );return false;" href="#">
						<i class="icon-check-empty"></i>&nbsp;{l s='Uncheck all' mod='correos'}
					</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('generate_label_a4');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Generate labels - A4' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('generate_label_printer');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Generate labels - Label printer ' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('generate_manifest');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Generate Manifest' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('export');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Export' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" id="request_collection">
					{l s='Request collection' mod='correos'}
				</a>
				</li>
			</ul>
		</div>
			</div>
		<div class="col-lg-6">
		
		<div class="pagination">
			{l s='Show' mod='correos'}
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{$search_shipping_pagination.order_rows|escape:'htmlall':'UTF-8'}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
							<li>
					<a href="javascript:void(0);" class="pagination-items-page" data-items="20" data-list-id="order">20</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-items-page" data-items="50" data-list-id="order">50</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-items-page" data-items="100" data-list-id="order">100</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-items-page" data-items="300" data-list-id="order">300</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-items-page" data-items="1000" data-list-id="order">1000</a>
				</li>
						</ul>
			/ {$search_shipping_pagination.total_rows|escape:'htmlall':'UTF-8'} {l s='results' mod='correos'}
			<input id="order_rows" name="order_rows" value="{$search_shipping_pagination.order_rows|escape:'htmlall':'UTF-8'}" type="hidden">
		</div>
		<script type="text/javascript">
			$('.pagination-items-page').on('click',function(e){
				e.preventDefault();
				$('#order_rows').val($(this).data("items")).closest("form").submit();
			});

		</script>
   
      <ul class="pagination pull-right">
						<li {if $search_shipping_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="1">
								<i class="icon-double-angle-left"></i>
							</a>
						</li>
						<li {if $search_shipping_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="{$search_shipping_pagination.page|escape:'htmlall':'UTF-8' - 1}">
								<i class="icon-angle-left"></i>
							</a>
						</li>
						{assign p 0}
						{while $p++ < $search_shipping_pagination.total_pages}
							{if $p < $search_shipping_pagination.page-2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $search_shipping_pagination.page-3}
							{else if $p > $search_shipping_pagination.page+2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $search_shipping_pagination.total_pages}
							{else}
								<li {if $p == $search_shipping_pagination.page}class="active"{/if}>
									<a href="javascript:void(0);" class="pagination-link" data-page="{$p|escape:'htmlall':'UTF-8'}">{$p|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/while}
						<li {if $search_shipping_pagination.page >= $search_shipping_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="{$search_shipping_pagination.page|escape:'htmlall':'UTF-8' + 1}">
								<i class="icon-angle-right"></i>
							</a>
						</li>
						<li {if $search_shipping_pagination.page >= $search_shipping_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="{$search_shipping_pagination.total_pages|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-right"></i>
							</a>
						</li>
					</ul>
            
               
               
      <input id="order-page" name="order_page" type="hidden">
		<script type="text/javascript">
			$('.pagination-link').on('click',function(e){
				e.preventDefault();

				if (!$(this).parent().hasClass('disabled'))
					$('#order-page').val($(this).data("page")).closest("form").submit();
			});
		</script>
	</div>
     
	</div>
      <input type="hidden" name="option_order" id="option_order"/>
      <button style="display:none" id="form-search_shipping_action" name="form-search_shipping_action" type="submit"></button>
    {else}
     <p class="text-center">{l s='No results found' mod='correos'}</p>
   {/if}  
      <div class="row col-lg-3">
      <p class="pull-left" style="margin: 5px 5px 0 0 ">{l s='Choose print position (only A4 Format)' mod='correos'}</p>
      <select name="print_position" class="fixed-width-xs">
         <option>1</option>
         <option>2</option>
         <option>3</option>
         <option>4</option>
      </select>
      <br style="clear:left"> 
       <img  src="{$img_dir|escape:'htmlall':'UTF-8'}admin/print_position.gif"/>
      </div>
      
        <!-- Modal -->
  <div class="modal fade"  id="CorreosModalCollection" role="dialog">
    <div class="modal-dialog">
    
<!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='COLLECTION ADDRESS. Modify only if not match with the Sender address' mod='correos'}</h4>
        </div>
        <div class="modal-body">
        
         <div class="nopadding clear clearfix ">
         <div class="form-horizontal">
            <div class="form-group">
             <div>
               {if isset($sender_form.options.select_sender.data)}
               <select name="collection_sender" id="collection_sender" class="form-control" autocomplete="off">
                  {foreach from=$sender_form.options.select_sender.data key="key" item="item"}
                     <option {if $sender_form.options.select_sender.default_option eq $key}selected="true"{/if} value="{$key|escape:'htmlall':'UTF-8'}">{$item|escape:'htmlall':'UTF-8'}</option>
                  {/foreach}
               </select>
               {/if}
            </div>
            <p class="help-block">{l s='Select Recipient' mod='correos'}</p>
         </div>
         
            <div class="form-group">
               <div class="col-xs-4 text-right">
               <label for="collection_req_name" class="control-label required">
                   {l s='Name and surname' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_nombre.value|escape:'htmlall':'UTF-8'} {$sender_form.options.sender_apellidos.value|escape:'htmlall':'UTF-8'}" id="collection_req_name" name="collection_req_name" class="form-control required" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
               <label for="collection_req_address" class="control-label required">
                   {l s='Address' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_direccion.value|escape:'htmlall':'UTF-8'}" id="collection_req_address" name="collection_req_address" class="form-control required" autocomplete="off">
               </div>
            </div>
     
     
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_req_city" class="control-label required">
                     {l s='City' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_localidad.value|escape:'htmlall':'UTF-8'}" id="collection_req_city" name="collection_req_city" class="form-control required" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_req_postalcode" class="control-label required">
                  {l s='Postal Code' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_cp.value|escape:'htmlall':'UTF-8'}" id="collection_req_postalcode" name="collection_req_postalcode" class="form-control required" autocomplete="off">
               </div>   
            </div>

            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label for="collection_req_mobile_phone" class="control-label required">
                     {l s='Mobile phone' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_movil.value|escape:'htmlall':'UTF-8'}" id="collection_req_mobile_phone" name="collection_req_mobile_phone" class="form-control required" autocomplete="off">
               </div>
            </div>

            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                     {l s='E-mail' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_email.value|escape:'htmlall':'UTF-8'}" id="collection_req_email" name="collection_req_email" class="form-control required" autocomplete="off">
               </div>
            </div> 
          <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                     {l s='Collection reference' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" name="collection_req_reference" id="collection_reference" class="form-control required"  maxlength="100" autocomplete="off">
               </div>
            </div> 
          
         </div>
         <div class="form-group pull-left col-xs-4">
            <label class="control-label">
               {l s='Time of collection' mod='correos'}  
            </label>
            <select name="collection_req_time" class="form-control" autocomplete="off">
                     <option value="morning">{l s='Morning' mod='correos'}</option>
                     <option value="afternoon">{l s='Afternoon' mod='correos'}</option>
            </select>
         </div> 
         <div class="form-group pull-left col-xs-4">
            <label class="control-label required">
               {l s='Date of collection' mod='correos'}
            </label>
            <input type="text" value="" id="collection_date" name="collection_req_date" class="datepicker date-input form-control required" autocomplete="off">
            
         </div> 
         <div class="form-group pull-left col-xs-4">
            <label class="control-label required">
               {l s='Number of pieces' mod='correos'}  
            </label>
            <input type="text" id="collection_pieces" name="collection_req_pieces" class="form-control required" autocomplete="off">
         </div>

         <div class="form-group pull-left col-xs-6">
         
                <label class="control-label required">
                   {l s='Size' mod='correos'} 
                </label>
          
           
                <select name="collection_req_size" id="collection_size" class="form-control required" autocomplete="off">
                      <option value=""></option>
                     <option value="10">{l s='Envelopes' mod='correos'}</option>
                     <option value="20">{l s='Small (box shoes)' mod='correos'}</option>
                     <option value="30">{l s='Medium (box with packs folios)' mod='correos'}</option>
                     <option value="40">{l s='Large (box 80x80x80 cm)' mod='correos'}</option>
                     <option value="50">{l s='Very large (larger than box 80x80x80 cm)' mod='correos'}</option>
                     <option value="60">Palet</option>
               </select>
                <p class="help-block">{l s='If there are several sizes, it indicates the highest' mod='correos'}</p>
  
         </div>
         <div class="form-group pull-left col-xs-6">
         
                <label class="control-label">
                {l s='Label printing' mod='correos'} 
                </label>
            
            
                 <select name="collection_req_label_print" id="label_print" class="form-control" autocomplete="off">
                    <option  value="N"></option>
                    <option  value="S">{l s='Yes' mod='correos'} ({l s='Max. 5 labels' mod='correos'})</option>
                    <option  value="N">{l s='No' mod='correos'}</option>
                  </select>
                  <p class="help-block">{l s='Do you need Correos to print the labels?' mod='correos'}</p>
         </div>
         <div class="form-group">
            <label class="control-label">
               {l s='Comments' mod='correos'}
            </label>
            <textarea class="form-control" name="collection_req_comments" class="form-control" rows="5"></textarea>
         </div>
         <hr>
         <button class="btn btn-primary pull-right has-action btn-save-general" id="btn-request_collection" name="form-request_collection" type="submit">
            <i class="fa fa-save nohover"></i>
            {l s='Request collection' mod='correos'}
        </button>
      </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
       </form>
      
   </div>
   
   

  
 