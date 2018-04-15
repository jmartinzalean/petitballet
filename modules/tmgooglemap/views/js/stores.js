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

// init google map script
function initTmgoogleMapScript(url, callback){
  var tmGoogleScript = document.createElement('script');
  if (callback) {
    tmGoogleScript.onload = callback;
  }
  tmGoogleScript.async = true;
  tmGoogleScript.type = 'text/javascript';
  tmGoogleScript.src = url;
  document.body.appendChild(tmGoogleScript);
}

// load google map api
function loadTMGoogleMapsAPI(){
  if (typeof(googleScriptStatus) != 'undefined') {
    if (!googleScriptStatus) {
      if(TMGOOGLE_MAP_KEY != false){
        initTmgoogleMapScript('//maps.googleapis.com/maps/api/js?key='+TMGOOGLE_MAP_KEY+'&sensor=true&callback=initTMGoogleMap');
      } else {
        initTmgoogleMapScript('//maps.googleapis.com/maps/api/js?sensor=true&callback=initTMGoogleMap');
      }
    } else {
      initTMGoogleMap();
    }
  }
}

$(window).load(function(){
  loadTMGoogleMapsAPI();
});

// init google map
function initTMGoogleMap(){
  if (typeof(tmdefaultLat) == 'undefined' && typeof(tmdefaultLong) == 'undefined') {
    return;
  }
  if ($('#tmmap').length < 1) {
    return;
  }
  var isDraggable = $(window).width() > 1023 ? true : false,
  isPan = $(window).width() < 1024 ? true : false;
  tmmap = new google.maps.Map(document.getElementById('tmmap'), {
    center: new google.maps.LatLng(parseFloat(tmdefaultLat), parseFloat(tmdefaultLong)),
    zoom: TMGOOGLE_ZOOM,
    mapTypeId: TMGOOGLE_TYPE,
    scrollwheel: TMGOOGLE_SCROLL,
    mapTypeControl: TMGOOGLE_TYPE_CONTROL,
    streetViewControl: TMGOOGLE_STREET_VIEW,
    draggable:isDraggable,
    panControl: isPan,
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
	},
    styles: google_map_style
  });
  infoWindow = new google.maps.InfoWindow();
  initTmMarkers();
}

// init markers
function initTmMarkers(){

  Object.defineProperty(Array.prototype, 'mapWithIndex', {
    enumerable: false,
    value: function (combined) {
      var toR = [];
      if (typeof combined === 'function') {
        for (var i = 0; i < this.length; i++) {
          toR.push(combined.apply(null, [this[i], i]));
        }
        return toR;
      } else {
        return this;
      }
    }
  });
  
  function inherits(base, extension){
    if (!extension) {
      extension={};
	}
    for ( var property in base ) {
      extension[property] = base[property];
    }
    return extension ;
  };

  if (typeof(tm_store_contact) == 'undefined' && typeof(tm_store_custom) == 'undefined') {
    tm_store_contact = tm_store_contact.sort(function(a,b){return a.id-b.id_store});
    tm_store_custom = tm_store_custom.sort(function(a,b){return a.id-b.id_store});
  }

  var combined=tm_store_contact.mapWithIndex(function(e,i){
    return inherits(e,tm_store_custom[i])
  });

  function explode(delimiter, string){
    var emptyArray = { 0: '' };
    if (arguments.length != 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {
      return null;
    }
    if (delimiter === '' || delimiter === false || delimiter === null) {
      return false;
    }
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
      return emptyArray;
    }
    if (delimiter === true) {
      delimiter = '1';
    }
    return string.toString().split (delimiter.toString());
  }

  for (var i = 0; i < combined.length; i++) {
    var tmname = combined[i]['name'],
    tmaddress = combined[i]['address1'],
    tmaddressNoHtml = combined[i]['addressNoHtml'],
    tmother = combined[i]['hours'],
    tmid_store = combined[i]['id_store'],
    tmid = combined[i]['id'],
    tmcontent = combined[i]['content'],
    tmimagemarker = tmmarker_path+combined[i]['marker'],
    tmhas_store_picture = combined[i]['id_image'],
    tmlatlng = new google.maps.LatLng(
      parseFloat(combined[i]['latitude']),
      parseFloat(combined[i]['longitude'])
    );
    var tmdays =  explode(';',explode('{', tmother)[1]);
    popped = tmdays.pop();
    for (var u = 0; u < tmdays.length; u++) {
      var tmdays_1 = tmdays[1].substring(6).slice(0,-1),
      tmdays_2 = tmdays[3].substring(6).slice(0,-1),
      tmdays_3 = tmdays[5].substring(6).slice(0,-1),
      tmdays_4 = tmdays[7].substring(6).slice(0,-1),
      tmdays_5 = tmdays[9].substring(6).slice(0,-1),
      tmdays_6 = tmdays[11].substring(6).slice(0,-1),
      tmdays_7 = tmdays[13].substring(6).slice(0,-1);
    }

	createTmMarker(tmlatlng, tmname, tmaddress, tmother, tmid_store, tmhas_store_picture, tmid, tmimagemarker, tmcontent, tmdays_1, tmdays_2, tmdays_3, tmdays_4, tmdays_5, tmdays_6, tmdays_7);
  }

  var tmzoomOverride = tmmap.getZoom();
  if (tmzoomOverride > TMGOOGLE_ZOOM) {
    tmzoomOverride = TMGOOGLE_ZOOM;
  }
  tmmap.setZoom(tmzoomOverride);
}

// create marker
function createTmMarker(tmlatlng, tmname, tmaddress, tmother, tmid_store, tmhas_store_picture, tmid, tmimagemarker, tmcontent, tmdays_1, tmdays_2, tmdays_3, tmdays_4, tmdays_5, tmdays_6, tmdays_7){
  var tmhours_1 = '<p><b>' + translation_1 + ': </b><span>' + tmdays_1 + '</span></p>',
  tmhours_2 = '<p><b>' + translation_2 + ': </b><span>' + tmdays_2 + '</span></p>',
  tmhours_3 = '<p><b>' + translation_3 + ': </b><span>' + tmdays_3 + '</span></p>',
  tmhours_4 = '<p><b>' + translation_4 + ': </b><span>' + tmdays_4 + '</span></p>',
  tmhours_5 = '<p><b>' + translation_5 + ': </b><span>' + tmdays_5 + '</span></p>',
  tmhours_6 = '<p><b>' + translation_6 + ': </b><span>' + tmdays_6 + '</span></p>',
  tmhours_7 = '<p><b>' + translation_7 + ': </b><span>' + tmdays_7 + '</span></p>';
  var tmhours = '<div class="tmgooglemap-hours clearfix">' +
    ''+(!!(tmdays_1) ? tmhours_1 : '')+'' +
    ''+(!!(tmdays_2) ? tmhours_2 : '')+'' +
    ''+(!!(tmdays_3) ? tmhours_3 : '')+'' +
    ''+(!!(tmdays_4) ? tmhours_4 : '')+'' +
    ''+(!!(tmdays_5) ? tmhours_5 : '')+'' +
    ''+(!!(tmdays_6) ? tmhours_6 : '')+'' +
    ''+(!!(tmdays_7) ? tmhours_7 : '')+
    '</div>';
  var tmhtml = '<div class="marker_content tmgooglemap-popup-'+tmid_store+'">' +
    '<div class="tmaddress-content clearfix">'+((!!tmhas_store_picture) ? '<img class="marker_logo" src="'+img_store_dir+parseInt(tmid)+'.jpg" alt="" />' : '')+'' +
    '<div class="description"><h5>'+tmname+'</h5><p>'+tmaddress+'</p></div>' +
    '</div><div class="tmgooglemap-content clearfix">'+tmcontent+'</div>'+tmhours+'<a class="tmlink-directions" href="//maps.google.com/maps?saddr=&daddr='+tmlatlng+'" target="_blank">'+tm_directions+'</a>' +
    '</div>';
  var tmmarker = '';
  var tmanimationmarker = '';
  if (TMGOOGLE_ANIMATION == true) {
    tmanimationmarker = google.maps.Animation.BOUNCE;
  } else {
    tmanimationmarker = false;
  }

  tmmarker = new google.maps.Marker({ map: tmmap, position: tmlatlng, icon: tmimagemarker, title: tmname, animation: tmanimationmarker });
  google.maps.event.addListener(tmmarker, 'click', function() {
    if (TMGOOGLE_POPUP == true) {
      infoWindow.setContent(tmhtml);
      infoWindow.open(tmmap, tmmarker);
    }
  });
  markers.push(tmmarker);
}