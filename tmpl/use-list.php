<?php
/**
 * @package		mod_show_member_list for Standard Squadron Site Project 
 * @subpackage	default.php  Original view module for displaying the member list.  
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
<h4 class='text-center'> Squadron Members </h4>
<!--Contact Information-->
<ul class='list-inline'>
		<li> Name</li>
		<li> email</li>
		<li> Telephone</li>
		<li> Cellphone</li>
		<br/>
<!--
	Each member record includes the name, email, phone, cell phone
-->
<?php
	foreach($members as $member){
		$name = $member['mbr_name'];
		$phone = $member['telephone'];
		$cell = $member['cellphone'];
		$email = $member['email'];
?>
		<li> <?php echo $name; ?></li>
		<li> <?php echo $email; ?></li>
		<li> <?php echo $phone; ?></li>
		<li> <?php echo $cell; ?></li>
		<br/>
<?php 
	} 
?>
	</ul>
</form>