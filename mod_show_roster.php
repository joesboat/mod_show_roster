<?php
/**
 * @package		mod_show_member_list for Standard Squadron Site Project 
 * @copyright	Copyright (C) 2015 Joseph P. Gibson. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
include (JPATH_LIBRARIES.'/USPSaccess/dbUSPS.php');
require_once dirname(__FILE__).'/helper.php';
$GLOBALS['mod_folder'] = $mod_folder = explode('.',basename(__FILE__))[0];
$session = JFactory::getSession();
$app = JFactory::getApplication();
//$params = $app->getParams();
$menu = $app->getMenu()->getItem($params->get("show-details"))->alias;

$loging = $params->get("siteLog");
$source = $params->get('roster_type');
$fields = "certificate,last_name,first_name,grade,rank,email,telephone,cell_phone,nickname,nn_prf,spouse";

if ($source == '4785'){
	$squad_name = "Rockville Sail & Power Squadron";
	$heading = "$squad_name Members";
	$list = mod_show_member_helper::getSquadronMembers($source);
} elseif ($source == 'sqd'){
	//$sqds = new tableSquadrons($db_d5,'');
	$user = JFactory::getUser();
	$squad_no = mod_show_member_helper::get_squadron_number_from_certificate($user->username);
	$heading = mod_show_member_helper::getSquadronName($squad_no) . " Members"; 
	$list = mod_show_member_helper::getSquadronMembers($squad_no);
} else {
	$heading = "District 5 Members";
	$list = mod_show_member_helper::getDistrictMembers("5",$menu);
}
$params->def('greeting', 1);
require JModuleHelper::getLayoutPath('mod_show_roster', $params->get('layout', 'default'));
?>