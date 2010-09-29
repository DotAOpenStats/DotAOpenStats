<?php
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
*    along with DOTA OPENSTATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/

  include('header.php');
  
  $pageTitle = "$lang[site_name] | $lang[dota_games]";
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  
  $sql = "SELECT COUNT(*) FROM games LIMIT 1";
  
  $result = $db->query($sql);
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = $games_per_page;
  
  $order = 'id';
  if (isset($_GET['order']))
  {
  if ($_GET['order'] == 'game') {$order = ' LOWER(gamename) '.$sort.'';}
  if ($_GET['order'] == 'duration') {$order = ' duration '.$sort.'';}
  if ($_GET['order'] == 'type') {$order = ' type '.$sort.'';}
  if ($_GET['order'] == 'date') {$order = ' datetime '.$sort.'';}
  if ($_GET['order'] == 'creator') {$order = ' LOWER(creatorname) '.$sort.'';}
  }
  
  $sort = 'DESC';
  if (isset($_GET['sort']) AND $_GET['sort'] == 'desc')
  {$sort = 'asc'; $sortdb = 'DESC';} else {$sort = 'desc'; $sortdb = 'ASC';}

  include('pagination.php');

  $sql = "SELECT g.id, map, datetime, gamename, ownername, duration, creatorname, dg.winner, CASE WHEN(gamestate = '17') THEN 'PRIV' ELSE 'PUB' end AS type FROM games as g LEFT JOIN dotagames as dg ON g.id = dg.gameid WHERE map LIKE '%dota%' ORDER BY $order $sortdb LIMIT $offset, $rowsperpage";
  
  $result = $db->query($sql);
  
  
  echo "<div align='center'><table class='tableA'> 
  <tr>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=game&sort=$sort'>$lang[game]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=duration&sort=$sort'>$lang[duration]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=type&sort=$sort'>$lang[type]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=date&sort=$sort'>$lang[date]</a></div></th>
  <th><a href='{$_SERVER['PHP_SELF']}?order=creator&sort=$sort'>$lang[creator]</a></th>
  </tr>";
  
  while ($list = $db->fetch_array($result,'assoc')) {
        $gameid=$list["id"]; 
		$map=substr($list["map"], strripos($list["map"], '\\')+1);
		$type=$list["type"];
		$gametime=date($date_format,strtotime($list["datetime"]));
		$gamename=trim($list["gamename"]);
		$ownername=$list["ownername"];
		$duration=secondsToTime($list["duration"]);
		$creator=trim($list["creatorname"]);
		$creator2=trim(strtolower($list["creatorname"]));
		$winner=$list["winner"];
	echo "<tr class='row'>
	<td width='300px'><div align='left'><a href='game.php?gameid=$gameid'>$gamename</a></div></td>
	<td width='160px'><div align='left'>$duration</div></td>
	<td width='100px'><div align='left'>$type</div></td>
	<td width='200px'><div align='left'>$gametime</div></td>
	<td width='200px'><div align='left'><a href='user.php?u=$creator2'>$creator</a></div></td>
	</tr>";
		
		//echo "<br/>id:$gameid | gn: $gamename | m: $map | time: $duration | win: $winner";
  }
      echo "</table></div>";
	  
	  $sql = "SELECT MAX(duration), MIN(duration), AVG(duration), SUM(duration) 
	  FROM games 
	  WHERE LOWER(map) LIKE LOWER('%dota%') LIMIT 1";
	  
	  $result = $db->query($sql);
	  $row = $db->fetch_array($result,'assoc');

      $maxDuration=secondsToTime($row["MAX(duration)"]);
      $minDuration=secondsToTime($row["MIN(duration)"]);
      $avgDuration=secondsToTime($row["AVG(duration)"]);
 	  $totalDuration=secondsToTime($row["SUM(duration)"]);

  include('pagination.php');

     echo "<div align='center'><table class='tableA'><tr>
     <th width='33%' class='padLeft'>$lang[total_games] $numrows</th>
	 <th width='33%'>$lang[avg_duration] $avgDuration</th>
	 <th width='33%'>$lang[total_duration] $totalDuration</th>
	 </tr>
	 </table></div>
	 ";
  
  include('footer.php');
  ?>