<?PHP

   if (isset($_GET["replayLoc"]) ) 
   {$replayloc = "../".EscapeStr($_GET["replayLoc"]); }

   if(file_exists("$replayloc"))
   {
   echo "<div class='hid' align='center'>
   <table class='tableA'><tr><td><b>Game Log:</b></td></tr>
   <tr>
   <td colspan='13'>
   <table width='80%'>";

	require('chat.php');
	$replay = new replay($replayloc);
	if (!isset($error)) {
	
	///////////////////     COLORS            ////////////
	
			$firstBlood = true;
		$i = 1;
		foreach ($replay->teams as $team=>$players) {
			if ($team != 12) {	
				foreach ($players as $player) {          
					// remember there's no color in tournament replays from battle.net website
					if ($player['color']) {
						//echo('<span class="'.$player['color'].'">'.$player['color'].'</span>');
						// since version 2.0 of the parser there's no players array so
						// we have to gather colors and names earlier as it will be harder later ;)
						$colors[$player['player_id']] = $player['color'];
						$names[$player['player_id']] = $player['name'];
					}
				}
				$i++;
			}
		}
		for($i = 0; $i <= 14; $i++)
		{
			switch($i) {
			
			case 0:
				$slotname[$i] = 'The Sentinel';
				$slotcolor[$i] = 'sentinel';
				break;
			case 1:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'blue')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 2:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'teal')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 3:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'purple')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 4:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'yellow')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 5:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'orange')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 6:
				$slotname[$i] = 'The Scourge';
				$slotcolor[$i] = 'scourge';
				break;
			case 7:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'pink')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 8:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'gray')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 9:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'light-blue')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 10:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'dark-green')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 11:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'brown')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 12:		
				$slotname[$i] = 'Neutral Creeps';
				$slotcolor[$i] = 'system';
				break;	
			case 13:		
				$slotname[$i] = 'The Sentinel';
				$slotcolor[$i] = 'sentinel';
				break;
			case 14:
				$slotname[$i] = 'The Scourge';
				$slotcolor[$i] = 'scourge';
				break;
			}
		}
		$colors[''] = 'system';
		$names[''] = 'System';
		
         //////////////       COLORS              /////////////
		 
		if ($replay->chat) {
			foreach ($replay->chat as $content) {
				$time = $content['time'];
				$mode = $content['mode'];
				$playerID = $content['player_id'];
				$playerName = $names[$playerID];
				$playerColor = $colors[$playerID];
				$text = convEnt2($content['text']);
				
	if($mode == 'All' || getTeam($playerColor) == 1) 
	{$ply = "<a href='user.php?u=$playerName'><span class='$playerColor'>$playerName</span></a>";} else $ply = "";
	
	$timeSec = secondsToTime($time/1000);
				
				echo "<tr class='row'><td align='right' width='250px'>$ply</td>
				<td width='90px'>$timeSec</td>";
				
				if($mode == 'All') 
				{echo "<td width='500px' class='all'>$text</td>";}
				
				else if($mode == 'System') {
					 if($content['type'] == 'Start') 
					 {echo "<td width='500px' class='GameSystem'>$text</td>";}
					 
					        else if($content['type'] == 'Hero')
							{
								$victim = trim($content['victim']);
								$killer = $content['killer'];
								if($firstBlood)
								{
									if($content['killer'] < 12)
									{
			echo "<td width='500px' class='GameSystem'>
			<div style='background-color:#7D0001;'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>
			$text<span class='$slotcolor[$victim]'>$slotname[$victim]</span> <b>for first blood</b></div></td>";
									$firstBlood = false;
									}
									else
									{
									echo "<td width='500px' class='GameSystem'>
									<span class='$slotcolor[$killer]'>$slotname[$killer]</span> $text<span class='$slotcolor[$victim]'> $slotname[$victim]</span></td>";
									}
								}
								else
								{
								if($victim == $killer)
								{
								echo "<td width='500px' class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span> has killed himself!</td>";
									}
				else if(($victim < 6 && $killer < 6) || ($victim > 6 && $killer > 6) && $killer <= 11)
									{
				                 echo "<td width='500px' class='GameSystem'>
				<span class='$slotcolor[$killer]'>$slotname[$killer]</span> <span class='$slotcolor[$victim]'>$victim]</span></td>";
									}
									else
									{
									echo "<td width='500px' class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span> $text<span class='$slotcolor[$victim]'> $slotname[$victim]</span></td>";
									}
								}
							}
							else if($content['type'] == 'Courier')
							{
							$victim = trim($content['victim']);
							$killer = $content['killer'];
							echo "<td width='500px' class='GameSystem'><span class='$slotcolor[$victim]'>$slotname[$victim] </span>$text<span class='$slotcolor[$killer]'> $slotname[$killer]</span></td>";
							}
							else if($content['type'] == 'Tower')
							{
							$killer = $content['killer'];
								
							echo "<td width='500px' class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>$text $content[side]  level  $content[level]<span class=".strtolower($content['team'])."> $content[team] </span> tower</td>";
							}
							else if($content['type'] == 'Rax')
							{
							$killer = $content['killer'];
						    echo "<td width='500px' class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>$text $content[side] $content[raxtype]<span class='".strtolower($content['team'])."'> $content[team]</span> barracks</td>";
								
							}
				else if($content['type'] == 'Throne') {echo "<td width='500px' class='GameSystem'>$text</td>";}
				else if($content['type'] == 'Tree')   {echo "<td width='500px' class='GameSystem'>$text</td>";}
						}
						else
						{
	if(getTeam($playerColor) == 1)
						{
			echo "<td width='500px' align='left' class='sentinel'><span style='color:#B32704;'>$text</span></td>";
						}
						    else
						    {
			echo "<td width='500px' align='right' class='scourge'><span style='color:#00A404;'>$text</span></td>";
						    }
						}
			echo "<td width='90px' style='text-align:center'>".secondsToTime($time/1000)."</td>
			
			<td width='250px'>";
                        if($mode == 'All' || getTeam($playerColor) == 2)
						{
						echo "<a href='user.php?u=$playerName'><span class='$playerColor'>$playerName</span></a>";
						}
						echo "</td></tr>";
			}
		}	
	}
	echo "</table>
	</td>
	</tr>
	     </table></div>";

}
?>		