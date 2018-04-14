{*
* 2002-2016 TemplateMonster
*
* TM Product List Gallery
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
* @author   TemplateMonster
* @copyright  2002-2016 TemplateMonster
* @license  http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="homegooglemap" class="clearfix">
  <div id="tmmap"></div>
</div>
{strip}
  {addJsDef map=''}
  {addJsDef markers=array()}
  {addJsDef infoWindow=''}
  {addJsDef googleScriptStatus=$googleScriptStatus}
  {addJsDef tmdefaultLat=$tmdefaultLat}
  {addJsDef tmdefaultLong=$tmdefaultLong}
  {addJsDef img_store_dir=$img_store_dir}
  {addJsDef tmmarker_path=$marker_path}
  {addJsDefL name=tm_directions}{l s='Get directions' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_1}{l s='Mon' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_2}{l s='Tue' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_3}{l s='Wed' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_4}{l s='Thu' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_5}{l s='Fri' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_6}{l s='Sat' mod='tmgooglemap'}{/addJsDefL}
  {addJsDefL name=translation_7}{l s='Sun' mod='tmgooglemap'}{/addJsDefL}
{/strip}