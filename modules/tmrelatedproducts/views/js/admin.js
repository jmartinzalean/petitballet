/*
* 2002-2016 TemplateMonster
*
* TM Related Products
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function(){
  initAutocomplite();
  $("#product-tab-content-ModuleTmrelatedproducts").on('loaded', function(){
    initAutocomplite()
  })
});

function initAutocomplite(){
  $('#product_autocomplete_input_new').autocomplete('ajax_products_list.php', {
    minChars: 1,
    autoFill: true,
    max:20,
    matchContains: true,
    mustMatch:false,
    scroll:false,
    cacheLength:0,
    formatItem: function(item){
      return item[1]+' - '+item[0];
    }
  }).result(addRel);
  $('#product_autocomplete_input_new').setOptions({
    extraParams: {
      excludeIds : getRelatedIds()
    }
  });
  $('#divRelated').delegate('.delRelated', 'click', function(){
    delRelated($(this).attr('name'));
  });
}

function getRelatedIds(){
  if ($('#inputRelated').val() === undefined) {
    return id_product;
  }
  return id_product + ',' + $('#inputRelated').val().replace(/\-/g,',');
}

function addRel(event, data, formatted){
  if (data == null) {
    return false;
  }
  var productId = data[1];
  var productName = data[0];
  var $divRelated = $('#divRelated');
  var $inputRelated = $('#inputRelated');
  var $nameRelated = $('#nameRelated');
  $divRelated.html($divRelated.html() + '<div class="form-control-static"><button type="button" class="delRelated btn btn-default" name="' + productId + '"><i class="icon-remove text-danger"></i></button>&nbsp;'+ productName +'</div>');
  $nameRelated.val($nameRelated.val() + productName + '¤');
  $inputRelated.val($inputRelated.val() + productId + '-');
  $('#product_autocomplete_input_new').val('');
  $('#product_autocomplete_input_new').setOptions({
    extraParams: {excludeIds : getRelatedIds()}
  });
};

function delRelated(id){
  var div = getE('divRelated');
  var input = getE('inputRelated');
  var name = getE('nameRelated');
  var inputCut = input.value.split('-');
  var nameCut = name.value.split('¤');
  if (inputCut.length != nameCut.length) {
    return jAlert('Bad size');
  }
  input.value = '';
  name.value = '';
  div.innerHTML = '';
  for (i in inputCut) {
    if (!inputCut[i] || !nameCut[i]) {
      continue;
    }
    if (inputCut[i] != id) {
      input.value += inputCut[i] + '-';
      name.value += nameCut[i] + '¤';
      div.innerHTML += '<div class="form-control-static"><button type="button" class="delRelated btn btn-default" name="' + inputCut[i] +'"><i class="icon-remove text-danger"></i></button>&nbsp;' + nameCut[i] + '</div>';
    } else {
      $('#selectRelated').append('<option selected="selected" value="' + inputCut[i] + '-' + nameCut[i] + '">' + inputCut[i] + ' - ' + nameCut[i] + '</option>');
    }
  }
  $('#product_autocomplete_input_new').setOptions({
    extraParams: {excludeIds : getRelatedIds()}
  });
};