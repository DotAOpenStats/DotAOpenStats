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

  function safeEscape($text)
  {
  $text = str_replace ("'","",$text);
  $text = str_replace ('"','',$text);
  $text = strip_tags(mysql_real_escape_string($text));
  return $text;
  }
  
  
  function EscapeStr($text)
  {
  $text = mysql_real_escape_string($text);
  return $text;
  }
  
  
  function secondsToTime($seconds)//Returns the time like 1:43:32
{
	$hours = floor($seconds/3600);
	$secondsRemaining = $seconds % 3600;
	
	$minutes = floor($secondsRemaining/60);
	$seconds_left = $secondsRemaining % 60;
	
	if($hours != 0)
	{
		if(strlen($minutes) == 1)
		{
		$minutes = "0".$minutes;
		}
		if(strlen($seconds_left) == 1)
		{
		$seconds_left = "0".$seconds_left;
		}
		return $hours."h ".$minutes."m ".$seconds_left."s";
	}
	else
	{
		if(strlen($seconds_left) == 1)
		{
		$seconds_left = "0".$seconds_left;
		}
		return $minutes."m ".$seconds_left."s";
	}
}

   function millisecondsToTime($milliseconds)//returns the time like 5.2 (5 seconds, 200 milliseconds)
{
	$return="";
	$return2="";
     // get the seconds
	$seconds = floor($milliseconds / 1000) ;
	$milliseconds = $milliseconds % 1000;
	$milliseconds = round($milliseconds/100,0);
	
	// get the minutes
	$minutes = floor($seconds / 60) ;
	$seconds_left = $seconds % 60 ;

	// get the hours
	$hours = floor($minutes / 60) ;
	$minutes_left = $minutes % 60 ;
// A little unneccasary with minutes and hours,,  but HEY  everythings possible
	if($hours)
	{
		$return ="$hours"."h ";
	}
	if($minutes_left)
	{
		$return2 ="$minutes_left"."m ";
	}
return $return.$return2.$seconds_left.".".$milliseconds;
}  

///////////////////////////////////////////////////////////////
     function replayDuration($seconds)
{
	$minutes = floor($seconds/60);
	$seconds_left = $seconds % 60;
	
	if(strlen($seconds_left) == 1)
	{
	$seconds_left = "0".$seconds_left;
	}
	return $minutes."m".$seconds_left."s";
}

   
    function getTeam($color)
{
	switch ($color) {
		case 'red': return 0;
		case 'blue': return 1;
		case 'teal': return 1;
		case 'purple': return 1;
		case 'yellow': return 1;
		case 'orange': return 1;
		case 'green': return 0;
		case 'pink': return 2;
		case 'gray': return 2;
		case 'light-blue': return 2;
		case 'dark-green': return 2;
		case 'brown': return 2;
		case 'observer': return 0;
	}
}
     function getCountTops($games){
	 $count = "
  SELECT COUNT(*) as count 
  FROM( 
       SELECT name 
	   FROM gameplayers as gp, 
	   dotagames as dg, 
	   games as ga,
	   dotaplayers as dp 
	   WHERE dg.winner <> 0 
	   AND dp.gameid = gp.gameid 
	   AND dg.gameid = dp.gameid 
	   AND dp.gameid = ga.id 
	   AND gp.gameid = dg.gameid 
	   AND gp.colour = dp.colour 
	   GROUP BY gp.name having count(*) >= $games) as h
  LIMIT 1";
  
  return $count;
	 }
	 
      //////////////////////////////
	 //       GET TOPS           //
    //////////////////////////////
     function getTops($scoreFormula,$minPlayedRatio,$games,$order,$sortdb,$offset,$rowsperpage,$DBScore,$ScoreMethod,$ScoreWins,$ScoreLosses,$ScoreDisc,$ScoreStart,$HideBannedUsersOnTop){
	 if ($DBScore == 0)
    {//($scoreFormula) as totalscore
	    if ($ScoreMethod == 2) 
		{$scoreFormula = "$ScoreStart + (wins*$ScoreWins) + (losses*$ScoreLosses) + (disc*$ScoreDisc)";}
		
	    if ($HideBannedUsersOnTop == 1) {$_sql = "AND bans.name is null";} else {$_sql = "";}
		
	$text = "
	SELECT *, 
	case when (kills = 0) then 0 
	when (deaths = 0) then 1000 
	else ((kills*1.0)/(deaths*1.0)) end as killdeathratio, 
	($scoreFormula) as totalscore 
	 FROM ( 
	 SELECT 
	 gp.name as name, 
	 bans.name as banname, 
	 avg(dp.courierkills) as courierkills, 
	 avg(dp.raxkills) as raxkills,
	 avg(dp.towerkills) as towerkills, 
	 avg(dp.assists) as assists, 
	 avg(dp.creepdenies) as creepdenies, 
	 avg(dp.creepkills) as creepkills,
	 avg(dp.neutralkills) as neutralkills, 
	 avg(dp.deaths) as deaths, 
	 avg(dp.kills) as kills, 
	 SUM(dp.kills) as totkills,
	 SUM(dp.deaths) as totdeaths,
	 COUNT(*) as totgames, 
	 case when (kills = 0) then 0 
	 when (deaths = 0) then 1000 
	 else ((kills*1.0)/(deaths*1.0)) 
	 end as killdeathratio,
	 SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
	 or (dg.winner = 2 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 
	 else 0 end) as wins, 
     SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
	 or (dg.winner = 1 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) 
	 then 1 else 0 end) as losses
	 
	 , SUM(
	 (gp.`leftreason` LIKE ('%has lost the connection%'))  
	 OR (gp.`leftreason` LIKE ('%was dropped%')) 
	 OR (gp.`leftreason` LIKE ('%Lagged out%')) 
	 OR (gp.`leftreason` LIKE ('%Dropped due to%'))
	 ) as disc 
	 
	 
     FROM gameplayers as gp 
     LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
     LEFT JOIN dotaplayers as dp ON dg.gameid = dp.gameid 
	 AND gp.colour = dp.colour 
	 AND dp.newcolour <> 12 
	 AND dp.newcolour <> 6
	 LEFT JOIN games as ga ON dp.gameid = ga.id 
	 LEFT JOIN bans on bans.name = gp.name 
	 WHERE dg.winner <>0 $_sql 
	 GROUP BY gp.name having totgames >= $games) as i 
	 ORDER BY $order $sortdb, name $sortdb 
	 LIMIT $offset, $rowsperpage";}
	 else
	 {
	 if ($HideBannedUsersOnTop == 1) {$_sql = "AND bans.name is null";} else {$_sql = "";}
	 
	 $text = "
	 SELECT *, 
	 case when (kills = 0) then 0 
	 when (deaths = 0) then 1000 
	 else ((kills*1.0)/(deaths*1.0)) 
	 end as killdeathratio 
	 FROM (
          SELECT gp.name as name, 
		  bans.name as banname, 
		  avg(dp.courierkills) as courierkills, 
		  avg(dp.raxkills) as raxkills,
		  avg(dp.towerkills) as towerkills, 
		  avg(dp.assists) as assists, 
		  avg(dp.creepdenies) as creepdenies, 
		  avg(dp.creepkills) as creepkills,
		  avg(dp.neutralkills) as neutralkills, 
		  avg(dp.deaths) as deaths, 
		  avg(dp.kills) as kills, 
		  SUM(dp.kills) as totkills,
	      SUM(dp.deaths) as totdeaths,
		  sc.score as totalscore, 
		  COUNT(*) as totgames, 
		  SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
		  or (dg.winner = 2 and dp.newcolour > 6)) 
		  AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as wins, 
		  SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
		  or (dg.winner = 1 and dp.newcolour > 6)) 
		  AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as losses
		  
		  , SUM(
	 (gp.`leftreason` LIKE ('%has lost the connection%'))  
	 OR (gp.`leftreason` LIKE ('%was dropped%')) 
	 OR (gp.`leftreason` LIKE ('%Lagged out%')) 
	 OR (gp.`leftreason` LIKE ('%Dropped due to%'))
	 ) as disc 
		  
		  FROM gameplayers as gp 
		  LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
		  LEFT JOIN dotaplayers as dp ON dg.gameid = dp.gameid 
		  AND gp.colour = dp.colour 
		  AND dp.newcolour <> 12 
		  AND dp.newcolour <> 6
		  LEFT JOIN games as ga ON dp.gameid = ga.id 
		  LEFT JOIN scores as sc ON sc.name = gp.name 
		  LEFT JOIN bans on bans.name = gp.name 
		  WHERE dg.winner <> 0 $_sql
		  GROUP BY gp.name having totgames >= $games) as i 
	 ORDER BY $order $sortdb, name $sortdb 
	 LIMIT $offset, $rowsperpage";
	 }
	 
	 return $text;
}  
      ///////////////
     // GAME INFO //
    ///////////////
    function getGameInfo($gid){
	$sql = "
	   SELECT winner, dp.gameid, gp.colour, newcolour, original as hero, description, kills, deaths, assists, creepkills, creepdenies, neutralkills, towerkills, gold,  raxkills, courierkills, 
	   item1, item2, item3, item4, item5, item6, 
	   it1.icon as itemicon1, 
	   it2.icon as itemicon2, 
	   it3.icon as itemicon3, 
	   it4.icon as itemicon4, 
	   it5.icon as itemicon5, 
	   it6.icon as itemicon6, 
	   it1.name as itemname1, 
	   it2.name as itemname2, 
	   it3.name as itemname3, 
	   it4.name as itemname4, 
	   it5.name as itemname5, 
	   it6.name as itemname6, 
	   leftreason, 
	   gp.left, 
	   gp.name as name, 
	   b.name as banname 
	   FROM dotaplayers AS dp 
	   LEFT JOIN gameplayers AS gp ON gp.gameid = dp.gameid and dp.colour = gp.colour 
	   LEFT JOIN dotagames AS dg ON dg.gameid = dp.gameid 
	   LEFT JOIN games AS g ON g.id = dp.gameid 
	   LEFT JOIN bans as b ON gp.name = b.name
	   LEFT JOIN heroes as f ON hero = heroid
	   LEFT JOIN items as it1 ON it1.itemid = item1
	   LEFT JOIN items as it2 ON it2.itemid = item2
	   LEFT JOIN items as it3 ON it3.itemid = item3
	   LEFT JOIN items as it4 ON it4.itemid = item4
	   LEFT JOIN items as it5 ON it5.itemid = item5
	   LEFT JOIN items as it6 ON it6.itemid = item6
	   WHERE dp.gameid=$gid 
	   ORDER BY newcolour";
	   return $sql;
	}
	
	function GetScoreBefore($scoreFormula,$minPlayedRatio,$gid,$name3,$minGamesPlayed,$gmtime) {
	$sql = "SELECT *, case when (kills = 0) then 0 when (deaths = 0) then 1000 else ((kills*1.0)/(deaths*1.0)) end as killdeathratio, ($scoreFormula) as totalscore 
	 FROM ( 
	 SELECT gp.name as name, bans.name as banname, avg(dp.courierkills) as courierkills, avg(dp.raxkills) as raxkills,
avg(dp.towerkills) as towerkills, avg(dp.assists) as assists, avg(dp.creepdenies) as creepdenies, avg(dp.creepkills) as creepkills,
avg(dp.neutralkills) as neutralkills, avg(dp.deaths) as deaths, avg(dp.kills) as kills, count(*) as totgames, 
case when (kills = 0) then 0 when (deaths = 0) then 1000 else ((kills*1.0)/(deaths*1.0)) end as killdeathratio,
SUM(case when(((dg.winner = 1 and dp.newcolour < 6) or (dg.winner = 2 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as wins, 
     SUM(case when(((dg.winner = 2 and dp.newcolour < 6) or (dg.winner = 1 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as losses
     FROM gameplayers as gp 
     LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid AND gp.gameid != $gid
     LEFT JOIN dotaplayers as dp ON dg.gameid = dp.gameid AND dg.gameid != $gid
	 AND gp.colour = dp.colour 
	 AND dp.newcolour <> 12 
	 AND dp.newcolour <> 6
	 LEFT JOIN games as ga ON dp.gameid = ga.id
	 LEFT JOIN bans on bans.name = gp.name
	 WHERE dg.winner <> 0  AND gp.name = '$name3' AND ga.datetime<='$gmtime'
	 GROUP BY gp.name) as i
	 ORDER BY totalscore ASC LIMIT 1";
	 return $sql;
	}
	
	function GetScoreAfter($scoreFormula,$minPlayedRatio,$name3,$minGamesPlayed,$gmtime) {
	$sql = "SELECT *, case when (kills = 0) then 0 when (deaths = 0) then 1000 else ((kills*1.0)/(deaths*1.0)) end as killdeathratio, ($scoreFormula) as totalscore 
	 FROM ( 
	 SELECT gp.name as name, bans.name as banname, avg(dp.courierkills) as courierkills, avg(dp.raxkills) as raxkills,
avg(dp.towerkills) as towerkills, avg(dp.assists) as assists, avg(dp.creepdenies) as creepdenies, avg(dp.creepkills) as creepkills,
avg(dp.neutralkills) as neutralkills, avg(dp.deaths) as deaths, avg(dp.kills) as kills, count(*) as totgames, 
case when (kills = 0) then 0 when (deaths = 0) then 1000 else ((kills*1.0)/(deaths*1.0)) end as killdeathratio,
SUM(case when(((dg.winner = 1 and dp.newcolour < 6) or (dg.winner = 2 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as wins, 
     SUM(case when(((dg.winner = 2 and dp.newcolour < 6) or (dg.winner = 1 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as losses
     FROM gameplayers as gp 
     LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
     LEFT JOIN dotaplayers as dp ON dg.gameid = dp.gameid 
	 AND gp.colour = dp.colour 
	 AND dp.newcolour <> 12 
	 AND dp.newcolour <> 6
	 LEFT JOIN games as ga ON dp.gameid = ga.id
	 LEFT JOIN bans on bans.name = gp.name
	 WHERE dg.winner <> 0  AND gp.name = '$name3' AND ga.datetime<='$gmtime'
	 GROUP BY gp.name having totgames >= $minGamesPlayed) as i 
	 ORDER BY totalscore ASC LIMIT 1";
	 return $sql;
	}
	
	function getMostUsedHeroByItem($heroid, $itemid, $tot, $itemName ) {

	//IMPROVED QUERY
    //Aghanim's checks - Group all Agha items.
    if (!strstr($itemName,"Aghanim")){
	$sql = "SELECT COUNT(*) as total, dp.item1,dp.item2, dp.item3, dp.item4, dp.item5, dp.item6, dp.hero, h.heroid, h.description as heroname 
	FROM dotaplayers as dp 
	LEFT JOIN heroes as h ON h.heroid = dp.hero AND h.summary != '-'
	WHERE dp.hero = '$heroid' AND dp.hero !=''
	OR dp.item1 = '$itemid' 
	OR dp.item2 = '$itemid'  
	OR dp.item3 = '$itemid'
	OR dp.item4 = '$itemid'
	OR dp.item5 = '$itemid'
	OR dp.item6 = '$itemid' 
	GROUP BY dp.hero 
	ORDER BY count(*) DESC LIMIT $tot";}
	else
	{
	//Now check for that problematic Aghanim's xD
	$sql = "SELECT COUNT(*) as total, dp.item1,dp.item2, dp.item3, dp.item4, dp.item5, dp.item6, dp.hero, h.heroid, h.description as heroname, it.name, it.itemid
	FROM dotaplayers as dp 
	LEFT JOIN heroes as h ON h.heroid = dp.hero AND h.summary != '-'
	LEFT JOIN items as it ON it.name LIKE ('%Aghanim%') AND  it.item_info!='' AND (it.itemid = dp.item1)  
	LEFT JOIN items as it2 ON it2.name LIKE ('%Aghanim%') AND  it2.item_info!='' AND (it2.itemid = dp.item2) 
	LEFT JOIN items as it3 ON it3.name LIKE ('%Aghanim%') AND  it3.item_info!='' AND (it3.itemid = dp.item3) 
	LEFT JOIN items as it4 ON it4.name LIKE ('%Aghanim%') AND  it4.item_info!='' AND (it4.itemid = dp.item4) 
	LEFT JOIN items as it5 ON it5.name LIKE ('%Aghanim%') AND  it5.item_info!='' AND (it5.itemid = dp.item5) 
	LEFT JOIN items as it6 ON it6.name LIKE ('%Aghanim%') AND  it6.item_info!='' AND (it6.itemid = dp.item6) 
	WHERE dp.hero = '$heroid' AND dp.hero !='' 
    OR it.name LIKE ('%Aghanim%') OR it2.name LIKE ('%Aghanim%') OR it3.name LIKE ('%Aghanim%') 
	OR it4.name LIKE ('%Aghanim%') OR it5.name LIKE ('%Aghanim%') OR it6.name LIKE ('%Aghanim%')
	GROUP BY dp.hero 
	ORDER BY count(*) DESC,  dp.hero DESC LIMIT $tot
	";}
	
	
	
	return $sql;
	}
	
	
    function getMonthName($month,$ljan,$lfeb,$lmar,$lapr,$lmay,$ljun,$ljul,$laug,$lsep,$loct,$lnov,$ldec) {
	if ($month == 1) $rmonth = $ljan;
	if ($month == 2) $rmonth = $lfeb;
	if ($month == 3) $rmonth = $lmar;
	if ($month == 4) $rmonth = $lapr;
	if ($month == 5) $rmonth = $lmay;
	if ($month == 6) $rmonth = $ljun;
	if ($month == 7) $rmonth = $ljul;
	if ($month == 8) $rmonth = $laug;
	if ($month == 9) $rmonth = $lsep;
	if ($month == 10) $rmonth = $loct;
	if ($month == 11) $rmonth = $lnov;
	if ($month == 12) $rmonth = $ldec;
	
	return $rmonth;
	
	}
	
	function getDays($m){
	if ($m == 1) return 31;
	if ($m == 2) return 28;
	if ($m == 3) return 31;
	if ($m == 4) return 30;
	if ($m == 5) return 31;
	if ($m == 6) return 30;
	if ($m == 7) return 31;
	if ($m == 8) return 30;
	if ($m == 9) return 31;
	if ($m == 10) return 30;
	if ($m == 11) return 31;
	if ($m == 12) return 30;
	}

   
///////////////////////
    function getHero($heroid) {
	$text = "SELECT * FROM heroes WHERE original='$heroid' AND summary!= '-' LIMIT 1";
	return $text;
	}

    function getHeroInfo($heroid, $minPlayedRatio, $minPlayedRatio) {
	$text = "SELECT *, 
	(kills*1.0/deaths) as kdratio, 
	(wins*1.0/losses) as winratio, 
	summary, 
	skills, 
	stats 
	FROM 
	(SELECT count(*) as totgames, 
	original,
	SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
	or (dg.winner = 2 and dp.newcolour > 6)) 
	AND gp.`left`/g.duration >= $minPlayedRatio) then 1 else 0 end) as wins, 
	SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
	or (dg.winner = 1 and dp.newcolour > 6)) 
	AND gp.`left`/g.duration >= $minPlayedRatio) then 1 else 0 end) as losses, 
	SUM(kills) as kills, 
	SUM(deaths) as deaths, 
	SUM(assists) as assists, 
	SUM(creepkills) as creepkills, 
	SUM(creepdenies) as creepdenies, 
	SUM(neutralkills) as neutralkills, 
	SUM(towerkills) as towerkills, 
	SUM(raxkills) as raxkills, 
	SUM(courierkills) as courierkills
	FROM dotaplayers AS dp 
	LEFT JOIN heroes as b ON hero = heroid 
	LEFT JOIN dotagames as dg ON dg.gameid = dp.gameid
	LEFT JOIN gameplayers as gp ON gp.gameid = dp.gameid and dp.colour = gp.colour 
	LEFT JOIN games as g ON gp.gameid = g.id 
	WHERE original='$heroid' 
	GROUP BY original) as y 
	LEFT JOIN heroes as h ON y.original = h.heroid LIMIT 1";
	
	return $text;
	}
	
	function getHeroHistoryCount($heroid) {
	$text = "
	SELECT COUNT(*) AS  count 
	 FROM (
	       SELECT name 
	       FROM dotaplayers AS dp 
	       LEFT JOIN gameplayers AS gp ON gp.gameid = dp.gameid and dp.colour = gp.colour 
	       LEFT JOIN dotagames AS dg ON dg.gameid = dp.gameid 
	       LEFT JOIN games AS g ON g.id = dp.gameid 
	       LEFT JOIN heroes as e ON dp.hero = heroid 
	       WHERE heroid = '$heroid')as t LIMIT 1";
 
	return $text;
	}
	
	//HERO MOST USED ITEMS
	function getHeroItem1($heroid) {
	$sql = "SELECT count(*) as total, dotaplayers.item1, items.icon , items.name , items.itemid 
	FROM dotaplayers 
	LEFT JOIN items ON items.itemid = dotaplayers.item1
	WHERE hero = '$heroid' 
	AND dotaplayers.item1 != '\0\0\0\0' 
    AND dotaplayers.item1 != '' 
	GROUP BY item1 having count(*) > 1 
	ORDER BY count(*) DESC LIMIT 2";
	return $sql;
	}
	function getHeroItem2($heroid,$mostItem1,$mostItem11) {
	$sql = "SELECT count(*) as total, dotaplayers.item2, items.icon , items.name , items.itemid
	FROM dotaplayers 
	LEFT JOIN items ON items.itemid = dotaplayers.item2
	WHERE hero = '$heroid' 
	AND dotaplayers.item2 != '\0\0\0\0' 
    AND dotaplayers.item2 != '' 
	AND dotaplayers.item2 != '$mostItem1' AND dotaplayers.item2 != '$mostItem11'
	GROUP BY item2 having count(*) > 1 
	ORDER BY count(*) DESC LIMIT 2";
	return $sql;
	}
	function getHeroItem3($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22) {
	$sql = "SELECT count(*) as total, dotaplayers.item3, items.icon , items.name , items.itemid
	FROM dotaplayers 
	LEFT JOIN items ON items.itemid = dotaplayers.item3
	WHERE hero = '$heroid' 
	AND dotaplayers.item3 != '\0\0\0\0' 
    AND dotaplayers.item3 != '' 
	AND dotaplayers.item3 != '$mostItem1' AND dotaplayers.item3 != '$mostItem11'
	AND dotaplayers.item3 != '$mostItem2' AND dotaplayers.item3 != '$mostItem22'
	GROUP BY item3 having count(*) > 1 
	ORDER BY count(*) DESC LIMIT 2";
	return $sql;
	}
	function getHeroItem4($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22,$mostItem3,$mostItem33) {
	$sql = "SELECT count(*) as total, dotaplayers.item4, items.icon , items.name , items.itemid
	FROM dotaplayers 
	LEFT JOIN items ON items.itemid = dotaplayers.item4
	WHERE hero = '$heroid' 
	AND dotaplayers.item4 != '\0\0\0\0' 
    AND dotaplayers.item4 != '' 
	AND dotaplayers.item4 != '$mostItem1' AND dotaplayers.item4 != '$mostItem11'
	AND dotaplayers.item4 != '$mostItem2' AND dotaplayers.item4 != '$mostItem22'
	AND dotaplayers.item4 != '$mostItem3' AND dotaplayers.item4 != '$mostItem33'
	GROUP BY item4 having count(*) > 1 
	ORDER BY count(*) DESC LIMIT 2";
	return $sql;
	}
	function getHeroItem5($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22,$mostItem3,$mostItem33,$mostItem4,$mostItem44) {
	$sql = "SELECT count(*) as total, dotaplayers.item5, items.icon , items.name , items.itemid
	FROM dotaplayers 
	LEFT JOIN items ON items.itemid = dotaplayers.item5
	WHERE hero = '$heroid' 
	AND dotaplayers.item5 != '\0\0\0\0' 
    AND dotaplayers.item5 != '' 
	AND dotaplayers.item5 != '$mostItem1' AND dotaplayers.item5 != '$mostItem11'
	AND dotaplayers.item5 != '$mostItem2' AND dotaplayers.item5 != '$mostItem22'
	AND dotaplayers.item5 != '$mostItem3' AND dotaplayers.item5 != '$mostItem33'
	AND dotaplayers.item5 != '$mostItem4' AND dotaplayers.item5 != '$mostItem44'
	GROUP BY item5 having count(*) > 1 
	ORDER BY count(*) DESC LIMIT 2";
	return $sql;
	}
	function getHeroItem6($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22,$mostItem3,$mostItem33,$mostItem4,$mostItem44,$mostItem5,$mostItem55) {
	$sql = "SELECT count(*) as total, dotaplayers.item6, items.icon , items.name , items.itemid
	FROM dotaplayers 
	LEFT JOIN items ON items.itemid = dotaplayers.item6
	WHERE hero = '$heroid' 
	AND dotaplayers.item6 != '\0\0\0\0' 
    AND dotaplayers.item6 != '' 
	AND dotaplayers.item6 != '$mostItem1' AND dotaplayers.item6 != '$mostItem11'
	AND dotaplayers.item6 != '$mostItem2' AND dotaplayers.item6 != '$mostItem22'
	AND dotaplayers.item6 != '$mostItem3' AND dotaplayers.item6 != '$mostItem33'
	AND dotaplayers.item6 != '$mostItem4' AND dotaplayers.item6 != '$mostItem44'
	AND dotaplayers.item6 != '$mostItem5' AND dotaplayers.item6 != '$mostItem55'
	GROUP BY item6 having count(*) > 1 
	ORDER BY count(*) DESC LIMIT 2";
	return $sql;
	}
	
	
	function getHeroHistory($minPlayedRatio,$heroid,$order,$sortdb,$offset, $rowsperpage,$LEAVER) {
	$text = "
	SELECT CASE WHEN (kills = 0) THEN 0 WHEN (deaths = 0) then 1000 ELSE (kills*1.0/deaths*1.0) end as kdratio, 
	dp.gameid as gameid, 
	g.gamename, 
	kills, 
	deaths,
	assists, 
	creepkills, 
	neutralkills, 
	creepdenies, 
	towerkills, 
	raxkills, 
	courierkills, 
	b.name as name, 
	f.name as banname, 
	CASE when(gamestate = '17') then 'PRIV' else 'PUB' end as type, 
	CASE when ((winner=1 AND newcolour < 6) 
	or (winner=2 and newcolour > 5)) 
	AND b.`left`/g.duration >= $minPlayedRatio  then 'WON' when ((winner=2 AND newcolour < 6) 
	or (winner=1 and newcolour > 5)) 
	AND b.`left`/g.duration >= $minPlayedRatio  then 'LOST' when  winner=0 then 'DRAW' else '$LEAVER' end as result 
	FROM dotaplayers AS dp 
	LEFT JOIN gameplayers AS b ON b.gameid = dp.gameid 
	AND dp.colour = b.colour 
	LEFT JOIN dotagames AS dg ON dg.gameid = dp.gameid
	LEFT JOIN games AS g ON g.id = dp.gameid 
	LEFT JOIN heroes as e ON dp.hero = heroid 
	LEFT JOIN bans as f ON b.name = f.name 
	WHERE original = '$heroid' 
	ORDER BY $order $sortdb 
	LIMIT $offset, $rowsperpage";
 
	return $text;
	}
	
	function getUserGameHistory($LEAVER,$username,$order,$sortdb,$offset, $rowsperpage,$minPlayedRatio) {
	$text = "SELECT 
	winner, 
	dp.gameid as id, 
	newcolour, 
	datetime, 
	gamename, 
	original, 
	description, 
	kills, 
	deaths, 
	assists, 
	creepkills, 
	creepdenies, 
	neutralkills, 
	name, 
    CASE when(gamestate = '17') then 'PRIV' else 'PUB' end as type,
    CASE WHEN (kills = 0) THEN 0 WHEN (deaths = 0) then 1000 ELSE (kills*1.0/deaths) end as kdratio,
    CASE when ((winner=1 and newcolour < 6) 
	or (winner=2 and newcolour > 5)) 
	AND gp.`left`/g.duration >= $minPlayedRatio  then 'WON' when ((winner=2 and newcolour < 6) 
	or (winner=1 and newcolour > 5)) 
	AND gp.`left`/g.duration >= $minPlayedRatio  then 'LOST' when  winner=0 then 'DRAW' else '$LEAVER' end as outcome 
	FROM dotaplayers AS dp 
	LEFT JOIN gameplayers AS gp ON gp.gameid = dp.gameid and dp.colour = gp.colour 
	LEFT JOIN dotagames AS dg ON dg.gameid = dp.gameid 
	LEFT JOIN games AS g ON g.id = dp.gameid 
	LEFT JOIN heroes as e ON dp.hero = heroid 
	WHERE LOWER(name) = LOWER('$username') and original <> 'NULL' 
	ORDER BY $order $sortdb, g.id $sortdb 
	LIMIT $offset, $rowsperpage";
 
	return $text;
	}
	
	
	function longGameWon($username) {
	$sql = "SELECT (dotagames.min * 60 + dotagames.sec) AS longgamewon, 
	dotagames.gameid,
	games.gamename, 
	games.duration, 
	dotaplayers.kills, 
	dotaplayers.deaths, 
	dotaplayers.creepkills, 
    dotaplayers.creepdenies,	
	dotaplayers.assists, 
	dotaplayers.neutralkills 
			FROM gameplayers
			LEFT JOIN games ON games.id = gameplayers.gameid 
			LEFT JOIN dotaplayers ON dotaplayers.gameid = games.id AND dotaplayers.colour = gameplayers.colour 
			LEFT JOIN dotagames ON games.id = dotagames.gameid 
			WHERE LOWER(name) = LOWER('$username')
			AND (
					(
						winner = 1 
						AND dotaplayers.newcolour >= 1
						AND dotaplayers.newcolour <= 5
					) 
					OR
					(
						winner = 2 
						AND dotaplayers.newcolour >= 7 
						AND dotaplayers.newcolour <= 11
					)
				)
			GROUP BY dotagames.gameid
			ORDER BY longgamewon DESC
			LIMIT 1";
			return $sql;
	}
	
	function fastGameWon($username) {
	$sql = "SELECT dotagames.min * 60 + dotagames.sec AS fastgamewon, 
	dotagames.gameid, 
	games.gamename, 
	games.duration, 
	dotaplayers.kills, 
	dotaplayers.deaths, 
	dotaplayers.creepkills, 
    dotaplayers.creepdenies,	
	dotaplayers.assists, 
	dotaplayers.neutralkills 
			FROM gameplayers
			LEFT JOIN games ON games.id = gameplayers.gameid 
			LEFT JOIN dotaplayers ON dotaplayers.gameid = games.id AND dotaplayers.colour = gameplayers.colour 
			LEFT JOIN dotagames ON games.id = dotagames.gameid 
			WHERE LOWER(name) = LOWER('$username')
			AND (
					(
						winner = 1 
						AND dotaplayers.newcolour >= 1
						AND dotaplayers.newcolour <= 5
					) 
					OR
					(
						winner = 2 
						AND dotaplayers.newcolour >= 7 
						AND dotaplayers.newcolour <= 11
					)
				)
			GROUP BY dotagames.gameid
			ORDER BY fastgamewon ASC
			LIMIT 1";
			return $sql;
	}
	
	
	

///////////////////////

    function convEnt($text){
return str_replace(
array('<br>', '&#039;', '&quot;', '&amp;', '&#36;', '&lt;', '&gt;'), 
array("\r\n", "'", '"', '&amp;', '$', '<', '>'), $text);
}
    function convEnt2($text){
return strip_tags(str_replace(
array("'", '"', "<", ">",'$'), 
array('&#039;', '&quot;','&lt;', '&gt;','&#36;'), $text));
}
///////////////////////

   function my_nl2br($str, $rep = "\r\n", $max = 2) {
$arr = explode("\r\n", $str);
$str = '';
$nls = 0;
    foreach($arr as $line) {
    $str .= $line;
    if (empty($line)) {
    $nls++;
    } else {
    $nls = 0;
           }
      if ($nls < $max) {
      $str .= $rep;
                       }
      }
return substr($str, 0, strlen($str) - strlen($rep));
}

function BBCode ($text) {
$search = array(
    '@\[(?i)b\](.*?)\[/(?i)b\]@si',
    '@\[(?i)i\](.*?)\[/(?i)i\]@si',
    '@\[(?i)u\](.*?)\[/(?i)u\]@si',
	'#\[s\](.*?)\[/s\]#is',
	'/\[ul\]/is',
	'/\[\/ul\]/is',
	'/\[li\]/is',
	'/\[\/li\]/is',
    '#\[img\](.*?)\[/img\]#i',
    '@\[(?i)url=(.*?)\](.*?)\[/(?i)url\]@si',
	'/\[url\]([^\"]*?)\[\/url\]/si',
	'/\[font(#[A-F0-9]{6})\](.+?)\[\/font\]/is',
	'/\[font=([^\]]*?)\]([\s\S]*?)\[\/font\]/is',
	'/\[color(#[A-F0-9]{6})\](.+?)\[\/color\]/is',
	'/\[color=([^\]]*?)\]([\s\S]*?)\[\/color\]/is',
	'~\[quote\]~is',
	'~\[/quote\]~is',
	'~\[quote=(.+?)\]~is',
	'/\[justify\][\r\n]*(.+?)\[\/justify\][\r\n]*/si',
	'/\[youtube=http:\/(\/www\.|\/[a-z]+\.|\/)youtube\.com\/watch\?v=([a-zA-Z0-9-_]+)(.*)\]/si',
	'/\[youtube]http:\/(\/www\.|\/[a-z]+\.|\/)youtube\.com\/watch\?v=([a-zA-Z0-9-_]+)(.*)\[\/youtube\]/si',
    '@\[(?i)code\](.*?)\[/(?i)code\]@si',
	'/\[code\](.*?)\[\/code\]/is',
	'/\[left\](.*?)\[\/left\]/is',
	'/\[right\](.*?)\[\/right\]/is',
	'/\[center\](.*?)\[\/center\]/is',
	'#\[size=([1-9]|1[0-9]|24)\](.*?)\[/size\]#is',
	'/\[hl\][\r\n]*(.+?)\[\/hl\][\r\n]*/is',
	'/\[php\](.*?)\[\/php\]/is',
	'/\[spoiler\][\r\n]*(.+?)\[\/spoiler\][\r\n]*/si',
);
$replace = array(
    '<b>\\1</b>',
    '<i>\\1</i>',
    '<u>\\1</u>',
	'<span style="text-decoration: line-through;">$1</span>',
	'<ul>',
	'</ul>',
	'<li>',
	'</li>',
    '<img src="\\1"/>',
    '<a href="\\1" target="_blank">\\2</a>',
	'<a href="\\1" target="_blank">\\1</a>',
	'<span style="color:\\1">\\2</span>',
	'<span style=\"color: $1\">$2</span>',
	'<span style="color:\\1">\\2</span>',
	'<span style="color: $1">$2</span>',
	
	'<table style="width:90%" border=0><tr><td class="singlequoting">',
	'</td></tr></table>',
	
	'<table style="width:90%"><tr><td class="quoting">\\1</td></tr><tr><td class="quote">\\2',
	
	'<div align="justify">\\1</div>',
	'<object width=\"640\" height=\"385\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\2\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/\\2\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"640\" height=\"385\"></embed></object>',
	'<object width=\"640\" height=\"385\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\2\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/\\2\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"640\" height=\"385\"></embed></object>',
    '<pre>\\1</pre>',	
	'<pre>$1</pre>',
	'<div style="text-align: left;">$1</div>',
	'<div style="text-align: right;">$1</div>',
	'<div style="text-align: center;">$1</div>',
	'<span style="font-size: $1px;">$2</span>',
	'<span class="hl">\\1</span>',
	'<pre class="brush: php;">$1</pre>',
	'<div class="spoilerdiv"><input class="spoiler" type="button" onclick="showSpoiler(this);" value="Show/Hide" /><div class="inner" style="display:none;">$1</div></div>');
return preg_replace($search , $replace, $text);
}

function BBDecode ($text) {
$search = array(
    '/<a href=\"([^<> \n\r\[\]]+?)\" target=\"(_new|_blank)\">(.+?)<\/a>/i',
	'/<span style="color: (.+?)">(.+?)<\/span>/is',
    '~<table style="width:90%" border=0><tr><td class="singlequoting">(.+?)</td></tr></table>~is',
    '/<a\s[^<>]*?href=\"?([^<>]*?)\"?(\s[^<>]*)?>([^<>]*?)<\/a>/si',
    '/<b>(.+?)<\/b>/is',
    '/<u>(.+?)<\/u>/is',
	'/<i>(.+?)<\/i>/is',
	'/<span style="font-size: (.+?)px;\">(.+?)<\/span>/is',
	'/<span style="text-decoration: line-through;\">(.+?)<\/span>/is',
	
	'~<table style="width:90%"><tr><td class="quoting">(.+?)</td></tr><tr><td class="quote">(.+?)</td></tr></table>~is',
	
	'~<table style="width:90%"><tr><td class="quoting">(.+?)</td></tr><tr><td class="quote">~is',
	
	'~</td></tr></table>~is',
	
	'/<div style="text-align: center;">(.+?)<\/div>/is',
	'/<div style="text-align: left;">(.+?)<\/div>/is',
	'/<div style="text-align: right;">(.+?)<\/div>/is',
	'/<div align="justify">(.+?)<\/div>/is',
	'/<img src=\"([^<> \n\r\[\]&]+?)\" alt=\"(.+?)\" (title=\"(.+?)\" )?\/>/si',
	'/<img\s[^<>]*?src=\"?([^<>]*?)\"?(\s[^<>]*)?\/?>/si',
	'/<object width=\"[0-9]+\" height=\"[0-9]+\"><param name=\"movie\" value=\"http:\/\/www\.youtube\.com\/v\/([a-zA-Z0-9-_]+)\"><\/param><param name=\"wmode\" value=\"transparent\"><\/param><embed src=\"http:\/\/www\.youtube\.com\/v\/([a-zA-Z0-9-_]+)\" type=\"application\/x-shockwave-flash\" wmode=\"transparent\" width=\"[0-9]+\" height=\"[0-9]+\"><\/embed><\/object>/si',
	'/<span class="hl">(.+?)<\/span>/is',
	'/<pre class="brush: php;">(.+?)<\/pre>/is',
	'/<div class="spoilerdiv"><input class="spoiler" type="button" onclick="showSpoiler\(this\);" value="Show\/Hide" \/><div class="inner" style="display:none;">(.+?)<\/div><\/div>/is'
);
$replace = array(
    '[url=\\1]\\3[/url]',
    '[color=\\1]\\2[/color]',
    '[quote]\\1[/quote]',
    '[url]$3[/url]',
    '[b]\\1[/b]',
	'[u]\\1[/u]',
	'[i]\\1[/i]',
	'[size=\\1]\\2[/size]',
	'[s]\\1[/s]',
	'[quote=\\1]\\2[/quote]',
	'[quote=\\1]',
	'[/quote]',
	'[center]\\1[/center]',
	'[left]\\1[/left]',
	'[right]\\1[/right]',
	'[justify]\\1[/justify]',
	'[img=\\1]\\2[/img]',
	'[img]$1[/img]',
	'[youtube]http://www.youtube.com/watch?v=\\1[/youtube]',
	'[hl]\\1[/hl]',
	'[php]\\1[/php]',
	'[spoiler]\\1[/spoiler]'
);
return preg_replace($search , $replace, $text);
}

  function convHTML($ic1,$ic2,$ic3,$ic4,$ic5,$ic6,$HTML,$hero,$hero2,$url){
  
              $HTML = str_replace("$ic1","",$HTML);
			  $HTML = str_replace("$ic2","",$HTML);
			  $HTML = str_replace("$ic3","",$HTML);
			  $HTML = str_replace("$ic4","",$HTML);
			  $HTML = str_replace("$ic5","",$HTML);
			  $HTML = str_replace("$ic6","",$HTML);
			  $HTML = str_replace("'./img/items/","'$url/img/items/",$HTML);
			  $HTML = str_replace("$hero",
			  "<img title='$hero2' alt='' width='32px' height='32px' src='$url/img/heroes/$hero2.gif'>",$HTML);
			  //$HTML = BBDecode($HTML);
              //$HTML = strip_tags($HTML);
			  return $HTML;
  
  }


?>