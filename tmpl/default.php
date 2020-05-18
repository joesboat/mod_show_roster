<?php
/**
 * @package		mod_show_member_list for Standard Squadron Site Project 
 * @subpackage	default.php  View module for displaying the member list in an HTML table.  
 * @copyright	Copyright (C) 2015 - Joseph P. Gibson. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
$year = 0;
?>
<form action="
<?php 
	echo JRoute::_('index.php', true, $params->get('usesecure')); 
?>
" method="post" id="login-form">
<!--Rank, Name, Grade-->
<h4 class='text-center'> <?php echo $heading; ?> </h4>
<!--Contact Information-->
<table class='table'>
	<tr>
		<th> Name</th>
		<th> email</th>
		<th> Telephone</th>
		<th> Cellphone</th>
	</tr>
<!--
	Each member record includes the name, email, phone, cell phone
-->
<?php
	$working = true;
	$i = 0;
	foreach ($list as $row){ 	//while (($row = $list->fetch_assoc()) and $working ){
?>
	<tr>		
		<td> <?php echo $row['member_name_with_modal']; ?></td>
		<td> <?php echo $row['email']; ?></td>
		<td> <?php echo $row['telephone'];?></td>
		<td> <?php echo $row['cellphone'];?></td>
	</tr>
<?php 
		$i++;
		//if ($i > 1000) $working = false;
	}
?>
	</table>
</form>