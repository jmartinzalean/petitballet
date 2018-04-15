/*
 * 2002-2016 TemplateMonster
 *
 * TM Google Map
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
 * @author     TemplateMonster
 * @copyright  2002-2016 TemplateMonster
 * @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function(){
  showForm();
  $('.filemanager_image .input-group .form-control, .iframe-btn').fancybox({
    'width': 900,
    'height': 600,
    'type': 'iframe',
    'autoScale': false,
    'autoDimensions': false,
    'fitToView': false,
    'autoSize': false,
    onUpdate: function () {
      $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
      $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
    },
    afterShow: function () {
      $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
      $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
    },
    afterClose: function () {
    setTimeout(function () {
      $('.layout_image').attr('src',$('#image').attr('value'));
        showForm();
      }, 50);
    }
  });

  $(document).on('click', 'button#remove_map_marker', function(){
    $(this).parents('form').find('input[name="old_marker"]').val('');
    $(this).parents('form').find('.megamenu-marker-preview').remove();
    return false;
  });

});

function showForm() {
  if ($('#image').attr('value') != '') {
    $('.layout_image').removeClass('hidden');
  }
}