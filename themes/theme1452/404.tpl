<div class="pagenotfound row">
  <div class="col-lg-6 content">
    <svg viewBox="0 0 700 300">
      <!-- Symbol -->
      <symbol id="s-text">
        <text text-anchor="middle" x="50%" y="50%" dy=".35em">
          404
        </text>
      </symbol>
      <!-- Duplicate symbols -->
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#s-text" class="text"></use>
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#s-text" class="text"></use>
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#s-text" class="text"></use>
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#s-text" class="text"></use>
      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#s-text" class="text"></use>
    </svg>
  </div>
  <div class="col-lg-6">
    <h1>{l s='This page is not available'}</h1>
    <p>
      {l s='We\'re sorry, but the Web address you\'ve entered is no longer available.'}
    </p>
    <h3>{l s='To find a product, please type its name in the field below.'}</h3>
    <form action="{$link->getPageLink('search')|escape:'html':'UTF-8'}" method="post" class="std">
      <fieldset>
        <div>
          <input id="search_query" placeholder="{l s='Search our product catalog:'}" name="search_query" type="text" class="form-control grey"/>
          <button type="submit" name="Submit" value="OK" class="btn btn-default btn-sm"><span>{l s='Ok'}</span></button>
        </div>
      </fieldset>
    </form>
    <div class="buttons">
      <a class="btn btn-default btn-md icon-left" href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{l s='Home'}">
      	<span>
									{l s='Home page'}
      	</span>
      </a>
    </div>
  </div>
</div>