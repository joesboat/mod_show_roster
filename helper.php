<?php
/**
 * @package		mod_show_member_list for Standard Squadron Site Project 
 * @subpackage	Helper Module - Obtains Member Records  
 * @copyright	Copyright (C) 2015 Joseph P. Gibson. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
jimport('usps.tableD5VHQAB');
jimport('usps.includes.routines');
class mod_show_member_helper
{
//*******************************************************
 static function getMemberList($list,$menu){
	$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	foreach ($list as $mbr){
		$member['mbr_name'] = get_person_name($mbr);
		$member['email']=$mbr['email'];
		$member['telephone']=$mbr['telephone'];
		$member['cellphone']=$mbr['cell_phone'];
		$member['member_name_with_link'] = build_member_link($mbr,$menu);
		$member['member_name_with_modal'] = build_member_modal($mbr,$menu);
		$members[] = $member;
	}
	return $members;
}
//*******************************************************
static function getDistrictMembers($distno = 5, $menu){
	$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	$distno = sprintf("%02d",$distno);
	$list = $vhqab->getDistrictMembers($distno);
	$members = mod_show_member_helper::getMemberList($list,$menu);
	$vhqab->close();
	return $members;
}
//*******************************************************
static function getSquadronMembers($squad_no){
	$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	$squad_no = sprintf("%04d",$squad_no);
	$list = $vhqab->getSquadronMembers($squad_no);
	$members = mod_show_member_helper::getMemberList($list,$vhqab);
	$vhqab->close();
	return $members;
	// Get the list and call GetList format for display
}
//*******************************************************
static function getSquadronName($squad_no){
	$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	$sqds = $vhqab->getSquadronObject();
	$squad_name = $sqds->getSquadronName($squad_no);
	$vhqab->close();
	return $squad_name;
}
//*******************************************************
static function get_squadron_number_from_certificate($certno){
	$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	$squad_no = sprintf("%04d",$vhqab->getSquadNumber($certno));
	$vhqab->close();
	return $squad_no;		
}
} // end of class
//****************************************************************
function build_member_link($mbr,$menu){
$mod_folder = $GLOBALS['mod_folder'];
	$str = JURI::base()."modules/$mod_folder/assets/member_gen.php?ml=1&certno=".$mbr['certificate'];
	$str = JURI::base()."$menu?ml=1&certno=".$mbr['certificate'];
	$url = get_absolute_url($str);
	$str = "<a href='$url' class='name' target='_blank' >";
	$str .= get_person_name($mbr);
	$str .= "</a>";
	return $str;
}
//****************************************************************
function build_member_modal($mbr,$menu){
$mod_folder = $GLOBALS['mod_folder'];
	$str = JURI::base()."modules/$mod_folder/assets/member_gen.php?ml=1&certno=".$mbr['certificate'];
	$str = JURI::base()."$menu?ml=1&certno=".$mbr['certificate'];
	$url = get_absolute_url($str);
	$str = "{modal ";  
	$str .= $url;
	$str .= "}";
	$str .= get_person_name($mbr);;
	$str .= "{/modal} ";
	return $str;
}
