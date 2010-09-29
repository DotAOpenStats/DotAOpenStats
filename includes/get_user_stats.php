<?PHP

  $username=strtolower(safeEscape($_GET["u"]));
  
  $BANNED =  "";
  $COLOR = "";
  
  $sql = "
  SELECT 
  gp.name AS name, bans.name AS banname, count(1) AS counttimes 
  FROM 
  gameplayers gp 
  JOIN dotaplayers dp ON dp.colour = gp.colour 
  AND 
  dp.gameid = gp.gameid 
  LEFT JOIN bans ON bans.name = gp.name
  WHERE 
  LOWER(gp.name) = LOWER('$username') GROUP BY gp.name 
  ORDER BY 
  counttimes DESC, gp.name ASC";
  
  $result = $db->query($sql);
  
  if ($db->num_rows($result) <=0) {echo $lang["err_user"] ; die;}

  while ($list = $db->fetch_array($result,'assoc')) {
  
  if (strtolower("$list[name]") == strtolower($list['banname'])) {$BANNED =  "(Banned)"; $COLOR = "style='color:#DC0000;'";}

  $realname = $list['name'];
  }
  
  /////////////////////////////////// HERO STATS ///////////////////////////////////
  
  //////////////////////////////
  //Find hero with most kills
	$sql = "
	SELECT 
	original, description, max(kills) 
	FROM dotaplayers AS a 
	LEFT JOIN gameplayers AS b ON b.gameid = a.gameid AND a.colour = b.colour 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name)= LOWER('$username') 
	GROUP BY original 
	ORDER BY max(kills) DESC LIMIT 1 ";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostkillshero=$list["original"];
	$mostkillsheroname=$list["description"];
	$mostkillscount=$list["max(kills)"];
	
	
	//////////////////////////////
	//Find hero with most deaths
	$sql = "SELECT original, description, max(deaths) FROM dotaplayers AS a LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') GROUP BY original ORDER BY max(deaths) DESC LIMIT 1 ";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostdeathshero=$list["original"];
	$mostdeathsheroname=$list["description"];
	$mostdeathscount=$list["max(deaths)"];

	
	//////////////////////////////
	//Find hero with most assists
	$sql = "SELECT original, description, max(assists) FROM dotaplayers AS a LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') GROUP BY original ORDER BY max(assists) DESC LIMIT 1 ";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostassistshero=$list["original"];
	$mostassistsheroname=$list["description"];
	$mostassistscountlist=$list["max(assists)"];
	$mostassistscount=$list["max(assists)"];
	
	
	//////////////////////////////
	//Get hero with most wins
	$sql = "
	SELECT original, description, COUNT(*) as wins 
	FROM gameplayers 
	LEFT JOIN games ON games.id=gameplayers.gameid 
	LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
	AND dotaplayers.colour=gameplayers.colour 
	LEFT JOIN dotagames ON games.id=dotagames.gameid 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') 
	AND((winner=1 
	AND dotaplayers.newcolour>=1 
	AND dotaplayers.newcolour<=5) 
	OR (winner=2 
	AND dotaplayers.newcolour>=7 
	AND dotaplayers.newcolour<=11)) 
	GROUP BY original order by wins desc limit 1";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostwinshero=$list["original"];
	$mostwinsheroname=$list["description"];
	$mostwinscount=$list["wins"];
	
	
	//////////////////////////////
	//Get hero with most losses
	$sql = "
	SELECT original, description, COUNT(*) as losses 
	FROM gameplayers 
	LEFT JOIN games ON games.id=gameplayers.gameid 
	LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
	AND dotaplayers.colour=gameplayers.colour 
	LEFT JOIN dotagames ON games.id=dotagames.gameid 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') 
	AND((winner=2 AND dotaplayers.newcolour>=1 AND dotaplayers.newcolour<=5) OR (winner=1 AND dotaplayers.newcolour>=7 AND dotaplayers.newcolour<=11)) 
	GROUP BY original order by losses desc limit 1";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostlosseshero=$list["original"];
	$mostlossesheroname=$list["description"];
	$mostlossescount=$list["losses"];
	
	
	//////////////////////////////
	//Get hero you have played most with
	$sql = "SELECT SUM(`left`) as timeplayed, original, description, COUNT(*) as played FROM gameplayers LEFT JOIN games ON games.id=gameplayers.gameid LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id AND dotaplayers.colour=gameplayers.colour LEFT JOIN dotagames ON games.id=dotagames.gameid LEFT JOIN heroes on hero = heroid WHERE name='$username' GROUP BY original ORDER BY played desc LIMIT 1";
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
		$mostplayedhero=$list["original"];
		$mostplayedheroname=$list["description"];
		$mostplayedcount=$list["played"];
		$mostplayedtime=secondsToTime($list["timeplayed"]);
	
	//////////////////////////////
	//Using score table
	$sql = "SELECT ($scoreFormula) as score from(select *, (kills/deaths) as killdeathratio from (select avg(dp.courierkills) as courierkills, avg(dp.raxkills) as raxkills,
		avg(dp.towerkills) as towerkills, avg(dp.assists) as assists, avg(dp.creepdenies) as creepdenies, avg(dp.creepkills) as creepkills,
		avg(dp.neutralkills) as neutralkills, avg(dp.deaths) as deaths, avg(dp.kills) as kills,
		count(*) as totgames, SUM(case when(((dg.winner = 1 and dp.newcolour < 6) or (dg.winner = 2 and dp.newcolour > 6)) AND gp.`left`/ga.duration >= 0.8) then 1 else 0 end) as wins, SUM(case when(((dg.winner = 2 and dp.newcolour < 6) or (dg.winner = 1 and dp.newcolour > 6)) AND gp.`left`/ga.duration >= 0.8) then 1 else 0 end) as losses
		from gameplayers as gp LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid LEFT JOIN games as ga ON ga.id = dg.gameid LEFT JOIN 
		dotaplayers as dp on dp.gameid = dg.gameid and gp.colour = dp.colour where dg.winner <> 0 and gp.name = '$username') as h) as i LIMIT 1";
		
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc');
	$score=$list["score"];

	//FINAL STEP
	$result = $db->query("SELECT COUNT(a.id), SUM(kills), SUM(deaths), SUM(creepkills), SUM(creepdenies), SUM(assists), SUM(neutralkills), SUM(towerkills), SUM(raxkills), SUM(courierkills), name FROM dotaplayers AS a LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour where name= '$username' group by name ORDER BY sum(kills) desc LIMIT 1");
	
	$row = $db->fetch_array($result,'assoc');
	$kills=$row["SUM(kills)"];
	$death=$row["SUM(deaths)"];
    $assists=$row["SUM(assists)"];
	$creepkills=$row["SUM(creepkills)"];
	$creepdenies=$row["SUM(creepdenies)"];
	$neutralkills=$row["SUM(neutralkills)"];
	$towerkills=$row["SUM(towerkills)"];
	$raxkills=$row["SUM(raxkills)"];
	$courierkills=$row["SUM(courierkills)"];
	$name=$row["name"];
	$totgames=$row["COUNT(a.id)"];

	if ($displayUsersDisconnects == 1)	
	{
	$sql = " SELECT COUNT(*) 
    FROM `gameplayers`
    WHERE (`leftreason` LIKE('%ECONNRESET%') OR `leftreason` LIKE('%was dropped%') ) AND name= '$username' LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
    $disc = $r[0]; }

	echo "<table>
	<tr>
	<td style='width:36%;padding-left:8px; height:24px;'><div align='left'>$lang[show_hero_stats] <span $COLOR>$realname</span></div></td>
	<td><div align='left'>$lang[show_stats_user] <b>$realname <span $COLOR>$BANNED</span></b></div></td>
	</tr>
	</table>";
	//calculate wins
    $wins=$db->getUserWins($username);
    //calculate losses
    $losses=$db->getUserLosses($username);
	
	if ($death >=1)
	{$kdratio = round($kills/$death,1);} else {$kdratio =0;}
	
	$totgames = $wins+$losses;
	$totscore = round($score,2);
	
	if($wins == 0 and $wins+$losses == 0)
	{$winloose = 0;}
	else
	{$winloose = round($wins/($wins+$losses), 4)*100;}
	
	
     		
	if ($mostkillshero == "") $mostkillshero = "blank";
	if ($mostdeathshero == "") $mostdeathshero = "blank";
	if ($mostassistshero == "") $mostassistshero = "blank";
	if ($mostwinshero == "") $mostwinshero = "blank";
	if ($mostlosseshero == "") $mostlosseshero = "blank";
	if ($mostplayedhero == "") $mostplayedhero = "blank";
    
	$mkhimg = "<a href='hero.php?hero=$mostkillshero'><img width='64px' title='$mostkillsheroname' alt='' src='img/heroes/$mostkillshero.gif'/ border=0></a>";
	$mdhimg = "<a href='hero.php?hero=$mostassistshero'><img width='64px' title='$mostdeathsheroname' alt='' src='img/heroes/$mostdeathshero.gif' border=0 /></a>";
	$mahimg = "<a href='hero.php?hero=$mostassistshero'><img width='64px' title='$mostassistsheroname' alt='' src='img/heroes/$mostassistshero.gif' border=0/></a>";
	$mwhimg = "<a href='hero.php?hero=$mostwinshero'><img width='64px' title='$mostwinsheroname' alt='' src='img/heroes/$mostwinshero.gif' border=0/></a>";
	$mlhimg = "<a href='hero.php?hero=$mostlosseshero'><img width='64px' title='$mostlossesheroname' alt='' src='img/heroes/$mostlosseshero.gif' border=0/></a>";
	$mphimg = "<a href='hero.php?hero=$mostplayedhero'><img width='64px' title='$mostplayedheroname' alt='' src='img/heroes/$mostplayedhero.gif' border=0/></a>";
	
	?>