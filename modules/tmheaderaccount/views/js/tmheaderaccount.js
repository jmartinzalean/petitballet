/*
* 2002-2016 TemplateMonster
*
* TM Header Account Block
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
if (countriesNeedIDNumber == undefined) var countriesNeedIDNumber = [];
if (countriesNeedZipCode == undefined) var countriesNeedZipCode = [];
if (states == undefined) var states = [];

tmha = {
  ajax: function(){
    this.init = function(options){
      this.options = $.extend(this.options, options);
      this.request();

      return this;
    };

    this.options = {
      type: 'POST',
      url: baseUri,
      cache: false,
      dataType : "json",
      success: function(){},
      error: this.error
    };

    this.request = function(){
      $.ajax(this.options);
    };

    this.error = function(XMLHttpRequest, textStatus, errorThrown){
      var error = "TECHNICAL ERROR: unable to load form.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus;
      if (!!$.prototype.fancybox) {
        $.fancybox.open([
            {
              type: 'inline',
              autoScale: true,
              minHeight: 30,
              content: "<p class='fancybox-error'>" + error + '</p>'
            }],
          {
            padding: 0
          }
        );
      } else {
        alert(error);
      }
    };
  },
  countries: function()
  {
    var obj = this;

    this.init = function(){
      this.setCountries();
      this.bindStateInputAndUpdate();
      this.postcode_check();
      if (typeof bindUniform !=='undefined') {
        bindUniform();
      }
      this.bindZipcode();

      return this;
    };

    this.reload = function(){
      this.updateState();
      this.updateNeedIDNumber();
      this.updateZipCode();
      this.postcode_check();
    };

    this.postcode_check = function(){
      $('.create-account-content [name=postcode]').on('focusout', function() {
        obj.validate_postcode($(this));
      });
    };

    this.validate_postcode = function(that){
      if (that.hasClass('is_required') || that.val().length)
      {
        var selector = '[name=id_country]';
        if (that.attr('name') == 'postcode_invoice') {
          selector += '_invoice';
        }
        var id_country = $(selector + ' option:selected').val();

        if (typeof(countriesNeedZipCode[id_country]) != 'undefined' && typeof(countries[id_country]) != 'undefined') {
          var result = window['validate_'+that.attr('data-validate')](that.val(), countriesNeedZipCode[id_country], countries[id_country]['iso_code']);
        } else if(that.attr('data-validate')) {
          var result = window['validate_' + that.attr('data-validate')](that.val());
        }
        if (result) {
          that.parent().removeClass('form-error').addClass('form-ok');
        } else {
          that.parent().addClass('form-error').removeClass('form-ok');
        }
      }
    };

    this.setCountries = function setCountries(){
      if (typeof countries !== 'undefined' && countries) {
        var countriesPS = [];
        for (var i in countries) {
          var id_country = countries[i]['id_country'];
          if (typeof countries[i]['states'] !== 'undefined' && parseInt(countries[i]['contains_states'])) {
            countriesPS[id_country] = [];
            for (var j in countries[i]['states']) {
              countriesPS[parseInt(id_country)].push({
                'id'   : parseInt(countries[i]['states'][j]['id_state']),
                'name' : countries[i]['states'][j]['name']
              });
            }
          }

          if (typeof countries[i]['need_identification_number'] !== 'undefined' && parseInt(countries[i]['need_identification_number']) > 0) {
            countriesNeedIDNumber.push(parseInt(countries[i]['id_country']));
          }

          if (typeof countries[i]['need_zip_code'] !== 'undefined' && parseInt(countries[i]['need_zip_code']) > 0) {
            countriesNeedZipCode[parseInt(countries[i]['id_country'])] = countries[i]['zip_code_format'];
          }
        }
      }
      states = countriesPS;
    };

    this.bindZipcode = function(){
      $(document).on('keyup', 'input[name^=postcode]', function(e){
        var char = String.fromCharCode(e.keyCode);
        if (/[a-zA-Z]/.test(char))
          $.trim($(this).val($(this).val().toUpperCase()));
      });
    };

    this.bindStateInputAndUpdate = function(){
      $('.create-account-content .id_state, .create-account-content .dni, .create-account-content .postcode').css({'display':'none'});
      this.updateState();
      this.updateNeedIDNumber();
      this.updateZipCode();

      $(document).on('change', '.create-account-content [name=id_country]', function(e) {
        obj.updateState();
        obj.updateNeedIDNumber();
        obj.updateZipCode();
        obj.validate_postcode('.create-account-content [name=postcode]');
      });
      if (typeof idSelectedState !== 'undefined' && idSelectedState) {
        $('.create-account-content .id_state option[value=' + idSelectedState + ']').prop('selected', true);
      }
    };

    this.updateState = function(suffix){
      $('.create-account-content [name=id_state]' + (typeof suffix !== 'undefined' ? '_' + suffix : '')+' option:not(:first-child)').remove();
      if (typeof countries !== 'undefined') {
        var state_list = states[parseInt($('.create-account-content [name=id_country]' + (typeof suffix !== 'undefined' ? '_' + suffix : '')).val())];
      }
      if (typeof state_list !== 'undefined') {
        $(state_list).each(function(key, item){
          $('.create-account-content [name=id_state]' + (typeof suffix !== 'undefined' ? '_' + suffix : '')).append('<option value="' + parseInt(item.id) + '">' + item.name + '</option>');
        });

        $('.create-account-content .id_state' + (typeof suffix !== 'undefined' ? '_' + suffix : '') + ':hidden').fadeIn('slow');
        $('.create-account-content [name=id_state]').uniform();
      }
      else {
        $('.create-account-content .id_state' + (typeof suffix !== 'undefined' ? '_' + suffix : '')).fadeOut('fast');
      }
    };

    this.updateNeedIDNumber = function(suffix){
        var id_country = parseInt($('.create-account-content [name=id_country]' + (typeof suffix !== 'undefined' ? '_' + suffix : '')).val());
        if (in_array(id_country, countriesNeedIDNumber)) {
          $('.create-account-content .dni' + (typeof suffix !== 'undefined' ? '_' + suffix : '') + ':hidden').fadeIn('slow');
          $('.create-account-content [name=dni]').uniform();
        }
        else {
          $('.create-account-content .dni' + (typeof suffix !== 'undefined' ? '_' + suffix : '')).fadeOut('fast');
        }
    };
    this.updateZipCode = function(suffix){
      var id_country = parseInt($('.create-account-content [name=id_country]' + (typeof suffix !== 'undefined' ? '_' + suffix : '')).val());
      if (typeof countriesNeedZipCode[id_country] !== 'undefined') {
        $('.create-account-content .postcode' + (typeof suffix !== 'undefined' ? '_' + suffix : '') + ':hidden').fadeIn('slow');
        $('.create-account-content [name=postcode]').uniform();
      }
      else {
        $('.create-account-content .postcode'+(typeof suffix !== 'undefined' ? '_' + suffix : '')).fadeOut('fast');
      }
    }
  },
  sidebar: function(){
    var obj = this;
    this.init = function(options){
      this.options = $.extend(this.options, options);
      this.create();
      this.options.parent.click(function (event) {
        if ($(event.target).closest('.'+obj.options.selector).length === 0 || event.target.classList.contains('tmha-close-btn')) {
          obj.hide();
        }
      });

      return this;
    };
    this.options = {
      selector: 'tmha-sidebar-left',
      parent: $('body'),
      content: TMHEADERACCOUNT_CONTENT
    };
    this.create = function(){
      this.elem = document.createElement('div');
      this.elem.classList.add(this.options.selector);
      this.elem.innerHTML = '<span class="tmha-close-btn"></span>'+this.options.content;
      this.options.parent.append(this.elem);
      if ($('.header-login-content.is-logged').length && TMHEADERACCOUNT_DISPLAY_STYLE == 'twocolumns') {
        elementTwocolumns();
      }
    };
    this.toggle = function(){
      this.elem.classList.toggle('active');
    };
    this.hide = function(){
      this.elem.classList.remove('active');
    }
  },
  fancy: function(){
    this.init = function(options){
      this.options = $.extend(this.options, options);
      return this;
    };
    this.options = {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 285,
      padding: 0,
      content: TMHEADERACCOUNT_CONTENT,
      tpl: {
        closeBtn : '<a title="Close" class="fancybox-item tmha-close-btn" href="javascript:;"></a>'
      },
      helpers: {
        overlay: {
          locked: false
        }
      }
    };
    this.toggle = function(){
      $.fancybox(this.options);
      if (TMHEADERACCOUNT_DISPLAY_STYLE == 'twocolumns') {
        elementTwocolumns();
      }
    };
  },
  init: function(type){
    this.countries = new tmha.countries();
    this.countries.init();
    if (type == 'popup')  {
      this.display = new tmha.fancy();
      this.display.init({});
    } else if(type == 'leftside') {
      this.display = new tmha.sidebar();
      this.display.init();
    } else if (type == 'rightside') {
      this.display = new tmha.sidebar();
      this.display.init({selector: 'tmha-sidebar-right'});
    }
    this.reload = function(){
      this.countries.reload();
    };
    return this;
  }
};

$(document).ready(function(){
  if (typeof TMHEADERACCOUNT_CONTENT == 'undefined') {
    TMHEADERACCOUNT_CONTENT = '';
  }
  var tmheaderaccount = new tmha.init(TMHEADERACCOUNT_DISPLAY_TYPE);
  var $d = TMHEADERACCOUNT_DISPLAY_TYPE == 'popup' ? $(document) : $('.header-login-content');

  $('.tm_header_user_info').on('click', function() {
    if (TMHEADERACCOUNT_DISPLAY_TYPE != 'dropdown') {
      tmheaderaccount.display.toggle();
    }
    tmheaderaccount.reload();
  });

  $d.on('click', '[name=HeaderSubmitLogin]', function (e) {
    e.preventDefault();
    submitLoginFunction($(this).closest('.login-content'));
  });
  $d.on('click', '.create-account-content button[type=submit]', function(e){
    e.preventDefault();
    submitCreate($(this).closest('.create-account-content'));
  });
  $d.on('click', '.forgot-password-content [type="submit"]', function(e){
    e.preventDefault();
    submitRetrieve($(this).closest('.forgot-password-content'));
  });


  $('.alert.alert-danger').live('click', this, function(e) {
    if (e.offsetX >= 16 && e.offsetX <= 39 && e.offsetY >= 16 && e.offsetY <= 34) {
      $(this).slideUp();
    }
  });

  if (TMHEADERACCOUNT_DISPLAY_STYLE == 'twocolumns' && TMHEADERACCOUNT_DISPLAY_TYPE == 'dropdown') {
    elementTwocolumns();
  };

  if (!parseInt(TMHEADERACCOUNT_USE_REDIRECT)) {
    $d.on('click', '.login-content a.create', function (e) {
      e.preventDefault();
      $(this).closest('.header-login-content').find('.login-content').addClass('hidden');
      $(this).closest('.header-login-content').find('.create-account-content').removeClass('hidden');
      if (typeof bindUniform !=='undefined') {
        bindUniform();
      }
      if (TMHEADERACCOUNT_DISPLAY_TYPE == 'popup') {
        $.fancybox.reposition();
        $.fancybox.update()
      }
    });
    $d.on('click', '.create-account-content a.signin, .forgot-password-content a.signin', function (e) {
      e.preventDefault();
      $(this).closest('.header-login-content').find('.login-content').removeClass('hidden');
      $(this).closest('.header-login-content').find('.create-account-content').addClass('hidden');
      $(this).closest('.header-login-content').find('.forgot-password-content').addClass('hidden');
      if (TMHEADERACCOUNT_DISPLAY_TYPE == 'popup') {
        $.fancybox.reposition();
        $.fancybox.update()
      }
    });
    $d.on('click', '.login-content a.forgot-password', function (e) {
      e.preventDefault();
      $(this).closest('.header-login-content').find('.login-content').addClass('hidden');
      $(this).closest('.header-login-content').find('.forgot-password-content').removeClass('hidden');
      if (TMHEADERACCOUNT_DISPLAY_TYPE == 'popup') {
        $.fancybox.reposition();
        $.fancybox.update()
      }
    });
  }
});

function submitLoginFunction(elem){
  var options = {
    data: {
      controller: 'authentication',
      SubmitLogin: 1,
      ajax: true,
      email: elem.find('.email').val(),
      passwd: elem.find('.password').val(),
      token: token
    },
    success: function(jsonData){
      if (jsonData.hasError) {
        var errors = '';
        for(error in jsonData.errors) {
          if (error != 'indexOf') {
            errors += '<li>' + jsonData.errors[error] + '</li>';
          }
        }
        elem.find('.alert.alert-danger').html('<ol>' + errors + '</ol>').slideDown();
      } else {
        document.location.reload();
      }
    }
  };
  var ajax = new tmha.ajax();
  ajax.init(options);
}

function submitCreate(elem){
  var options = {
    data: elem.find('form').serialize() + '&submitAccount=1&fc=module&module=tmheaderaccount&controller=auth&ajax=true',
    success: function(jsonData)
    {
      if (jsonData.hasError)
      {
        var errors = '';
        for(error in jsonData.errors)
          //IE6 bug fix
          if(error != 'indexOf')
            errors += '<li>' + jsonData.errors[error] + '</li>';
        elem.find('.alert.alert-danger').addClass('alert-danger').html('<ol>' + errors + '</ol>').slideDown();
      } else {
        document.location.reload();
      }
    }
  };
  var ajax = new tmha.ajax();
  ajax.init(options);
}

function submitRetrieve(elem) {
  var options = {
    url: baseDir + 'modules/tmheaderaccount/controllers/front/password.php',
    data:
    {
      retrievePassword: 1,
      email: elem.find('[name=email]').val()
    },
    success: function(jsonData)
    {
      if (jsonData.hasError)
      {
        var errors = '';
        for(error in jsonData.errors)
          if(error != 'indexOf')
            errors += '<li>' + jsonData.errors[error] + '</li>';
        elem.find('.alert.alert-success').slideUp();
        elem.find('.alert.alert-danger').html('<ol>' + errors + '</ol>').slideDown();
      } else {
        elem.find('.alert.alert-danger').slideUp();
        elem.find('.alert.alert-success').html(jsonData.confirm).slideDown();
      }
    }
  };
  var ajax = new tmha.ajax();
  ajax.init(options);
}

function elementTwocolumns() {
  $('.header-login-content .twocolumns > ul').each(function(){
    $(this).find('.user-data').prependTo($(this).parents('.header-login-content'));
    var total = $(this).children().length;
    var half = Math.ceil(total / 2) - 1;
    $(this).children(':gt('+half+')').detach().wrapAll('<ul></ul>').parent().insertAfter(this);
  });
}
