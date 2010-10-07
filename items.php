<?php
  /*********************************************
   <!--
   *     DOTA OPENSTATS
   *
   *  Developers: Ivan.
   *  Contact: ivan.anta@gmail.com - Ivan
   *
   *
   *  Please see http://openstats.iz.rs
   *  and post your webpage there, so I know who's using it.
   *
   *  Files downloaded from http://openstats.iz.rs
   *
   *  Copyright (C) 2010  Ivan
   *
   *
   *  This file is part of DOTA OPENSTATS.
   *
   *
   *   DOTA OPENSTATS is free software: you can redistribute it and/or modify
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
   
   $pageTitle = "$lang[site_name] | Items";
   $pageContents = ob_get_contents();
   ob_end_clean();
   echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
   
   $sql = "SELECT itemid 
          FROM items as Items
		  WHERE item_info !='' AND name != 'Aegis Check' AND name != 'Arcane Ring' 
		  GROUP BY shortname 
		  ORDER BY LOWER(name) ASC ";
   $result = $db->query($sql);
   //$r = $db->num_rows($result);
   $numrows = $db->num_rows($result);
   $result_per_page = $games_per_page;
   
   include('pagination.php');
   
   $sql = "SELECT * 
          FROM items as Items
		  WHERE item_info !='' AND name != 'Aegis Check' AND name != 'Arcane Ring' 
		  GROUP BY shortname 
		  ORDER BY LOWER(name) ASC 
		  LIMIT $offset, $rowsperpage";
   $result = $db->query($sql);
   
   echo "<br><div align='center'><table class='tableHeroPageTop'><tr>
   <th><div align='center'>Item</div></th>
   <th class='padLeft'>Name</th></tr>";
   while ($row = $db->fetch_array($result,'assoc')) {
   $itemName = convEnt2($row["shortname"]);
   $icon = $row["icon"];
   $itemID = $row["itemid"];
   $itemSummary = $row["item_info"];
   $itemSummary = str_replace("\n\n", "<br>", $itemSummary);
   $itemSummary = str_replace("\n", "<br>", $itemSummary);
   $itemSummary = str_replace("Cost:", "<img alt='' title='Cost' style='vertical-align:middle;' border='0' src='./img/coin.gif'>", $itemSummary);
   echo "<tr>
   <td valign='top' class='padLeft' width='52px' align='left'>
   <a href='item.php?item=$itemID'><img border=0 width='48px' height='48px' src='./img/items/$icon'></a></td>
   <td class='padLeft' align='left'>
   <a onClick='showhide(\"$itemID\");' href='javascript:;'>$itemName</a>
   <div style='display:none;' id = '$itemID'>$itemSummary<br>
   <a href='item.php?item=$itemID'>$lang[more_info]</a><br><br></div>
   </td>
   </tr>";
   
   }
   echo "</table></div><br>";
   
  include('pagination.php');
  include('footer.php');
  ?>