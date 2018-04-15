{*
* 2002-2016 TemplateMonster
*
* TM Collections
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="mycollections">

  {capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}">{l s='My account' mod='tmcollections'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    {l s='My collections' mod='tmcollections'}
  {/capture}

  <h1 class="page-heading">{l s='My collections' mod='tmcollections'}</h1>
  {include file="$tpl_dir./errors.tpl"}
  {if isset($confirmation_add)}
    <p class="alert alert-success">{l s='Add new collection - "%s"' sprintf=[$confirmation_name] mod='tmcollections'}</p>
  {/if}
  {if isset($confirmation_change)}
    <p class="alert alert-success">{l s='This name is change' mod='tmcollections'}</p>
  {/if}
  {if $id_customer|intval neq 0}
    <form method="post" class="std box" id="form_collection">
      <fieldset>
        <h3 class="page-subheading">{l s='New collection' mod='tmcollections'}</h3>
        <div class="form-group">
          <label class="align_right" for="name">{l s='Name' mod='tmcollections'}</label>
          <input autocomplete="off" type="text" id="name_collection" name="name" class="inputTxt form-control" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'html':'UTF-8'}{/if}" />
          <input type="hidden" name="token" value="{$token|escape:'html':'UTF-8'}" />
        </div>
        <p class="submit">
          <button id="submitCollections" class="btn btn-default btn-md button button-medium" name="submitCollections" type="submit">
            <span>
              {l s='Save' mod='tmcollections'}
            </span>
          </button>
          <input id="id_collection" type="hidden" name="id_collection" value="" />
        </p>
      </fieldset>
    </form>
  {if $collections}
    <ul class="all-collection">
      {foreach from=$collections item=collection name=collection}
        <li data-collection-id="{$collection.id_collection|intval}" id="collection_{$collection.id_collection|intval}" data-collection-name="{$collection.name|truncate:22:'...'|escape:'html':'UTF-8'}">
          <h3>
            <span>{$collection.name|truncate:50:'...'|escape:'html':'UTF-8'}</span>
            <a class="delete-collection" href="#" onclick="javascript:event.preventDefault();return (CollectionDelete('collection_{$collection.id_collection|intval}', '{$collection.id_collection|intval}', '{l s='Do you really want to delete this collection ?' mod='tmcollections' js=1}'));">
              <i class="icon-remove fa fa-times"></i>
            </a>
            <a class="edit-collection" href="#" onclick="CollectionEdit('{$collection.id_collection|intval}');">
              <i class="icon-edit fa fa-pencil-square-o"></i>
            </a>
          </h3>
          <div class="collection-products-container">
            {assign var='products' value=ClassTmCollections::getProductByIdCollection($collection.id_collection)}
            {if $products}
              <ul class="row">
                {foreach from=$products item=product name=product}
                  {if $product.id_collection == $collection.id_collection}
                    <li class="clp_{$product.id_product|escape:'htmlall':'UTF-8'}_{$product.id_product_attribute|escape:'htmlall':'UTF-8'} col-xs-12 col-sm-4 col-md-3">
                      <div class="product_image">
                        <img class="replace-2x img-responsive"  src="{$link->getImageLink($product.link_rewrite, $product.cover, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:'html':'UTF-8'}"/>
                        <a class="lnkdel" href="javascript:;" onclick="DeleteProduct('{$collection.id_collection|intval}', '{$product.id_product|intval}', '{$product.id_product_attribute|intval}')" title="{l s='Delete' mod='tmcollections'}">
                          <i class="icon-remove-sign fa fa-times"></i>
                        </a>
                      </div>
                      <h5>
                        <a class="product-name" href="{$link->getProductlink($product.id_product, $product.link_rewrite)|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
                          <span class="quantity-formated"><span class="quantity">{$product.quantity|intval}</span> x </span>{$product.name|truncate:25:'...'|escape:'html':'UTF-8'}
                        </a>
                      </h5>
                      {if (!$PS_CATALOG_MODE)}
                        <div class="content_price">
                          <span>
                            {convertPrice price=$product.price}
                          </span>
                        </div>
                      {/if}
                    </li>
                  {/if}
                {/foreach}
              </ul>
            {else}
            <p class="alert alert-warning">{l s='No products in this collection.' mod='tmcollections'}</p>
            {/if}
          </div>
          <div class="clearfix collection-row-bottom">
            <a target="_blank" href="{$link->getModuleLink('tmcollections', 'collection', ['token' => $collection.token])|escape:'htmlall':'UTF-8'}" class="btn btn-view-collection btn-default btn-sm button">
              {l s='View collection' mod='tmcollections'}
            </a>
            {if $products}
              <button type="button" id="add-new-layout" class="btn"><span>{l s='Share' mod='tmcollections'}</span></button>
            {/if}
            <a class="btn-product-collection" href="#">
              {l s='View products' mod='tmcollections'}
            </a>
            <script type="text/javascript">
              window.fbAsyncInit = function() {
                FB.init({
                  appId      : "{$tm_collection_app_id|escape:'html':'UTF-8'}",
                  xfbml      : true,
                  version    : 'v2.6'
                });
              };

              (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                  return;
                }
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));

              $(document).on('click', '#share_button_{$collection.id_collection|escape:'htmlall':'UTF-8'}', function(e) {
                var id_collection = $(this).parent().parent().find('input[name="id_collection"]').attr('value'),
                    name_collection = $(this).parent().parent().find('input[name="name_collection"]').attr('value'),
                    id_layout = $(this).parent().find('input[name="id_layout"]').attr('value'),
                    id_product = $(this).parent().find('input[name="selected_products"]').attr('value');

                $.ajax({
                  type:'POST',
                  url: mycollections_url,
                  headers: {literal}{"cache-control": "no-cache"}{/literal},
                  dataType: 'json',
                  async:false,
                  data: {
                    myajax: 1,
                    id_layout: id_layout,
                    id_collection: id_collection,
                    name_collection: name_collection,
                    id_product: id_product,
                    action: 'getImageById',
                  },
                  success: function(msg){
                    result = msg.status;
                  }
                });

                var obj = {
                  method: 'share',
                  title: "{$collection.name|truncate:30:'...'|escape:'html':'UTF-8'}",
                  href: "{$link->getModuleLink('tmcollections', 'collection', ['token' => $collection.token])|escape:'htmlall':'UTF-8'}",
                  picture: "{$img_path|escape:'htmlall':'UTF-8'}{$collection.id_collection|truncate:30:'...'|escape:'html':'UTF-8'}-collection.jpg?v={sha1(md5(time()))|escape:'htmlall':'UTF-8'}",
                };

                function callback() {
                  location.reload();
                }

                FB.ui(obj, callback);
                e.stopPropagation();
                $.fancybox.close();
              });
            </script>
          </div>
        </li>
      {/foreach}
    </ul>
  {/if}
    <div id="block-order-detail">&nbsp;</div>
  {/if}
  <ul class="footer_links clearfix">
    <li>
      <a class="btn btn-default btn-md button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        <span>
          <i class="icon-chevron-left"></i>
          {l s='Back to Your Account' mod='tmcollections'}
        </span>
      </a>
    </li>
    <li>
      <a class="btn btn-default btn-md button button-small" href="{$base_dir|escape:'html':'UTF-8'}">
        <span>
          <i class="icon-chevron-left"></i>
          {l s='Home' mod='tmcollections'}
        </span>
      </a>
    </li>
  </ul>
</div>