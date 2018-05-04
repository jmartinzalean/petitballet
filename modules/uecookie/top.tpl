<script>
    {literal}
    function setcook() {
        var nazwa = 'cookie_ue';
        var wartosc = '1';
        var expire = new Date();
        expire.setMonth(expire.getMonth() + 12);
        document.cookie = nazwa + "=" + escape(wartosc) + ";path=/;" + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()))
    }

    {/literal}
    {if Configuration::get('uecookie_close_anim')==1}
    {literal}
    function closeUeNotify() {
        $('#cookieNotice').fadeOut(1500);
        setcook();
    }
    {/literal}
    {/if}
    {literal}

    {/literal}
    {if Configuration::get('uecookie_close_anim')==0}
    {literal}
    function closeUeNotify() {
        {/literal}{if $vareu->uecookie_position==2}{literal}
        $('#cookieNotice').animate(
                {bottom: '-200px'},
                2500, function () {
                    $('#cookieNotice').hide();
                });
        setcook();
        {/literal}{else}{literal}
        $('#cookieNotice').animate(
                {top: '-200px'},
                2500, function () {
                    $('#cookieNotice').hide();
                });
        setcook();
        {/literal}{/if}{literal}
    }
    {/literal}
    {/if}
    {literal}
    {/literal}
</script>
<style>
    {literal}
    .closeFontAwesome:before {
        content: "\f00d";
        font-family: "FontAwesome";
        display: inline-block;
        font-size: 23px;
        line-height: 23px;
        color: {/literal}#{$vareu->uecookie_closex}{literal};
        padding-right: 15px;
        cursor: pointer;
    }

    .closeButtonNormal {
    {/literal} {if Configuration::get('uecookie_x_where')!=3}display: block;{else}display: inline-block; margin:5px;{/if} {literal}
        text-align: center;
        padding: 2px 5px;
        border-radius: 2px;
        color: {/literal}#{$vareu->uecookie_close_txt}{literal};
        background: {/literal}#{$vareu->uecookie_close_bg}{literal};
        cursor: pointer;
    }

    #cookieNotice p {
        margin: 0px;
        padding: 0px;
    }


    #cookieNoticeContent {
    {/literal}
    {if Configuration::get('uecookie_padding') != ""}
        padding:{Configuration::get('uecookie_padding')}px;
    {/if}
    {literal}
    }

    {/literal}
</style>
{if Configuration::get('uecookie_x_fa')==1}
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
{/if}
<div id="cookieNotice" style=" width: 100%; position: fixed; {if $vareu->uecookie_position==2}bottom:0px; box-shadow: 0px 0 10px 0 #{$vareu->uecookie_shadow};{else} top:0px; box-shadow: 0 0 10px 0 #{$vareu->uecookie_shadow};{/if} background: #{$vareu->uecookie_bg}; z-index: 9999; font-size: 14px; line-height: 1.3em; font-family: arial; left: 0px; text-align:center; color:#FFF; opacity: {$vareu->uecookie_opacity} ">
    <div id="cookieNoticeContent" style="position:relative; margin:auto; width:100%; display:block;">
        <table style="width:100%;">
            <tr>
                {if Configuration::get('uecookie_x_where')==1}
                    <td style="width:80px; vertical-align:middle; padding-right:20px; text-align:left;">
                        {if Configuration::get('uecookie_usex')==1}
                            <span class="closeFontAwesome" onclick="closeUeNotify()"></span>
                        {else}
                            <span class="closeButtonNormal" onclick="closeUeNotify()">{l s='close' mod='uecookie'}</span>
                        {/if}
                    </td>
                {/if}
                <td style="text-align:center;">
                    {$uecookie nofilter}
                </td>
                {if Configuration::get('uecookie_x_where')==2}
                    <td style="width:80px; vertical-align:middle; padding-right:20px; text-align:right;">
                        {if Configuration::get('uecookie_usex')==1}
                            <span class="closeFontAwesome" onclick="closeUeNotify()"></span>
                        {else}
                            <span class="closeButtonNormal" onclick="closeUeNotify()">{l s='close' mod='uecookie'}</span>
                        {/if}
                    </td>
                {/if}
            </tr>
            <tr>
                {if Configuration::get('uecookie_x_where')==3}
                    <td style="width:80px; vertical-align:middle; padding-right:20px; text-align:center;">
                        {if Configuration::get('uecookie_usex')==1}
                            <span class="closeFontAwesome" onclick="closeUeNotify()"></span>
                        {else}
                            <span class="closeButtonNormal" onclick="closeUeNotify()">{l s='close' mod='uecookie'}</span>
                        {/if}
                    </td>
                {/if}
            </tr>
        </table>
    </div>
</div>