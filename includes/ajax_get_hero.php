<?PHP
/*********************************************
<!-- 
*   	DOTA OPENSTATS
*   
*	Developers: Ivan.
*	Contact: ivan.anta@gmail.com - Ivan
*
*	
*	Please see http://openstats.iz.rs
*	and post your webpage there, so I know who's using it.
*
*	Files downloaded from http://openstats.iz.rs
*
*	Copyright (C) 2010  Ivan
*
*
*	This file is part of DOTA OPENSTATS.
*
* 
*	 DOTA OPENSTATS is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    DOTA OPEN STATS is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with DOTA OPEN STATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/
     require_once ('../config.php');
	 require_once("../lang/$default_language.php");
	 require_once('../includes/class.database.php');
	 require_once('../includes/common.php');
	 require_once('../includes/db_connect.php');
	 
	 if($_GET)
	 {
	 
   $heroid = safeEscape($_GET['searchterm']);

   $sql = getHero($heroid);
	
	$sql2 = getHeroInfo($heroid, $minPlayedRatio, $minPlayedRatio);
	
	$result = $db->query($sql);
	if ($db->num_rows($result)<=0) {echo "";die;}
	
	$result2 = $db->query($sql2);
	
	$list = $db->fetch_array($result,'assoc');
	$row = $db->fetch_array($result2,'assoc');
		
		$totgames=$row["totgames"];
		$wins=$row["wins"];
		$losses=$row["losses"];
		$winratio=ROUND($row["winratio"],2);
		$kills=ROUND($row["kills"],2);
		$deaths=ROUND($row["deaths"],2);
		$assists=ROUND($row["assists"],2);
		$kdratio=ROUND($row["kdratio"],2);
		$creepkills=ROUND($row["creepkills"],2);
		$creepdenies=ROUND($row["creepdenies"],2);
		$neutralkills=ROUND($row["neutralkills"],2);
		$towerkills=ROUND($row["towerkills"],2);
		$raxkills=ROUND($row["raxkills"],2);
		$courierkills=ROUND($row["courierkills"],2);
		$summary=$row["summary"];
		$stats=$row["stats"];
		$skills=$row["skills"];
	
	
	    $hero=convEnt($list["description"]);
		$hid=convEnt($list["original"]);
		$summ=convEnt($list["summary"]);
		$stats=convEnt($list["stats"]);
		$skills=convEnt($list["skills"]);
		
		$summ = str_replace("’","&rsquo;",$summ );
		$summ = str_replace("…","&hellip;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
		
		$stats = str_replace("Strength","<img style='vertical-align: middle;' alt='' src='./img/strength.gif' border=0 />Strength",$stats );
		
		$stats = str_replace("Agility","<img style='vertical-align: middle;' alt='' src='./img/agility.gif' border=0 />Agility",$stats );
		
		$stats = str_replace("Intelligence","<img style='vertical-align: middle;' alt='' src='./img/intelligence.gif' border=0 />Intelligence",$stats );
		
		$summ = str_replace("’","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		
		echo "<TABLE><TR><TD style='height:24px;'>  <B>$hero</B> $lang[info]</TD></TR></TABLE><br/>";
		
		
		echo "<div align='center'>
		<table style='width:75%;'><tr>

		<td width='70%' valign='top' style='text-align:left;padding:12px;'>

		<br/><a href='hero.php?hero=$hid'><img style='vertical-align: middle;' width='64px' height='64px' alt='' src='./img/heroes/$hid.gif' border=0/></a> <b>$hero</b><br/><div align='justify'>$summ</div>
		<br/>$stats</br><br/><br/>$skills
		</td>

		<td valign='top'>
		
		      <table style='margin-top:4px;width:96%;border: 0px solid #000' >
		      <tr>
		      <td width='60px'>$lang[wins]</td><td>$wins</td>
		      <td width='60px'>$lang[games]</td><td>$totgames</td>
		      <tr>
		      <td width='60px'>$lang[losses]</td><td>$losses</td>
		      <td width='60px'>$lang[w_l]</td><td>$winratio</td>
		      </tr>
		
		      <tr>
		      <td width='60px'>$lang[kills]</td><td>$kills</td>
		      <td width='60px'>$lang[assists]</td><td>$assists</td>
		      </tr>
		
		      <tr>
		      <td width='60px'>$lang[deaths]</td><td>$deaths</td>
		      <td width='60px'>$lang[kd]</td><td>$kdratio</td>
		      </tr>
		
		      <tr>
		      <td width='60px'>$lang[creeps]</td><td>$creepkills</td>
		      <td width='60px'>$lang[neutrals]</td><td>$neutralkills</td>
		      </tr>
		
		      <tr>
		      <td width='60px'>$lang[denies]</td><td>$creepdenies</td>
		      <td width='60px'>$lang[towers]</td><td>$towerkills</td>
		      </tr>
		
		      <tr>
		      <td width='60px'>$lang[rax]</td><td>$raxkills</td>
		      <td width='60px'>$lang[couriers]</td><td>$courierkills</td>
		      </tr>
              </tr>
			  </table>
			  
	 </td> </table>";
    }
	
	
	
	
	
	else {echo "Direct access not allowed!"; die;}
	
	?>
	