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
*    along with DOTA OPENSTATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'new_dota';

$default_style = 'dota';
$default_language = 'english';

$bans_per_page = '20';
$games_per_page = '20';
$heroes_per_page = '20';
$top_players_per_page = '30';
$news_per_page = '5';
$search_limit = '50';

$top_stats = '5';

$displayUsersDisconnects = '0';

//Replay Location:
$replayLocation = 'replays';

//Max. page links before and after current page
$max_pagination_link = 5;

$scoreFormula = '((((kills-deaths+assists*0.5+towerkills*0.5+raxkills*0.2+(courierkills+creepdenies)*0.1+neutralkills*0.03+creepkills*0.03) * .2)+(wins-losses)))*10'; 

//Minimal ratio (lefttime/duration) that a player/hero has to complete a game to be counted as win/loss. otherwise game is ignored.
$minPlayedRatio = '0.8';
$minGamesPlayed = '2';

$date_format = 'd.m.Y H:i';

$monthly_stats = '5';

//Enable month rows. 1 - ENABLED, 0 - DISABLED

//Top Kills,Top Assists,Top Deaths,Top Creep Kills,Top Creep Denies
$monthRow1 = '1';
//Top Gold,Top Neutrals,Top Towers,Top Rax,Top Couriers Kills
$monthRow2 = '1';
//Best K/D,Best A/D Ratio,Most games,Best Win %,Top Stay %
$monthRow3 = '1';
//Most Kills,Assists,Deaths,Creeps,Denies
$monthRow4 = '1';
//AVG Kills,Assists,Deaths,Creeps,Denies
$monthRow5 = '1';

$DaysOnMonthlyStats = '1';
$TopRanksOnMonthly = '1';

$head_admin = 'Neubivljiv';
$bot_name = 'Ghost bot';

$LEAVER = 'LEAVER';

// Achievents plugin
$UserAchievements = '1'; //Enable/disable User Achievents
// Configuration
$AssistMedal = 200; //Assist in 200 kills. How many kills need to achieve this medal (default 200).
$KillsMedal = 500; //Kill 500 enemy heroes!
$GamesMedal = 50; //Play 50 games!
$WinsMedal = 50; //Win 20 games!
$CreepsMedal = 5000; //Kill 5000 creeps!
$DeniesMedal = 500; //Deny 500 creeps!
$TowersMedal = 30; //Destroy 10 towers!
$CouriersMedal = 20; //Kill 10 enemy couriers!
$NeutralsMedal = 500; //Kill 500 neutrals!
$PlayDurationMedal = 20; //Play at least 20 hours!

?>