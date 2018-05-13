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

<div class="panel-heading">{l s='Query collections' mod='correos'}</div>
   <div class="panel-body">
   
   <form class="form clearfix" id="correos_orders" enctype="multipart/form-data" method="post">
        <table class="table order">
         <thead>
            <tr class="nodrag nodrop">
               <th class="text-center">
                  <span class="title_box"> {l s='Confirmation code' mod='correos'} </span>
               </th>
               <th class="text-center">
                  <span class="title_box"> {l s='Reference code' mod='correos'} </span>
               </th>
               <th class="text-center fixed-width-xl">
                  <span class="title_box"> {l s='Date Requested' mod='correos'} </span>
               </th>
               <th class="text-center fixed-width-xl">
                  <span class="title_box"> {l s='Collection Date' mod='correos'} </span>
               </th>
               <th class="center fixed-width-xl"></th>
            </tr>
            <tr class="nodrag nodrop filter row_hover">
               <th></th>
               <th></th>
               <th class="text-right">
                  <div class="date_range row">
                      <div class="col-md-12">
                       <div class="input-group">
                          <input id="local_collectionFilter_dateFrom" class="filter datepicker date-input form-control" type="text" placeholder="{l s='From' mod='correos'}" 
                          value="{if isset($smarty.post.local_collectionFilter_dateFrom) && isset($smarty.post['form-search_collection_filter'])} {$smarty.post.local_collectionFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}" name="local_collectionFilter_dateFrom">
                          <input type="hidden" id="collectionFilter_dateFrom" name="collectionFilter_dateFrom" value="{if isset($smarty.post.collectionFilter_dateFrom) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.collectionFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}">
                          
                          <span class="input-group-addon">
                             <i class="icon-calendar"></i>
                          </span>
                       </div>
                     </div>
                     <div class="col-md-12">
                     <div class="input-group">
                  
                        <input id="local_collectionFilter_dateTo" class="filter datepicker date-input form-control" type="text" placeholder="{l s='To' mod='correos'}" 
                           value="{if isset($smarty.post.local_collectionFilter_dateTo) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.local_collectionFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}"                         
                           name="local_collectionFilter_dateTo">
                        <input type="hidden" id="collectionFilter_dateTo" value="{if isset($smarty.post.collectionFilter_dateTo) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.collectionFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}" name="collectionFilter_dateTo" value="">
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                     </div>
                  </div>
               </th>
               <th class="text-right">
                  <div class="date_range row">
                      <div class="col-md-12">
                       <div class="input-group">
                          <input id="local_collectionDateFilter_dateFrom" class="filter datepicker date-input form-control" type="text" placeholder="{l s='From' mod='correos'}" 
                          value="{if isset($smarty.post.local_collectionDateFilter_dateFrom) && isset($smarty.post['form-search_collectionDate_filter'])} {$smarty.post.local_collectionDateFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}" name="local_collectionDateFilter_dateFrom">
                          <input type="hidden" id="collectionDateFilter_dateFrom" name="collectionDateFilter_dateFrom" value="{if isset($smarty.post.collectionDateFilter_dateFrom) && isset($smarty.post['form-search_collectionDate_filter'])}{$smarty.post.collectionDateFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}">
                          
                          <span class="input-group-addon">
                             <i class="icon-calendar"></i>
                          </span>
                       </div>
                     </div>
                     <div class="col-md-12">
                     <div class="input-group">
                  
                        <input id="local_collectionDateFilter_dateTo" class="filter datepicker date-input form-control" type="text" placeholder="{l s='To' mod='correos'}" 
                           value="{if isset($smarty.post.local_collectionDateFilter_dateTo) && isset($smarty.post['form-search_collectionDate_filter'])}{$smarty.post.local_collectionDateFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}"                         
                           name="local_collectionDateFilter_dateTo">
                        <input type="hidden" id="collectionDateFilter_dateTo" value="{if isset($smarty.post.collectionDateFilter_dateTo) && isset($smarty.post['form-search_collectionDate_filter'])}{$smarty.post.collectionDateFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}" name="collectionDateFilter_dateTo" value="">
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                     </div>
                  </div>
               </th>
               <th class="actions">
                  <span class="pull-right">
                     <button id="submitFilterButtoncollection" class="btn btn-default" name="form-search_collection_filter" type="submit">
                     <i class="icon-search"></i>
                     {l s='Search' mod='correos'}
                     </button>
                     {if isset($smarty.post['form-search_collection_filter'])}
                     <button class="btn btn-warning" name="form-search_collection_reset" type="submit">
						<i class="icon-eraser"></i>
                        {l s='Reset' mod='correos'}
					</button>
                    {/if}
         
                  </span>
               </th>
            </tr>
         </thead>
         <tbody>
         {foreach from=$collections item=colelction}
         <tr>
            <td class="text-center">{$colelction.confirmation_code|escape:'htmlall':'UTF-8'}</td>
            <td class="text-center">{$colelction.reference_code|escape:'htmlall':'UTF-8'}</td>
            <td class="text-center">{dateFormat date=$colelction.date_requested full=1}</td>
            <td class="text-center">{dateFormat date=$colelction.collection_date}</td>
            <td class="text-center">
              <button class="btn btn-success view-collection-details" type="button"
                data-id_collection="{$colelction.id|intval}" 
                data-confirmation_code="{$colelction.confirmation_code|escape:'htmlall':'UTF-8'}"
                data-reference_code="{$colelction.reference_code|escape:'htmlall':'UTF-8'}"
                data-date_requested="{dateFormat date=$colelction.date_requested full=1}">
                  {l s='View details' mod='correos'}
             </button>
            </td>
         </tr>
         {/foreach}
         </tbody>
         </table>
         
        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-6">
          <div class="pagination">
			{l s='Show' mod='correos'}
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{$collection_pagination.collection_rows|escape:'htmlall':'UTF-8'}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="20">20</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="50">50</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="100">100</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="300">300</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="1000">1000</a>
				</li>
						</ul>
			/ {$collection_pagination.total_rows|escape:'htmlall':'UTF-8'} {l s='results' mod='correos'}
			<input id="collection_rows" name="collection_rows" value="{$collection_pagination.collection_rows|escape:'htmlall':'UTF-8'}" type="hidden">
		</div>
		<script type="text/javascript">
			$('.pagination-collection-items-page').on('click',function(e){
				e.preventDefault();
				$('#collection_rows').val($(this).data("items")).closest("form").submit();
			});

		</script>
   
      <ul class="pagination pull-right">
						<li {if $collection_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="1">
								<i class="icon-double-angle-left"></i>
							</a>
						</li>
						<li {if $collection_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$collection_pagination.page|escape:'htmlall':'UTF-8' - 1}">
								<i class="icon-angle-left"></i>
							</a>
						</li>
						{assign p 0}
						{while $p++ < $collection_pagination.total_pages}
							{if $p < $collection_pagination.page-2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $collection_pagination.page-3}
							{else if $p > $collection_pagination.page+2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $collection_pagination.total_pages}
							{else}
								<li {if $p == $collection_pagination.page}class="active"{/if}>
									<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$p|escape:'htmlall':'UTF-8'}">{$p|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/while}
						<li {if $collection_pagination.page >= $collection_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$collection_pagination.page|escape:'htmlall':'UTF-8' + 1}">
								<i class="icon-angle-right"></i>
							</a>
						</li>
						<li {if $collection_pagination.page >= $collection_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$collection_pagination.total_pages|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-right"></i>
							</a>
						</li>
					</ul>
            
               
               
      <input id="collection-page" name="collection_page" type="hidden">
		<script type="text/javascript">
			$('.collection-pagination-link').on('click',function(e){
				e.preventDefault();

				if (!$(this).parent().hasClass('disabled'))
					$('#collection-page').val($(this).data("page")).closest("form").submit();
			});
		</script>
    
          </div>
        </div>
         
    </form>
   </div>
   

<div class="modal fade" id="collectionDetails" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{l s='Collection Details' mod='correos'}</h4>
            </div>
            <div class="modal-body">


              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='Confirmation code' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-confirmation-code"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='Reference code' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-reference"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='Date requested' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-date-requested"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='Date of collection' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-date"></div>
              </div>
              
              <div class="row">
                 <div class="col-xs-4 text-right">
                      {l s='Time of collection' mod='correos'}  
                 </div>
                 <div class="col-xs-8" id="collection-detail-time"></div>
              </div>
              

              <hr>
              <div class="row">
                 <div class="col-xs-4 text-right">
                     {l s='Name and surname' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-name"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                     {l s='Address' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-address"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='City' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-city"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                    {l s='Postal Code' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-postalcode"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='Mobile phone' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-phone"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                       {l s='E-mail' mod='correos'}
                 </div>
                 <div class="col-xs-8" id="collection-detail-email"></div>
              </div>

              <hr/>

              <div class="row">
                 <div class="col-xs-4 text-right">
                      {l s='Number of pieces' mod='correos'}  
                 </div>
                 <div class="col-xs-8" id="collection-detail-pieces"></div>
              </div>


              <div class="row">
                 <div class="col-xs-4 text-right">
                      {l s='Size' mod='correos'} 
                 </div>
                 <div class="col-xs-8" id="collection-detail-size"></div>
              </div>
              

              <div class="row">
                 <div class="col-xs-4 text-right">
                      {l s='Label printing' mod='correos'} 
                 </div>
                 <div class="col-xs-8" id="collection-detail-label-print"></div>
              </div>

              <div class="row">
                 <div class="col-xs-4 text-right">
                      {l s='Comments' mod='correos'} 
                 </div>
                 <div class="col-xs-8" id="collection-detail-comments"></div>
              </div>
            
            <hr/>
            <div class="row">
                 <div class="col-xs-4 text-right">
                      {l s='Orders IDs for collection' mod='correos'} 
                 </div>
                 <div class="col-xs-8" id="collection-detail-orders"></div>
            </div>
         

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Cerrar' mod='correos'}</button>
            </div>
        </div>
    </div>
</div>
