<?PHP
    $sql = getSentScourWon();
    $result = $db->query($sql);
    $row = $db->fetch_array($result,'assoc');

    $_totals = $row["sentinelWon"]+$row["scourgeWon"]+$row["draw"];
    $_sentWon = number_format($row["sentinelWon"],"0",".",",");
	$_sentWon2 = $row["sentinelWon"];
    $_scourWon = number_format($row["scourgeWon"],"0",".",",");
	$_scourWon2 = $row["scourgeWon"];
    $_draw = number_format($row["draw"],"0",".",",");
  
    $_sentPerc = ROUND(($_sentWon2/$_totals)*100,1);
    $_scourPerc = ROUND(($_scourWon2/$_totals)*100,1);
    $_drawPerc = ROUND(($_draw/$_totals)*100,1);
	
	 //Get total kills
	 $sql = getSentScourgeKills(1);
	 $result = $db->query($sql);
	 $row = $db->fetch_array($result,'assoc');
	 $TotSentinelKills = number_format($row["TotalKills"],"0",".",",");
	 
	 $sql = getSentScourgeKills(2);
	 $result = $db->query($sql);
	 $row = $db->fetch_array($result,'assoc');
	 $TotScourgeKills = number_format($row["TotalKills"],"0",".",",");
	 
	 //Get total creep kills
	 $sql = getSentScourgeCreepKills(1);
	 $result = $db->query($sql);
	 $row = $db->fetch_array($result,'assoc');
	 $TotSentinelCreepKills = number_format($row["TotalKills"],"0",".",",");
	 
	 $sql = getSentScourgeCreepKills(2);
	 $result = $db->query($sql);
	 $row = $db->fetch_array($result,'assoc');
	 $TotScourgeCreepKills = number_format($row["TotalKills"],"0",".",",");
	 
	 //Get total creep denies
	 $sql = getSentScourgeCreepDenies(1);
	 $result = $db->query($sql);
	 $row = $db->fetch_array($result,'assoc');
	 $TotSentinelCreepDenies = number_format($row["TotalKills"],"0",".",",");
	 
	 $sql = getSentScourgeCreepDenies(2);
	 $result = $db->query($sql);
	 $row = $db->fetch_array($result,'assoc');
	 $TotScourgeCreepDenies = number_format($row["TotalKills"],"0",".",",");
	 
	 //DURATION AND TOTAL GAMES
	  $sql = "SELECT MAX(duration), MIN(duration), AVG(duration), SUM(duration) 
	  FROM games 
	  WHERE LOWER(map) LIKE LOWER('%dota%') LIMIT 1";
	  
	  $result = $db->query($sql);
	  $row = $db->fetch_array($result,'assoc');

      $maxDuration=secondsToTime($row["MAX(duration)"]);
      $minDuration=secondsToTime($row["MIN(duration)"]);
      $avgDuration=secondsToTime($row["AVG(duration)"]);
 	  $totalDuration=secondsToTime($row["SUM(duration)"]);
	  $totalGames = number_format($numrows,"0",".",",");

  echo "<div align='center'><table class='tableA'><tr>
     <th class='padLeft'></th>
     <th>$lang[total_wins]</th>
	 <th>$lang[total_kills]</th>
	 <th>$lang[total_creeps]</th>
	 <th>$lang[total_creep_denies]</th>
	 <th></th>
	 </tr>
	 <tr class='row'>
	 <td class='padLeft' width='100px'><span class='sentinel'><b>$lang[Sentinel]</b></span></td>
	 <td width='180px'>$_sentWon ($_sentPerc%)</td>
	 <td width='180px'>$TotSentinelKills</td>
	 <td width='200px'>$TotSentinelCreepKills</td>
	 <td width='200px'>$TotSentinelCreepDenies</td>
	 <td></td>
	 
	 <tr class='row'>
	 <td class='padLeft' width='100px'><span class='scourge'><b>$lang[Scourge]</b></span></td>
	 <td width='180px'>$_scourWon ($_scourPerc%)</td>
	 <td width='180px'>$TotScourgeKills</td>
	 <td width='200px'>$TotScourgeCreepKills</td>
	 <td width='200px'>$TotScourgeCreepDenies</td>
	 <td></td>
	 
	 <tr class='row'>
	 <td class='padLeft' width='100px'><span class='GamesDraw'>$_lang[draw_games]</span></td>
	 <td width='180px'>$_draw ($_drawPerc%)</th>
	 <td>-</td>
	 <td>-</td>
	 <td>-</td>
	 <td></td>
	 </tr>
	 </table>";
	 

     echo "
	 <table class='tableA'><tr>
     <th width='33%' class='padLeft'>$lang[total_games] $numrows</th>
	 <th width='33%'>$lang[avg_duration] $avgDuration</th>
	 <th width='33%'>$lang[total_duration] $totalDuration</th>
	 </tr>
	 </table></div>";
	 
	 ?>