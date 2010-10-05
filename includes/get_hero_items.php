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
	
	if ($ShowHeroMostUsedItems==1)
	{
	echo "<div align='center'><table class='tableA'><tr><th><div align='center'>Frequently used items</div></th></tr>
	<tr><td>";
	
	$sql = getHeroItem1($heroid);
	$result = $db->query($sql);
    $c = 0;
	while ($row = $db->fetch_array($result,'assoc')){
	$mostItemName1 = $row["name"];
	$mostItemIcon1 = $row["icon"];
	$mostItemTotal1 = $row["total"];
	if ($c==0)
	{$mostItem1 = $row["item1"]; $c = 1;} 
	$mostItem11 = $row["item1"];
	echo "
	<img width='64' height='64' alt='' title='$mostItemName1' src='./img/items/$mostItemIcon1'>";

	   }
	$sql = getHeroItem2($heroid,$mostItem1,$mostItem11);
	$result = $db->query($sql);
	$c = 0;
	while ($row = $db->fetch_array($result,'assoc')){
	$mostItemName2 = $row["name"];
	$mostItemIcon2 = $row["icon"];
	$mostItemTotal2 = $row["total"];
	if ($c==0)
	{$mostItem2 = $row["item2"]; $c = 1;} 
	$mostItem22 = $row["item2"];
	echo "
	<img width='64' height='64' alt='' title='$mostItemName2' src='./img/items/$mostItemIcon2'>";

	   }
	   
    $sql = getHeroItem3($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22);
	$result = $db->query($sql);
	$c = 0;
	while ($row = $db->fetch_array($result,'assoc')){
	$mostItemName3 = $row["name"];
	$mostItemIcon3 = $row["icon"];
	$mostItemTotal3 = $row["total"];
	if ($c==0)
	{$mostItem3 = $row["item3"]; $c = 1;} 
	$mostItem33 = $row["item3"];
	echo "
	<img width='64' height='64' alt='' title='$mostItemName3' src='./img/items/$mostItemIcon3'>";

	   }
	   
	$sql = getHeroItem4($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22,$mostItem3,$mostItem33);
	$result = $db->query($sql);
	$c = 0;
	while ($row = $db->fetch_array($result,'assoc')){
	$mostItemName4 = $row["name"];
	$mostItemIcon4 = $row["icon"];
	$mostItemTotal4 = $row["total"];
	if ($c==0)
	{$mostItem4 = $row["item4"]; $c = 1;} 
	$mostItem44= $row["item4"];
	echo "
	<img width='64' height='64' alt='' title='$mostItemName4' src='./img/items/$mostItemIcon4'>";

	   }
	$sql = getHeroItem5($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22,$mostItem3,$mostItem33,$mostItem4,$mostItem44);
	$result = $db->query($sql);
	$c = 0;
	while ($row = $db->fetch_array($result,'assoc')){
	$mostItemName5 = $row["name"];
	$mostItemIcon5 = $row["icon"];
	$mostItemTotal5 = $row["total"];
	if ($c==0)
	{$mostItem5 = $row["item5"]; $c = 1;} 
	$mostItem55= $row["item5"];
	echo "
	<img width='64' height='64' alt='' title='$mostItemName5' src='./img/items/$mostItemIcon5'>";

	   }   
	$sql = getHeroItem6($heroid,$mostItem1,$mostItem11,$mostItem2,$mostItem22,$mostItem3,$mostItem33,$mostItem4,$mostItem44,$mostItem5,$mostItem55);
	$result = $db->query($sql);
	while ($row = $db->fetch_array($result,'assoc')){
	$mostItemName6 = $row["name"];
	$mostItemIcon6 = $row["icon"];
	$mostItemTotal6 = $row["total"];
	$mostItem6 = $row["item6"];
	echo "
	<img width='64' height='64' alt='' title='$mostItemName6' src='./img/items/$mostItemIcon6'>";

	   }      
	   
	 
	   
	}
	
	
	echo "</tr><table></div>";
	
	?>