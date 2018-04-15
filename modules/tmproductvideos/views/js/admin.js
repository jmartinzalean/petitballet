/*
* 2002-2016 TemplateMonster
*
* TM Product Videos
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
  tabs_manager.onLoad('ModuleTmproductvideos', function(){
    $(window).trigger('resize'); // hack for video video.js responsive width initialisation
	  initTiny();
	  initAjax();
	  fancyInit();
	});
});

function initTiny(){
  tinyMCE.execCommand( 'mceRemoveControl', false, 'autoload_rte_custom');
  tinySetup({
    editor_selector :"autoload_rte_custom",
    setup : function(ed){
      ed.on('init', function(ed){
	    if (typeof ProductMultishop.load_tinymce[ed.id] != 'undefined') {
          tinyMCE.triggerSave();
        }
      });
      ed.on('keydown', function(ed, e){
        tinyMCE.triggerSave();
      });
	}
  });
}

function fancyInit(){
  $('.fancybox-media').fancybox({
    openEffect  : 'none',
    closeEffect : 'none',
    helpers : {
      media : {}
    }
  });

  $('.video-btn').fancybox({
    'width': 900,
    'height': 600,
    'type': 'iframe',
    'autoScale': false,
    'autoDimensions': false,
    'fitToView': false,
    'autoSize': false,
    onUpdate: function(){
      $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
      $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
	},
    afterShow: function(){
      $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
      $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
    }
  });
}

function sortInit(elNumb){
  if ($("#video-list-"+elNumb+" li").length > 1) {
	$("#video-list-"+elNumb).sortable({
	  cursor: 'move',
	  start: function(e, ui){
		$(ui.item).find('textarea').each(function(){
		  tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
		});
	  },
	  stop: function(e, ui){
		$(ui.item).find('textarea').each(function(){
		  tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
		});
		$(this).sortable('refresh');
	  },
	  update: function(event, ui){
		$('#video-list-'+elNumb+' li').not('.no-slides').each(function(index){
		  $(this).find('.sort-order').text(index + 1);
		});
	  }
	}).bind('sortupdate', function(){
	  var items = $(this).sortable('toArray');
	  var id_lang = $(this).find("input[name='id_lang']").val();

	  $.ajax({
		type: 'POST',
		url: theme_url + '&configure=themeconfigurator&ajax',
		headers: { "cache-control": "no-cache" },
		dataType: 'json',
		data: {
		  action: 'updateposition',
		  item: items,
		  id_lang: id_lang
		},
		success: function(msg){
		  if (msg.error) {
            showErrorMessage(msg.error);
            return;
		  }
		  showSuccessMessage(msg.success);
		}
	  });
	});
  }
}

function initAjax(){
  if (typeof(shopCount) !='undefined' && shopCount.length) {
	for (i = 0; i < shopCount.length; i++) {
	  sortInit(shopCount[i]);
	}
	$(".video-list li.video-item button.list-action-enable").on('click', function(){
	  var id_video = $(this).parents('li').find('input[name="id_video"]').val();
	  var id_lang = $(this).parents('li').find('input[name="id_lang"]').val();
	  var video_status = $(this).parents('li').find('input[name="item_status"]').val();
	  var element = $(this);
	  var elementStatus = $(this).parents('li').find('input[name="item_status"]');

	  $.ajax({
		type: 'POST',
		url: theme_url + '&ajax',
		headers: {"cache-control": "no-cache"},
		dataType: 'json',
		data: {
		  action: 'updatestatus',
		  id_video: id_video,
		  id_lang: id_lang,
		  ideo_status: video_status
		},
		success: function(msg){
		  if (msg.error_status) {
		    showErrorMessage(msg.error_status);
		    return;
		  }
		  showSuccessMessage(msg.success_status);
		  if (element.hasClass('action-disabled')) {
		    elementStatus.val(1),
			element.removeClass('action-disabled').addClass('action-enabled'),
			element.children('i').removeClass('icon-remove').addClass('icon-check');
		  } else {
		    elementStatus.val(0),
			element.removeClass('action-enabled').addClass('action-disabled'),
			element.children('i').removeClass('icon-check').addClass('icon-remove');
		  }
		}
	  });

	  return false;
	});

	$(".video-list li.video-item button.btn-remove").on('click', function(){
	  var id_video = $(this).parents('li').find('input[name="id_video"]').val();
	  var id_lang = $(this).parents('li').find('input[name="id_lang"]').val();
	  var element = $(this).parents('li');

	  $.ajax({
		type: 'POST',
		url: theme_url + '&ajax',
		headers: {"cache-control": "no-cache"},
		dataType: 'json',
		data: {
		  action: 'removeitem',
		  id_video: id_video,
		  id_lang: id_lang
		},
		success: function(msg){
		  if (msg.error_status) {
		    showErrorMessage(msg.error_status);
		    return;
		  }
		  showSuccessMessage(msg.success_status);
		  element.fadeOut();
		}
	  });

	  return false;
	});

	$(".video-list li.video-item button.btn-save").on('click', function(){
	  var id_video = $(this).parents('li').find('input[name="id_video"]').val();
	  var id_lang = $(this).parents('li').find('input[name="id_lang"]').val();
	  var video_name = $(this).parents('li').find('input[name="video_name"]').val();
	  var video_description = $(this).parents('li').find('textarea[name="video_description"]').val();
	  var newName = video_name;
	  var element = $(this).parents('li');

	  $.ajax({
		type: 'POST',
		url: theme_url + '&ajax',
		headers: {"cache-control": "no-cache"},
		dataType: 'json',
		data: {
		  action: 'updateitem',
		  id_video: id_video,
		  id_lang: id_lang,
		  video_name: video_name,
		  video_description: video_description,
		},
		success: function(msg){
		  if (msg.error_status) {
		    showErrorMessage(msg.error_status);
		    return;
		  }
		  showSuccessMessage(msg.success_status);
		  element.find('h4.item-tile').text(video_name);
		}
	  });

	  return false;
	});
  }
};