{**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<form id="import_layout_form" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
  <div class="form-wrapper">
    <div class="form-group">
      <label class="control-label col-lg-3">
        {l s='Zip file' mod='tmmegalayout'}
      </label>
      <div class="col-lg-5">
        <div class="form-group">
          <div class="col-sm-12">
            <input id="layoutArchive" type="file" name="themearchive" class="hide">
            <div class="dummyfile input-group">
              <span class="input-group-addon"><i class="icon-file"></i></span>
              <input id="layoutArchiveName" type="text" name="filename" readonly="">
              <span class="input-group-btn">
                <button id="selectLayoutArchive" type="button" name="submitAddAttachments" class="btn btn-default">{l s='Add file' mod='tmmegalayout'}</button>
              </span>
            </div>
          </div>
        </div>
        <p class="help-block text-center">
          {l s='Browse your computer files and select the Zip file for your layout.' mod='tmmegalayout'}<br>
          {l s='Maximum file size:' mod='tmmegalayout'}{$max_file_size|escape:'htmlall':'UTF-8'}.<br>
          {l s='You can change it in your server settings.' mod='tmmegalayout'}
        </p>
      </div>
    </div>
    <div class="form-group layout-preview-wrapper hidden">
    </div>
  </div>
</form>