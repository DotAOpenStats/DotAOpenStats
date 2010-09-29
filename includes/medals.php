<?PHP
	
	$firstgame=$row["MIN(datetime)"];

		$totalHours=ROUND($row["SUM(`left`)"]/ 3600,1);
		$totalMinutes=ROUND($row["SUM(`left`)"]/ 3600*60,1);
		
		if ($totalMinutes>0)
		{$killsPerMin = ROUND($kills/$totalMinutes,2);
		$deathsPerMin = ROUND($death/$totalMinutes,2);
		$creepsPerMin = ROUND($creepkills/$totalMinutes,2);
		}  else {$killsPerMin = 0; $deathsPerMin = 0;}

		
		echo "<div align='center'>
		<table class='tableA'><tr>
		<th></th>
		<th>$realname Achievements</th>
		</tr>";
		
		if ($kills >= $KillsMedal AND $KillsMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/kills.png'></td>
		<td align='left'>Kill $KillsMedal enemy heroes. ($kills kills)</td>
		</tr>";}
		
		if ($assists >= $AssistMedal AND $KillsMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/assist.png'></td>
		<td align='left'>Assist in $AssistMedal kills. ($assists assists)</td>
		</tr>";}
		
		if ($totgames >= $GamesMedal AND $KillsMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/games.png'></td>
		<td align='left'>Play $GamesMedal games. ($totgames games)</td>
		</tr>";}
		
		if ($wins >= $WinsMedal AND $KillsMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/wins.png'></td>
		<td align='left'>Win $WinsMedal games. ($wins wins)</td>
		</tr>";}
		
		
		if ($creepkills >= $CreepsMedal AND $CreepsMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/creeps.gif'></td>
		<td align='left'>Kill $CreepsMedal creeps. ($creepkills creeps)</td>
		</tr>";}
		
		if ($creepdenies >= $DeniesMedal AND $DeniesMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/denies.gif'></td>
		<td align='left'>Deny $DeniesMedal creeps. ($creepdenies denies)</td>
		</tr>";}
		
		if ($towerkills >= $TowersMedal AND $TowersMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/towers.png'></td>
		<td align='left'>Destroy $TowersMedal towers. ($towerkills towers)</td>
		</tr>";}
		
		if ($courierkills >= $CouriersMedal AND $CouriersMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/couriers.gif'></td>
		<td align='left'>Kill $CouriersMedal enemy couriers. ($courierkills couriers)</td>
		</tr>";}
		
		if ($neutralkills >= $NeutralsMedal AND $NeutralsMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/neutrals.png'></td>
		<td align='left'>Kill $NeutralsMedal neutrals. ($neutralkills neutrals)</td>
		</tr>";}
		
		if ($totalHours >=$PlayDurationMedal AND $PlayDurationMedal!=0)
		{echo "<tr class='row'>
		<td width='72px' align='left'><img alt='' width='48px' border=0 src='./img/achievements/play.gif'></td>
		<td align='left'>Play at least $PlayDurationMedal hours. ($totalHours h)</td>
		</tr>";}
		
		
		echo "</table></div><br />";

	
	
	?>