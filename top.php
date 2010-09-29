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
*    along with DOTA OPEN STATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/

  include('header.php');
  //include('./includes/AJAX2.php');	 
  
  $pageTitle = "Dota OpenStats | $lang[top_players]";
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  
  $games = $minGamesPlayed;
  $gplay = $minGamesPlayed;
  if (isset($_GET['gp'])) {$games = safeEscape($_GET['gp']);
  $games = preg_replace("/[^0-9]/", '', $games);
  }
  
  			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
			   if (is_numeric($_POST['gp'])){
			   $gplay = trim(safeEscape($_POST['gp']));
			   $games = $gplay;
			   }
			} 
  
  
  $sql = getCountTops($games);
  
     $result = $db->query($sql);
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];
	 $result_per_page = $top_players_per_page;
     $order = 'totalscore';
	 
	 if (isset($_GET['order']))
	 	 {
	 	 if ($_GET['order'] == 'score') {$order = ' totalscore ';}
	 	 if ($_GET['order'] == 'name') {$order = ' LOWER(name) ';}
	 	 if ($_GET['order'] == 'deaths') {$order = ' deaths ';}
	 	 if ($_GET['order'] == 'kills') {$order = ' kills ';}
	 	 if ($_GET['order'] == 'losses') {$order = ' losses ';}
		 if ($_GET['order'] == 'assists') {$order = ' assists ';}
		 if ($_GET['order'] == 'ratio') {$order = ' killdeathratio ';}
		 if ($_GET['order'] == 'creeps') {$order = ' creepkills ';}
		 if ($_GET['order'] == 'denies') {$order = ' creepdenies ';}
		 if ($_GET['order'] == 'neutrals') {$order = ' neutralkills ';}
		 if ($_GET['order'] == 'games') {$order = ' totgames ';}
	 	 }
		 
		 $sort = 'ASC';
		 $sortdb = 'ASC';
		 $page_ = '&page=1';
		 
		 if (!isset($_GET['sort'])) {$sort = "asc"; $sortdb = "DESC";}

		 if (isset($_GET['sort']) AND $_GET['sort'] == 'desc')
		 {$sort = 'asc'; $sortdb = 'ASC';} else {$sort = 'desc'; $sortdb = 'DESC';}
		 
		 

		 /* 
		      //////////////////////////////////
		     //Dont reset pages on sorting???//
		    //////////////////////////////////
			
		 if (isset($_GET['page']) )
		 {$page_ = '&page='.safeEscape($_GET['page']);}
		 
		 */
	 
	 include('pagination.php');
	 
	    	 
	 echo "<div style='margin-right:24px;' align='right'>$lang[showing] "; 
	 echo $offset+1;
	 echo " - ";
	 echo $offset+$rowsperpage;
	 echo "</div>";
	 
	 $sql = getTops($scoreFormula,$minPlayedRatio,$games,$order,$sortdb,$offset,$rowsperpage);
       
	 $result = $db->query($sql);

     $data = array($lang["min_games_played"],
	 $lang["update"],
	 $games,
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."order=name&sort=$sort".$page_."'>$lang[name]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=score&sort=$sort".$page_."'>$lang[score]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=games&sort=$sort".$page_."'>$lang[games]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=wins&sort=$sort".$page_."'>$lang[wins]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=losses&sort=$sort".$page_."'>$lang[losses]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=kills&sort=$sort".$page_."'>$lang[kills]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=deaths&sort=$sort".$page_."'>$lang[deaths]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=assists&sort=$sort".$page_."'>$lang[assists]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=ratio&sort=$sort".$page_."'>$lang[kd]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=creeps&sort=$sort".$page_."'>$lang[creeps]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=denies&sort=$sort".$page_."'>$lang[denies]</a>",
	 "<a href='{$_SERVER['PHP_SELF']}?".$gplay."&order=neutrals&sort=$sort".$page_."'>$lang[neutrals]</a>",);
   
   $tags = array('{%MINGAMES%}','{%UPDATE%}','{GAMES_VALUE}','{%NAME%}', '{%SCORE%}', '{%GAMES%}', '{%WINS%}', '{%LOSSES%}', '{%KILLS%}', '{%DEATHS%}', '{%ASSISTS%}', '{%RATIO%}', '{%CREEPS%}', '{%DENIES%}', '{%NEUTRALS%}');
   
   echo str_replace($tags, $data, file_get_contents("./style/$default_style/top.html"));
   
  /*  
  */
	 $counter = 1; //  $top_players_per_page
	 if (isset($_GET['page'])) {$counter = ($top_players_per_page * $_GET['page']) - $top_players_per_page+1;}
	 
	 
	 while ($list = $db->fetch_array($result,'assoc')) {

		$name=trim($list["name"]);
		$name2=trim(strtolower($list["name"]));
		$banname=$list["banname"];
		
		if (trim(strtolower($banname)) == strtolower($name)) 
		{$name = "<span style='color:#BD0000'>$list[name]</span>";}
		
		$totgames=$list["totgames"];
		$kills=ROUND($list["kills"],1);
		$death=ROUND($list["deaths"],1);
		$assists=ROUND($list["assists"],1);
		$creepkills=ROUND($list["creepkills"],1);
		$creepdenies=ROUND($list["creepdenies"],1);
		$neutralkills=ROUND($list["neutralkills"],1);
		$courierkills=ROUND($list["courierkills"],1);
		$wins=$list["wins"];
		$losses=$list["losses"];
		
		if ($wins <=0)
		{$winlosses = 0;}
		else
		if($wins == 0 and $wins+$losses == 0){ $winlosses = 0;}
		else
		if($wins+$losses == 0){$winlosses = 1000;}
		else
		if ($wins >0)
		{$winlosses = ROUND($wins/($wins+$losses), 3)*100;} 
		
		$totalscore=ROUND($list["totalscore"],2);
		$killdeathratio=ROUND($list["killdeathratio"],1); 
		
		  $data = array($counter, $name2, $name, $totalscore, $totgames, $wins ,$winlosses,$losses, $kills, $death,$assists,$killdeathratio,$creepkills,$creepdenies,$neutralkills, );
   
   $tags = array('{%COUNTER%}','{%NAME_URL%}', '{%NAME%}', '{%SCORE%}', '{%TOTGAMES%}', '{%WINS%}', '{%WINLOSSES%}','{%LOSSES%}','{%KILLS%}', '{%DEATHS%}', '{%ASSISTS%}', '{%KDRATIO%}', '{%CK%}', '{%CD%}','{%NEUTRALS%}'
   );
   
   echo str_replace($tags, $data, file_get_contents("./style/$default_style/top_row.html"));
   
		/*
		*/
		$counter++;
		echo "";
		
		}
		echo "</table><br/>";
		   include('pagination.php'); 

   //include('./includes/get_tops.php');
   echo " <body onload='requestActivities2(\"includes/get_tops.php\");'> ";
   
    echo "<div id='divActivities2'></div>";

  
  
  include('footer.php');
  
  ?>