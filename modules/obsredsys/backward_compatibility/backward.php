<?php
/**
 * 2011-2018 OBSOLUTIONS WD S.L. All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of OBSOLUTIONS WD S.L. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to OBSOLUTIONS WD S.L.
 * and its suppliers and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from OBSOLUTIONS WD S.L.
 *
 *  @author    OBSOLUTIONS WD S.L. <http://addons.prestashop.com/en/65_obs-solutions>
 *  @copyright 2011-2018 OBSOLUTIONS WD S.L.
 *  @license   OBSOLUTIONS WD S.L. All Rights Reserved
 *  International Registered Trademark & Property of OBSOLUTIONS WD S.L.
 */

// Get out if the context is already defined
if (!in_array('Context', get_declared_classes()))
	require_once(dirname(__FILE__).'/Context.php');

// Get out if the Display (BWDisplay to avoid any conflict)) is already defined
if (!in_array('BWDisplay', get_declared_classes()))
	require_once(dirname(__FILE__).'/Display.php');

// If not under an object we don't have to set the context
if (!isset($this))
	return;
else if (isset($this->context))
{
	// If we are under an 1.5 version and backoffice, we have to set some backward variable
	if (_PS_VERSION_ >= '1.5' && isset($this->context->employee->id) && $this->context->employee->id && isset(AdminController::$currentIndex) && !empty(AdminController::$currentIndex))
	{
		global $currentIndex;
		$currentIndex = AdminController::$currentIndex;
	}
	return;
}

$this->context = Context::getContext();
$this->smarty = $this->context->smarty;
