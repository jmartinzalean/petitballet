{if $facebookurl != ''}
  <div id="fb-root"></div>
  <div id="facebook_block">
    <div class="box">
      <h4 >{l s='Follow us on Facebook' mod='blockfacebook'}</h4>
      <div class="facebook-fanbox">
        <div class="fb-like-box"
          data-href="{$facebookurl|escape:'html':'UTF-8'}"
          data-height="300"
          data-colorscheme="light"
          data-show-faces="true"
          data-header="false"
          data-stream="false"
          data-show-border="false">
        </div>
      </div>
    </div>
  </div>
{/if}
