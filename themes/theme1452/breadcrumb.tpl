<!-- Breadcrumb -->
{if isset($smarty.capture.path)}
  {assign var='path' value=$smarty.capture.path}
{/if}

<div class="breadcrumb clearfix">
  <ul class="container">
    <li class="home">
      <a class="home" href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{l s='Return to Home'}">
        <i class="fl-outicons-house204"></i>
      </a>
    </li>
    {if isset($path) && $path}
      {if $path|strpos:'<span' !== false}
        {assign var="trim_value" value="<span class=\"navigation-pipe\">$navigationPipe</span>"}
        {assign var="trimed_path" value=$path|replace:$trim_value:''}
        {assign var="crumbs" value='</a>'|explode:$trimed_path}
        {foreach from=$crumbs key=k item=crumb}
          <li class="crumb-{$k+1}{if $k+1 == count($crumbs)} last{/if}">
            {strip}{$crumb}{if $crumb|strpos:'<a' !== false}</a>{/if}{/strip}
          </li>
        {/foreach}
      {else}
        <li class="last">{strip}{$path}{/strip}</li>
      {/if}
    {/if}
  </ul>
</div>

{if isset($smarty.get.search_query) && isset($smarty.get.results) && $smarty.get.results > 1 && isset($smarty.server.HTTP_REFERER)}
  <div class="pull-right">
    <strong>
      {capture}{if isset($smarty.get.HTTP_REFERER) && $smarty.get.HTTP_REFERER}{$smarty.get.HTTP_REFERER}{elseif isset($smarty.server.HTTP_REFERER) && $smarty.server.HTTP_REFERER}{$smarty.server.HTTP_REFERER}{/if}{/capture}
      <a href="{$smarty.capture.default|escape:'html':'UTF-8'|secureReferrer|regex_replace:'/[\?|&]content_only=1/':''}" name="back">
        <i class="fa fa-chevron-left left"></i> 
        {l s='Back to Search results for "%s" (%d other results)' sprintf=[$smarty.get.search_query,$smarty.get.results]}
      </a>
    </strong>
  </div>
{/if}
<!-- /Breadcrumb -->