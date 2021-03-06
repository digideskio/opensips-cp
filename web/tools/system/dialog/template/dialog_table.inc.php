<?php
 /*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


function display_dialog_table($message, $mi_type){

	unset($entry);

	if ($mi_type != "json") {
		$temp = explode ("dialog:: ",$message);
		$recno = count($temp);
		for ($i=1;$i<$recno;$i++) {
		
			$row_style = ($i%2==1)?"rowOdd":"rowEven";
		
			preg_match_all('/hash=\d+:\d+\s+/',$temp[$i],$hash);
			$temp[$i] = substr($temp[$i],strlen($hash[0][0]),strlen($temp[$i]));
			$temptemp = explode ("\n",$temp[$i]);

			for ($j=0;$j<count($temptemp);$j++){
				$tmp = explode (":: ",$temptemp[$j]);
				$res[trim($tmp[0])]=$tmp[1];
			}
			unset($temptemp);

			$hashtemp = explode ("=",$hash[0][0]);
			$hashie = explode(":",$hashtemp[1]);
			$entry[$i]['h_entry'] = $hashie[0];
			$entry[$i]['h_id'] = $hashie[1];

			if(!$_SESSION['read_only']){
				if ($res['state']==3 || $res['state']==4)
       		     	$delete_link='<a href="'.$page_name.'?action=delete&h_id='.$entry[$i]['h_id'].'&h_entry='.$entry[$i]['h_entry'].'" onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
				else
					$delete_link = "n/a";
        	}

			echo '<tr>';

			$state_values = array(1 => "Unconfirmed Call", 2 => "Early Call", 3 => "Confirmed Not Acknoledged Call", 4 => "Confirmed Call", 5 => "Deleted Call");
			$entry[$i]['state'] = $state_values[$res['state']];

			//timestart
			$entry[$i]['start_time'] = date("Y-m-d H:i:s",$res['timestart']);

			//timeout time
			$entry[$i]['timeout_time'] = date("Y-m-d H:i:s",$res['timeout']);

			//toURI
			$entry[$i]['toURI']=$res['to_uri'];

			//fromURI
			$entry[$i]['fromURI']=$res['from_uri'];

			//callID
			$entry[$i]['callID']=$res['callid'];

			unset($res);

			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["callID"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["fromURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["toURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["start_time"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["timeout_time"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["state"]."</td>";

			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
			}
	  		echo '</tr>';
		}
		unset($entry);
	}
	else {
		for ($i=1;$i<count($message);$i++) {
		
			$row_style = ($i%2==1)?"rowOdd":"rowEven";
	
			$temp_hash = explode(":",$message[$i]['attributes']['hash']);

			$entry[$i]['h_entry'] = $temp_hash[0];
			$entry[$i]['h_id'] = $temp_hash[1];

			if(!$_SESSION['read_only']){
				if ($message[$i]['children']['state']<5)
       		     	$delete_link='<a href="'.$page_name.'?action=delete&h_id='.$entry[$i]['h_id'].'&h_entry='.$entry[$i]['h_entry'].'" onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
				else
					$delete_link = "n/a";
        	}

			echo '<tr>';

			$state_values = array(1 => "Unconfirmed Call", 2 => "Early Call", 3 => "Confirmed Not Acknoledged Call", 4 => "Confirmed Call", 5 => "Deleted Call");
			$entry[$i]['state'] = $state_values[$message[$i]['children']['state']];

			//timestart
			$entry[$i]['start_time'] = date("Y-m-d H:i:s",$message[$i]['children']['timestart']);

			//expire time
			$entry[$i]['expire_time'] = date("Y-m-d H:i:s",$message[$i]['children']['expire']);

			//toURI
			$entry[$i]['toURI']=$message[$i]['children']['to_uri'];

			//fromURI
			$entry[$i]['fromURI']=$message[$i]['children']['from_uri'];

			//callID
			$entry[$i]['callID']=$message[$i]['children']['callid'];

			unset($res);

			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["callID"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["fromURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["toURI"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["start_time"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["timeout_time"]."</td>";
			echo "<td class=".$row_style.">&nbsp;".$entry[$i]["state"]."</td>";

			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
			}
	  		echo '</tr>';
		}
		unset($entry);
	}
}

?>

