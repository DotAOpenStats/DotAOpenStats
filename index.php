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
  $pageTitle = "$lang[site_name] | $lang[title]";
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  
  echo "<TABLE><TR><TD style='height:24px;'> $lang[welcome_title]</TD>
  <TR><TD>DotA OpenStats is Php/MySql based web statistic and CMS site for DotA Games</TD></TR>
  <TR><TD><a href='https://sourceforge.net/projects/dotaopenstats/'>Download Dota OpenStats</a></TD>
  </TR></TABLE><br/>";
  
  	$sql = "SELECT COUNT(news_id) FROM news LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$result_per_page = $news_per_page;
	
	include('pagination.php');
	echo "<br />";
	$sql = "SELECT * FROM news ORDER BY news_date DESC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	
	if (isset($_GET['page'])) {$mypage = '?page=';}
	
	while ($row = $db->fetch_array($result,'assoc')) {
	 $title = "$row[news_title]";
	 $text = "$row[news_content]";
	 $text = str_replace("<br>","<br>",$row["news_content"]);
	 $date = date($date_format,strtotime($row["news_date"]));
	 $text = str_replace("&lt;br&gt;","",$row["news_content"]);
	 echo "<div align='center'><table class='tableNews'>
	 <tr>
	 <th class='padLeft'><p class='alignleft'>$title</p><p class='alignright'>$lang[posted]  $date <a name='$row[news_id]' href='index.php?page=$currentpage#$row[news_id]'>link</a></p></th>
	 </tr><td class='NewsText'>$text</td>
	 </tr>
	 </table>
	 </div><br />";
	 
	 }

  include('footer.php');
  
  ?>