<?php
$logging = false;
include($_SERVER['CONTEXT_DOCUMENT_ROOT']."/applications/setupJoomlaAccess.php");
// require_once(JPATH_LIBRARIES."/USPSaccess/dbUSPS.php");
$GLOBALS['lodr'] = $lodr = new displayOrder($db_d5, '');
$sqds = $vhqab->getSquadronObject();
$jobs = $vhqab->getJobsObject();
//$blob = new tableD5Blobs($db_d5);
$blob = JoeFactory::getTable("tableD5Blobs",$db_d5);
$desc = $vhqab->getJobcodesObject();
	//$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	if ($logging) log_it("Entering ".__FILE__,__LINE__);
	$certno = $_GET['certno'];
	$row = $vhqab->getD5Member($_GET['certno']);
	$squad_no = $row['squad_no'];
	$year = $vhqab->getSquadronDisplayYear($squad_no);
	$address_id = $row['address_id'];
	$nn_prf = $row['nn_prf'];
	$nickname = $row['nickname'];
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$display_only = false;

	$hdr = $vhqab->getSquadronName($row['squad_no']);
	showHeader($hdr, '?'.htmlspecialchars(SID));
?>
	<h2>
		<?php echo $vhqab->getMemberNameAndRank($certno,false); ?>
	</h2>
	<div id='roster_data' style="width:700px; vertical-align:text-top;">
	<fieldset id="main">
	<?php echo $certno; ?>
	<br />
	Contact Information:
	<br />
	<!--<label> Address:</label>-->		
	<?php echo $row['address_1']." ".$row['address_2'];?> 
	<br />
	<!--<label>City: </label>-->
		<?php echo $row['city'];?>, <?php echo $row['state']; ?> <?php echo $row['zip_code'];?>
	<br />
	<!--<label>Telephone:</label>-->
	(H:)&nbsp;&nbsp;
		<?php echo $row['telephone'];?>
	<!--<label>Cell Phone:</label>-->
	&nbsp;&nbsp;
	(C:)
	&nbsp;&nbsp;
		<?php echo $row['cell_phone'];?>
	<!--<label>Email Address: </label>-->
	&nbsp;&nbsp;
	(Email:)
	&nbsp;&nbsp;
	<a href="mailto:<?php echo $row['email'];?>">	
		<?php echo $row['email'];?>
	</a>
	<br />
	<?php 
		if (!$row['boat_name'] == '' ){
	?>
		<br />
		Boat Information:
		<br>
		&nbsp;&nbsp;&nbsp;<label>Boat Name: </label>
			<?php echo $row['boat_name'];?>
		<br>
		&nbsp;&nbsp;&nbsp;<label>Home Port: </label>
			<?php echo $row['home_port'];?>
		<br>
		&nbsp;&nbsp;&nbsp;<label>Boat Type : </label>
			<?php echo $row['boat_type'];?>
		<br>
		&nbsp;&nbsp;&nbsp;<label>MMSI: </label>
			<?php echo $row['mmsi'];?>
	<?php 
		}
	?>
	<br>
	</fieldset>
	</div>
<!--	//*************************************************************************************-->
<?php 
	$query = "certificate='$certno' and year='$year'";
	$ary = $jobs->search_records_in_order($query);
	if (count($ary) > 0 ){
		$str = '';
		?>
		<div id="roster_jobs" >
		Squadron, District or National Jobs<br />
		<?php
		foreach($ary as $j){
			$str .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			$jc = $desc->get_record('jobcode',$j['jobcode']);
			$i = substr($j['jobcode'],0,1);
			switch ($i){
				case 1: 
					$str .= "National ";
					break;
				case 2:
					$str .= "District "; 
					break;
				case 3:
					$str .= "Squadron ";
					break;
				default:
					continue;
			}
			$str .= $jc['jdesc'].'<br>';
		}
		echo $str;
	}
	?>	
		</div>
	<?php 
	showTrailer();	
//*********************************************************
function format_data_row($row,$n,$dis,$hid){
$lodr = $GLOBALS['lodr'];
	$order = $lodr->search_records_in_order("row_mbr_data = $n","col_mbr_data" ) ;
	$line = "" ;
	foreach($order as $r){
		if (!$hid) $line .= "<label class= >" ;
		if (!$hid) $line .= $r['col_display_name'] ;
		if (!$hid) $line .= "</label> " ;
		switch($r['col_format']){
			case 'checkbox':
				$line .= format_checkbox_field($row,$n,$dis,$hid,$r);
				break;
			default:
				$line .= format_text_field($row,$n,$dis,$hid,$r);
		}
		
	}
	$line .= '<br>' ;
	return $line;
}
//*************************************************************
function format_text_field($row,$n,$dis,$hid,$r){
	$line = '';
	$line .= "<input type=" ;
	if ($hid)
		$line .= '"hidden"' ;
	else
		$line .= '"text"' ;
	$line .= " id=" ;
	$line .= '"' . str_replace("mbr_","",$r['col_name']) . '" ' ; 
	if ($dis) $line .= " readonly " ;
	$line .= 'name="' . $r['col_name'] . '" ' ;
	$line .= 'size="' . $r['col_display_width'] . '" ' ; 
	$line .= 'value="' . $row[$r['col_name']] .'" />' ;  
	return $line;	
}
//*************************************************************
function format_checkbox_field($row,$n,$dis,$hid,$r){
	$line = '';
	$line .= "<input type=" ;
	if ($hid)
		$line .= '"hidden"' ;
	else
		$line .= '"checkbox"' ;
	$line .= " id=" ;
	$line .= '"' . str_replace("mbr_","",$r['col_name']) . '" ' ; 
	if ($dis) $line .= " readonly " ;
	$line .= 'name="' . $r['col_name'] . '" ' ;
	$line .= 'size="' . $r['col_display_width'] . '" ' ;
	if ($row[$r['col_name']] == 1) 
		$line .= "checked " ; 
	$line .= ' />' ;  
	return $line;	
}

?>