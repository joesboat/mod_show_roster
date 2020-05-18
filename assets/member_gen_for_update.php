<?php
$loging = false;
include($_SERVER['CONTEXT_DOCUMENT_ROOT']."/applications/setupJoomlaAccess.php");
// require_once(JPATH_LIBRARIES."/USPSaccess/dbUSPS.php");
$GLOBALS['lodr'] = $lodr = new displayOrder($db_d5, '');
$sqds = $vhqab->getSquadronObject();
$jobs = $vhqab->getJobsObject();
// $blob = new tableD5Blobs($db_d5);
$blob = JoeFactory::getTable("tableD5Blobs",$db_d5);

$desc = $vhqab->getJobcodesObject();
	//$vhqab = JoeFactory::getLibrary("USPSd5tableVHQAB");
	if ($loging) log_it("Entering ".__FILE__,__LINE__);
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

	$str = "<input type='hidden' name='certificate' value='$certno'/>";
	$str .= "<input type='hidden' name='squad_no' value='$squad_no'/>";
	$str .= "<input type='hidden' name='address_id' value='$address_id'/>";
	$str .= "<input type='hidden' name='nn_prf' value='$nn_prf'/>";
	$str .= "<input type='hidden' name='nickname' value='$nickname'/>";
	$str .= "<input type='hidden' name='first_name' value='$first_name'/>";
	$str .= "<input type='hidden' name='last_name' value='$last_name'/>";
	if ($display_only) {
		$str .= "<h2>" ;
	}else{
		$str .= "<h2>Update for:  " ;
	}
	$str .= $vhqab->getMemberNameAndRank($certno,false)."</h2>";
	
	//*************************************************************************************
//	$str .= "<div id='roster_pic' width:70px; vertical-align:text-top;' >";
//	$str .= 	"<img src='$site_url/applications/utility/getMemberPicture.php?certificate=$certno&width=640' width='200' height='200'>";
//	$str .= "</div>";
	
	//*************************************************************************************
	$str .= "<div id='roster_data' width:700px; vertical-align:text-top;'>";
	$str .= '<fieldset id="main">';
	$str .= 'Location:<br>'; 
	$str .= format_data_row($row, 2, $display_only, false);
	$str .= format_data_row($row, 3, $display_only, false);
	$str .= 'Contact Information:<br>';
	$str .= format_data_row($row, 4, $display_only, false);
	$str .= 'Boat Information:<br>';
	$str .= format_data_row($row, 5, $display_only, false);
	$str .= '</fieldset>';
	if (!$display_only){
		$str .= '<p><input type="submit" name="command" value="Update" ></input>&nbsp;&nbsp;&nbsp;Updates to your contact information are saved on the D5 site and also sent to your squadron commander and roster chair.</p>';
	}	
	$str .= "</div>";
	//*************************************************************************************
	$str .= '<div id="roster_jobs" >';
	$str .= 'Squadron: &nbsp;&nbsp;'.$vhqab->getSquadronName($row['squad_no']).'<br/>';
	$str .= 'District & National Jobs:<br/>';
	$query = "certificate='$certno' and year='$year'";
	$ary = $jobs->search_records_in_order($query);
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
	if (!$display_only){
		$str .= "<p>You may upload a new picture.  Pictures uploaded will overwrite any exisiting entry.  Proceed by using the <input type='file' name='filename' size='50' value='' /> button to identify the .jpg picture file.  Then select <input type='submit' name='command' value='Upload'/>.</p>";
	}
	//$str .= get_search_box();
	$str .= "</div>";
	$hdr = $vhqab->getSquadronName($row['squad_no']);
	showHeader($hdr, '?'.htmlspecialchars(SID));
		echo $str;
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