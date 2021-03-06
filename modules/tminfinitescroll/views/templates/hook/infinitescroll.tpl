{**
* 2002-2016 TemplateMonster
*
* TM Media Parallax
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

{literal}
<script>
  function getMaxPages() {
    return $('#pagination_bottom ul.pagination>li').length - 2;
  }
  function getStartPage() {
      return $('#pagination_bottom ul.pagination>li.current').index() + 1;
  }

  function afterPageCreate() {
    var elem = $('#pagination_bottom ul.pagination>li.active').last().next(),
            child = elem.find('>a'),
            childHtml = child.html();

    if (elem.length) {
      elem.addClass('active');
      child.replaceWith($('<span>' + childHtml + '</span>'));
    }
  }

  function beforeAnimate(items) {
    items.css({'opacity':'0'});
  }

  function animateElems(items) {
    items.each(function(index){
      var item = $(this);
      setTimeout(function(){
        item.animate({'opacity':'1'},500);
      },index*500);
    });
  }

  function afterCreate() {
    {/literal}
      {if !$TMINFINITESCROLL_PAGINATION || $TMINFINITESCROLL_AUTO_LOAD}
        {literal}
          $('#pagination_bottom').hide();
        {/literal}
      {/if}
      {if $TMINFINITESCROLL_SHOW_ALL && !$TMINFINITESCROLL_AUTO_LOAD}
        {literal}
          $('#pagination_bottom form.showall').clone().appendTo('.infiniteScrollNav');
        {/literal}
      {/if}
    {literal}
  }

  $(document).ready(function(){
    $('.product_list').ajaxInfiniteScroll({
      maxPages: getMaxPages,
      startPage: getStartPage,
      offset: {/literal}{$TMINFINITESCROLL_OFFSET|escape:'htmlall':'UTF-8'}{literal},
      loader: {
        image : '{/literal}{$_ps_base_uri}{$TMINFINITESCROLL_PRELOADER|escape:'htmlall':'UTF-8'}{literal}'
      },
      nav: {
        more: {/literal}{if $TMINFINITESCROLL_AUTO_LOAD}false{else}true{/if}{literal},
        moreEnabledText: '{/literal}{l s='Show next'}{literal}',
      },
      afterPageCreate: afterPageCreate,
      afterCreate: afterCreate,
      beforeAnimate: beforeAnimate,
      animate: animateElems
    });
  });
</script>
{/literal}
