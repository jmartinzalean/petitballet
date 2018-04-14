<!DOCTYPE HTML><!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" {if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}" {/if}><![endif]--><!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8 ie7" {if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}" {/if}><![endif]--><!--[if IE 8]>
<html class="no-js lt-ie9 ie8" {if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}" {/if}><![endif]--><!--[if gt IE 8]>
<html class="no-js ie9" {if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}" {/if}><![endif]-->
<html{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}>
<head>
  <meta charset="utf-8"/>
  <title>{$meta_title|escape:'html':'UTF-8'}</title>
  {if isset($meta_description) && $meta_description}
    <meta name="description" content="{$meta_description|escape:'html':'UTF-8'}"/>
  {/if}
  {if isset($meta_keywords) && $meta_keywords}
    <meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}"/>
  {/if}
  <meta name="theme-color" content="#121212">
  <meta name="generator" content="PrestaShop"/>
  <meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow"/>
  <meta name="viewport" content="width=device-width, minimum-scale=0.25, initial-scale=1.0"/>
  <meta name="apple-mobile-web-app-capable" content="yes"/>
  <link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}"/>
  <link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}"/>
  <link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i%7COpen+Sans:300,400,600,900&amp;subset=latin,latin-ext,cyrillic-ext" type="text/css" media="all"/>
  {if isset($css_files)}
    {foreach from=$css_files key=css_uri item=media}
      {if $css_uri == 'lteIE9'}
        <!--[if lte IE 9]>{foreach from=$css_files[$css_uri] key=css_uriie9 item=mediaie9}
        <link rel="stylesheet" href="{$css_uriie9|escape:'html':'UTF-8'}" type="text/css" media="{$mediaie9|escape:'html':'UTF-8'}"/>{/foreach}<![endif]-->
      {else}
        <link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}"/>
      {/if}
    {/foreach}
  {/if}
  {if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
    {$js_def}
    {foreach from=$js_files item=js_uri}
      <script src="{$js_uri|escape:'html':'UTF-8'}"></script>
    {/foreach}
  {/if}
  {$HOOK_HEADER}
    <link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
  {if (($hide_left_column || $hide_right_column) && ($hide_left_column !='true' || $hide_right_column !='true')) && !$content_only}
    {assign var="columns" value="2"}
  {elseif (($hide_left_column && $hide_right_column) && ($hide_left_column =='true' && $hide_right_column =='true')) && !$content_only}
    {assign var="columns" value="1"}
  {elseif $content_only}
    {assign var="columns" value="1"}
  {else}
    {assign var="columns" value="3"}
  {/if}
</head>
<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{else} show-left-column{/if}{if $hide_right_column} hide-right-column{else} show-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso} {if !$content_only}{if $columns == 2} two-columns{elseif $columns == 3} three-columns{else} one-column{/if}{/if}">
{if !isset($content_only) || !$content_only}
<!--[if IE]>
<div class="old-ie">
  <a href="http://windows.microsoft.com/en-US/internet-explorer/..">
    <img src="{$img_dir}ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
  </a>
</div>
<![endif]-->
{if isset($restricted_country_mode) && $restricted_country_mode}
  <div id="restricted-country">
    <p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country}
        <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
  </div>
{/if}
{if $page_name !='pagenotfound'}
  <div class="main-loader-overlay">
    <script>
      $.fancybox.showLoading();
    </script>
  </div>
{/if}
<div id="page">
  {if $page_name !='pagenotfound'}
    <div class="header-container">
      <header id="header" class="header-container">
        {capture name='displayBanner'}{hook h='displayBanner'}{/capture}
        {if $smarty.capture.displayBanner}
          <div class="banner">
            <div class="container">
              <div class="row">
                {$smarty.capture.displayBanner}
              </div>
            </div>
          </div>
        {/if}
        {assign var='displayMegaHeader' value={hook h='tmMegaLayoutHeader'}}
        {if isset($HOOK_TOP) || $displayMegaHeader}
          {if $displayMegaHeader}
            {$displayMegaHeader}
          {else}
            {capture name='displayNav'}{hook h='displayNav'}{/capture}
            {if $smarty.capture.displayNav}
              <div class="nav">
                <div class="container">
                  <div class="row">
                    <nav>{$smarty.capture.displayNav}</nav>
                  </div>
                </div>
              </div>
            {/if}
            <div>
              <div class="container">
                <div class="row">
                  <div id="header_logo">
                    <a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
                      <img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
                    </a>
                  </div>
                  {if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
                </div>
              </div>
            </div>
          {/if}
        {/if}
      </header>
    </div>
  {/if}
  <div class="columns-container">
    <div id="top-column" class="top-column">
      {assign var='displayMegaTopColumn' value={hook h='tmMegaLayoutTopColumn'}}
      {if $displayMegaTopColumn}
        {hook h='tmMegaLayoutTopColumn'}
      {else}
        {capture name='displayTopColumn'}{hook h='displayTopColumn'}{/capture}
        {if $smarty.capture.displayTopColumn}
          {$smarty.capture.displayTopColumn}
        {/if}
      {/if}
    </div>
    {if $page_name !='index' && $page_name !='pagenotfound'}
      {include file="$tpl_dir./breadcrumb.tpl"}
    {/if}
    {if $page_name =='category' && isset($category)}
      {include file="$tpl_dir./category-description.tpl"}
    {/if}
    <div id="columns" {if $page_name !='index' || $columns > 1}class="container"{/if}>
      <div class="row">
        <div class="large-left col-sm-{12 - $right_column_size}">
          <div class="row">
            <div id="center_column" class="center_column col-xs-12 col-sm-{12 - $left_column_size}">
{/if}
