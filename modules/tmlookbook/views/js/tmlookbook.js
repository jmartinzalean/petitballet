/*
 * 2002-2016 TemplateMonster
 *
 * TM Look Book
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0

 * @author     TemplateMonster
 * @copyright  2002-2016 TemplateMonster
 * @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function(){
  var $d = $(this);

  $d.on('click', '.hotSpotWrap .point', function() {
    var $t = $(this);
    $t.parent('.hotSpotWrap').find('.point.active').not(this).popover('hide');
  });
});