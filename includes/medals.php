<?PHP
	    
		$TotalMedals = 0;

		
		echo "<div align='center'>
		<table class='tableA'><!-- <tr>
		<th></th>
		<th>$realname Achievements</th>
		</tr>-->";
		
		$opacity = ""; 
		$ColClass = "NotAchieved";
		$buildAchievements = ""; 
		$countAchievements = 0;
		
		//KILLS		
		if ($kills < $KillsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/kills.png'></td>
		<td align='left'><span class='$ColClass'>Kill $KillsMedal enemy heroes. ($kills kills)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//ASSISTS		
		if ($assists < $AssistMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/assist.png'></td>
		<td align='left'><span class='$ColClass'>Assist in $AssistMedal kills. ($assists assists)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		

		//WIN PERCENT		
		if ($winloose < $WinPercentMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/winperc.gif'></td>
		<td align='left'><span class='$ColClass'>Achieve $WinPercentMedal % wins. ($winloose%)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//KILLS PERCENT		
		if ($KillsPercent < $KillsPercentMedal) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/killperc.gif'></td>
		<td align='left'><span class='$ColClass'>Achieve $KillsPercentMedal % of kills. ($KillsPercent %)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//TOTAL GAMES	
		if ($totgames < $GamesMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/games.png'></td>
		<td align='left'><span class='$ColClass'>Play $GamesMedal games. ($totgames games)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//WINS
		if ($wins < $WinsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/wins.png'></td>
		<td align='left'><span class='$ColClass'>Win $WinsMedal games. ($wins wins)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//CREEPS
		if ($creepkills < $CreepsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/creeps.gif'></td>
		<td align='left'><span class='$ColClass'>Kill $CreepsMedal creeps. ($creepkills creeps)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//DENIES
		if ($creepdenies < $DeniesMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/denies.gif'></td>
		<td align='left'><span class='$ColClass'>Deny $DeniesMedal creeps. ($creepdenies denies)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//TOWERS
		if ($towerkills < $TowersMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/towers.png'></td>
		<td align='left'><span class='$ColClass'>Destroy $TowersMedal towers. ($towerkills towers)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//COURIERS
		if ($courierkills < $CouriersMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/couriers.gif'></td>
		<td align='left'><span class='$ColClass'>Kill $CouriersMedal enemy couriers. ($courierkills couriers)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//NEUTRALS
		if ($neutralkills < $NeutralsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/neutrals.png'></td>
		<td align='left'><span class='$ColClass'>Kill $NeutralsMedal neutrals. ($neutralkills neutrals)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//PLAY DURATION
		if ($totalHours < $PlayDurationMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/play.gif'></td>
		<td align='left'><span class='$ColClass'>Play at least $PlayDurationMedal hours. ($totalHours h)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;

		$buildAchievements .= "<tr>
		<td width='72px' align='left'></td>
		<td align='left'>Total achievements: $TotalMedals of $countAchievements</td>
		</tr>";

		
		$buildAchievements .= "</table></div><br>";
		
		echo $buildAchievements;

	
	
	?>