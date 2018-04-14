{*
* 2002-2016 TemplateMonster
*
* TM Newsletter
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
*}

{strip}
  {addJsDef is_logged = $is_logged}
  {addJsDef blocking_popup = $blocking_popup|escape:'quotes':'UTF-8'}
  {addJsDef user_newsletter_status = $user_newsletter_status|escape:'intval'}
  {addJsDef popup_status = $popup_status|escape:'intval'}
  {addJsDef module_url = $module_url|escape:'html'}
{/strip}