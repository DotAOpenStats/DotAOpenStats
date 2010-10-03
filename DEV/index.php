<?PHP
	include('config.php');
	//include("../lang/english.php");
	include("../includes/common.php");

		
	define("MAX_SIZE", 20); //20 KB max
	define("VERSION", "1.1.6");
	
	
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Dota OpenStats | ACP</title>
	<link rel="stylesheet" href="'.$admin_style.'" type="text/css" />
	<link href="editor.css" rel="Stylesheet" type="text/css" />
	<script src="editor.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/scripts.js"></script>
	</head>
	<body>
	';
	if ($admin == "admin" AND $password == "admin") {echo "Please change your admin username/password in DEV/config.php"; die;}
	
		  function getExtension($str)
          {
              $i = strrpos($str, ".");
              if (!$i) {
                  return "";
              }
              $l = strlen($str) - $i;
              $ext = substr($str, $i + 1, $l);
              return $ext;
          }
	
	//CHECK LOGIN 
	if (isset($_SESSION['user_name']) AND (isset($_SESSION['user_password'])))
	{
	    if ($_SESSION['user_name'] != $admin)
	 	   {unset($_SESSION['user_name']);}
		   
		    if ($_SESSION['user_password']!=sha1($password))
		        {unset($_SESSION['user_password']);}
	}

	//LOGIN
    if (!isset($_SESSION['user_name']) AND (!isset($_SESSION['user_password'])))
    {
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	
	if (file_exists("../index.php")) {$dotaos = '<tr><td></td><td align="right">
		<a href="../index.php"><span style="font-size:9px;">DotA OpenStats</span></a></td></tr>';}
		
    echo '<div align="center">
<form method="post" action="">
<table style="width:320px;margin-top:100px;" border=0><tr>
<th><b>&nbsp;Login</b></th>
<th></th></tr>

<tr>
<td height="36"><div align="right">Username </div></td>
<td> <div align="left">
&nbsp;<input id="user_name" type="text" name="user_name" maxlength="20"/>
</div>
</td></tr>

<tr>
<td height="36"><div align="right">Password </div></td>
<td> <div align="left">
&nbsp;<input id="user_name" type="password" name="user_pass" maxlength="20" />
</tr><tr><td></td>
        <td height="36">
		<input type="submit" class="inputButton" value="Login" />
 	    </td></tr>'.$dotaos.'
		</table>
		
		</form>
			
</div></div>';}

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	 
	 $un = $_POST["user_name"];
	 $pw = $_POST["user_pass"];
	 //echo "$_POST[user_name] $_POST[user_pass]";
	     if ($admin == $un AND sha1($password) == sha1($pw) AND !$_GET)
	 	 {
	 	 $_SESSION['user_name'] = $_POST['user_name'];
	 	 $_SESSION['user_password'] = sha1($_POST['user_pass']);
		 
		 echo "<div style='float:left;margin-left:2px;margin-top:2px;' align='center'><table style='width:400px;'><tr><th><div align='center'>You have been successfully logged in.</div></th></tr></table></div>";
		 
		 /*$ch = curl_init('http://openstats.iz.rs/version_check.php?check');
		 curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
         curl_exec ($ch);
         curl_close ($ch);*/
		 
	 	 }
		 else
		    {
			 echo "<div align='center'><br>Wrong username or password!<br><br><a href='index.php'>Back to previous page</a></div>";
			unset($_SESSION['user_name']);
			unset($_SESSION['user_password']);
			}
	}

    }
	
	if (isset($_GET['logout']) AND isset($_SESSION['user_name']))
	{
	unset($_SESSION['user_name']);
	unset($_SESSION['user_password']);
	echo "<div align='center'><table style='width:320px;margin-top:100px;'><tr><th><div align='center'>You have been successfully logged out.</div></th></tr><tr><td><a href='index.php'>Back to previous page</a></td></tr></table></div>";
	}
	
	//LOGIN SUCCESSFULLY
	if (isset($_SESSION['user_name'])  AND isset($_SESSION['user_password']) AND $_SESSION['user_password'] == sha1($password) AND $_SESSION['user_name'] == $admin)	
	{
    //BUILD ACP
	echo "<div style='padding-top:4px;padding-bottom:4px;'>Welcome, <b>$_SESSION[user_name]</b> <a href='index.php?logout'>(Logout)</a>
	<p class='alignright'>DotA OpenStats ACP</p></div><hr>";
	
	include("../config.php");
	include("../includes/class.database.php");
	include("../includes/db_connect.php");
	
	$manage_bans = "Manage Bans";
	$edit_h = "Edit Heroes";
	$edit_i = "Manage Items";
	$add_news = "News";
	$os_configuration = "Configuration";
	$dashboard = "Dashboard";
	$back_up = "Backup";
	
	if (isset($_GET['bans'])) {$manage_bans = "<b>Manage Bans</b>";}
	if (isset($_GET['addban'])) {$manage_bans = "<b>Manage Bans</b>";}
	if (isset($_GET['heroes'])) {$edit_h = "<b>Edit Heroes</b>";}
	if (isset($_GET['addhero'])) {$edit_h = "<b>Edit Heroes</b>";}
	if (isset($_GET['items'])) {$edit_i = "<b>Manage Items</b>";}
	if (isset($_GET['additem'])) {$edit_i = "<b>Manage Items</b>";}
	if (isset($_GET['addnews'])) {$add_news = "<b>News</b>";}
	if (isset($_GET['conf'])) {$os_configuration = "<b>Configuration</b>";}
	if (isset($_GET['backup'])) {$back_up = "<b>Backup</b>";}
	
	$sel1 = ""; $sel2 = "";
	
	if ($admin_style == "style2.css") {$sel2 = "selected";}
	if ($admin_style == "style.css") {$sel1 = "selected";}
	
	echo "<table style='margin:4px;'><tr><td align='left'>
	<a href='index.php'>$dashboard</a>
	| <a href='index.php?bans'>$manage_bans</a>
	| <a href='index.php?heroes'>$edit_h</a>
	| <a href='index.php?items'>$edit_i</a>
	| <a href='index.php?addnews'>$add_news</a>
	| <a href='index.php?conf'>$os_configuration</a>
	| <a href='index.php?backup'>$back_up</a>
	| <a href='../index.php'>(Go to DotA OS)</a>
	</td>
	<td width='160px'>Admin style: <select onchange='location = this.options[this.selectedIndex].value;' name='admin_style'>
	<option $sel1 value='index.php?style=default'>default</option>
	<option $sel2 value='index.php?style=dota'>dota</option>
	</select>
	</td>
	</tr></table></table>
	";
	
	    function get_value_of($name)
       {
       $lines = file("../config.php");
	   $val = array();
     foreach (array_values($lines) AS $line)
     {
	   if (strstr($line,"="))
	   {
       list($key, $val) = explode('=', trim($line) );
       if (trim($key) == $name)
          {$val = str_replace(";","",$val); $val = str_replace("'","",$val); 
		  $val = str_replace('"',"",$val);  return $val;}
       }
     }
     return false;
  }
	   
	   function write_value_of($var,$oldval,$newval)
       {
       $contents = file_get_contents('../config.php');
       $regex = '~\\'.$var.'\s+=\s+\''.$oldval.'\';~is';
       $contents = preg_replace($regex, "$var = '$newval';", $contents);
       file_put_contents('../config.php', $contents);
       }
	   
	   function admin_write_value_of($var,$oldval,$newval)
       {
       $contents = file_get_contents('config.php');
       $regex = '~\\'.$var.'\s+=\s+\''.$oldval.'\';~is';
       $contents = preg_replace($regex, "$var = '$newval';", $contents);
       file_put_contents('config.php', $contents);
       }
	
	if (isset($_GET['style']))
	{
	if ($_GET['style'] == "dota") {admin_write_value_of('$admin_style', "$admin_style", "style2.css");}
	if ($_GET['style'] == "default") {admin_write_value_of('$admin_style', "$admin_style", "style.css");}
	
	echo "<br/>Configuration updated successfully.<br/><br/><a href='index.php'>Back to previous page</a><br/><br/>";
	}
	
	
	////////////////////////////////
	//HEROES
	if (isset($_GET['heroes']) AND !isset($_GET['edit']))
	{		
	if (isset($_GET["l"])) 
	{$letter = " AND LOWER(description) LIKE '".safeEscape($_GET["l"])."%'";} else {$letter = "";}
	
	if (isset($_GET["l"]) AND $_GET["l"] == "all") 
	{$letter = "";}

	$sql = "SELECT COUNT(*) FROM heroes WHERE summary != '-' $letter ORDER BY LOWER(description) LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 30;
	
	include('pagination.php');
	
	$alph = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$countAlph = strlen($alph);
	$letters = "";
	for ($i = 0; $i <= $countAlph; $i++) {
	$abc = substr($alph,$i,1);
	if ($i!=0 AND $i !=$countAlph) {$sufix = " , ";} else {$sufix = "";}
	$letters .= "$sufix<a href='index.php?heroes&l=$abc'>".strtoupper($abc)."</a> ";
	}

	echo "<div align='center'>
	<table><tr>
	<td style='text-align:center;font-weight: bold;'>
	<a href='index.php?heroes&l=all'>All</a> 
	| $letters</td></tr></table></div>";

	$sql = "SELECT * FROM heroes WHERE summary != '-' $letter ORDER BY LOWER(description) ASC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	

	
	 echo "<br/><div style='padding-right:3%;text-align:right'><a href='index.php?addhero'><b><img  alt='' style='vertical-align: middle;' width='22px' height='22px' src='../img/items/BTNCloakOfFlames.gif' border=0/>[+] Add hero</b></a></div>
	 <div align='center'>
	 <table style='width:95%' border=1><tr>
	 <th><div align='center'>HID</div></th>
	 <th><div align='center'>Original</div></th>
	 <th><div align='center'>Description</div></th>
	 <th><div align='center'>Summary</div></th>
	 <th><div align='center'>Stats</div></th>
	 <th><div align='center'>Skills</div></th>
	 <th><div align='center'>Action</div></th>
	 </tr>";
	 while ($row = $db->fetch_array($result,'assoc'))
	         {
			 $hid = $row['heroid'];
			 $original = $row['original'];
			 $description = $row['description'];
			 $summ = $row['summary'];
			 $stats = convEnt($row['stats']);
			 $skills = convEnt($row['skills']);
			 
		$summ = str_replace("’","&hellip;",$summ );
		$summ = str_replace("…","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
			 
			 $summ = substr($summ,0,200)."...";
			 
			 //echo "<tr>$hid - $original - $description - $summ - $stats - $skills<br/>";
			 
		$heroimg = "<a title='View $description' href='../hero.php?hero=$hid'><img alt='' width='48px' height='48px' src='../img/heroes/$hid.gif' border=0/></a><br/><br/>";
			 
			 if (!file_exists("../img/heroes/$hid.gif")) {$heroimg = "Missing image:<br/><span style='color:red'>$hid.gif</span><br/><br/>";} 
			 
			 
			 echo "<tr class='row'>
			 <td valign='top'>$hid</td>
			 <td valign='top'><div align='center'>$original</div></td>
			 <td width='140px' valign='top'><div align='center'><a title='Edit $description' href='index.php?heroes&edit=$hid'><b>$description</b></a><br/>
			 $heroimg</div></td>
			 <td width='380px'>$summ<br/><br/></td>
			 <td width='140px' valign='top'>$stats</td>
			 <td valign='top'>$skills</td>
			 <td valign='top'><div align='center'><a title='Edit: $description' href='index.php?heroes&edit=$hid'><b>Edit</b></a></div></td>
			 </tr>";
			 
			 } echo "</table></div><br>";
			 
			 	include('pagination.php');
				 echo "<br>";
	}
	
	////////////////////////////////
	//EDIT HERO
	////////////////////////////////
	
	if (isset($_GET['heroes']) AND isset($_GET['edit']) AND $_SERVER['REQUEST_METHOD'] != 'POST')
	{
	$heroid = EscapeStr($_GET['edit']);
	$sql = "SELECT * FROM heroes WHERE heroid = '$heroid' LIMIT 1";
	$result = $db->query($sql);
	
	if (($db->num_rows($result)) <=0)
	{
	echo "Hero $heroid does not exists!"; die;
	}
	
	$row = $db->fetch_array($result,'assoc');
	
	         $hid = $row['heroid'];
			 $original = $row['original'];
			 $description = $row['description'];
			 $summ = $row['summary'];
			 $stats = $row['stats'];
			 $skills = $row['skills'];
			 
		$summ = str_replace("’","&hellip;",$summ );
		$summ = str_replace("…","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
		
			echo "<script type='text/javascript'>
function confirmDelete(delUrl) {
  if (confirm('Are you sure you want to delete this Hero $description ($hid) ?')) {
    document.location = delUrl;
  }
}
</script>";
		
	
	echo "	<form method='post' action='' enctype='multipart/form-data'>
	<img alt='' src='../img/heroes/$hid.gif'><br/>$description<br/><input type='file' name='image'>  (max 20KB, .gif only)<br/><br/>";

	echo '<div align="center">

	<table style="width:70%"><tr>
	<td width="96px">HeroID</td><td><input size="6" maxlength="6" value="'.$hid.'" name="heroid"></td></tr>
	
	<tr>
	<td width="96px">Original</td><td><input size="6" maxlength="6" value="'.$original.'" name="orig"></td></tr>
	
	<tr>
	<td width="96px">Description</td><td><input size="60" maxlength="60" value="'.$description.'" name="desc"> </td></tr>
	
	<tr>
	<td valign="top" width="96px">Summary</td><td><textarea style="width:600px;height:200px" name="summ">'.$summ.'</textarea><br/><br/></td></tr>
	
	<tr>
	<td valign="top" width="96px">Stats</td><td><textarea style="width:600px;height:90px" name="stats">'.$stats.'</textarea></td></tr>
	
	<tr>
	<td valign="top" width="96px">Skills</td><td><textarea style="width:600px;height:90px" name="skills">'.$skills.'</textarea></td></tr>
	
	<tr><td></td><td>
		<input type="submit" class="inputButton" value="Edit '.$description.'" />
		<div align="right"><a href="javascript:confirmDelete(\'index.php?removeHero='.$hid.'\')">[X] Remove '.$description.'</a></div>
		</td>
			</tr></table>
        </div></form><br/><br/>';
	    }
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

	if (isset($_GET['heroes']) AND isset($_GET['edit']) AND isset($_SESSION['user_name'])  AND isset($_SESSION['user_password']) AND $_SERVER['REQUEST_METHOD'] == 'POST')	
	{
	include("../config.php");
	         $heroid = strtoupper(convEnt2($_POST['heroid']));
			 $original = strtoupper(convEnt2($_POST['orig']));
			 $description = convEnt2($_POST['desc']);
			 $summ = convEnt2($_POST['summ']);
			 $stats = convEnt2($_POST['stats']);
			 $skills = convEnt2($_POST['skills']);
			 
			 $heroid2 = strtoupper(convEnt($_POST['heroid']));
			 $original2 = strtoupper(convEnt($_POST['orig']));
			 $description2 = convEnt($_POST['desc']);
			 $summ2 = convEnt($_POST['summ']);
			 $stats2 = convEnt($_POST['stats']);
			 $skills2 = convEnt($_POST['skills']);
	
			 $image = $_FILES['image']['name'];
              if ($image) {
                  $filename = stripslashes($_FILES['image']['name']);
                  $extension = getExtension($filename);
                  $extension = strtolower($extension);
                  if (($extension != "gif")) {
                      echo '<b>Unknown extension! (not .gif)</b>';
                      $errors = 1;
                  } else {
                      $size = filesize($_FILES['image']['tmp_name']);
                      
                      if ($size > MAX_SIZE * 1024) {
                          echo '<h1>You have exceeded image size limit!</h1>';
                          $errors = 1;
                      }
                      
                      $image_name = strtoupper(convEnt2($_POST['heroid']));
                      $newname = "../img/heroes/" . $image_name.".gif";
					  if (file_exists($newname))
					  {echo "<br/><b>File $image_name aready exists</b>";}
					  
					  if (isset($image_name))
                      {$copied = copy($_FILES['image']['tmp_name'], $newname);
                      if (!$copied) {
                          echo '<b>Image copy failed!</b>';
                          $errors = 1;
                      }
                    }
				  }
              }
			 
			 $sql = "UPDATE heroes SET 
			 heroid = '$heroid', 
			 original = '$original', 
			 description = '$description',
			 summary = '$summ',
			 stats = '$stats',
			 skills = '$skills' 
			 WHERE heroid = '$heroid' LIMIT 1";
			 
			 $result = $db->query($sql);
			 
			 if ($result)
			 {
			 echo "<br>Hero <b>$description</b> successfully updated!<br/><br/>";
			 echo "<br/><br/><a href='index.php?heroes&edit=$heroid'><b>Edit hero $description</b></a>
			 <br/><br/>
			 <a href='index.php?heroes'><b>Back to edit heroes</b></a><br/><br/>
			 <div align='center'><table style='width:60%'><tr>
			 
			 <td><img alt='' src='../img/heroes/$original2.gif'> $description2 </td></tr>
			 <td><p>$summ2</p></td></tr>
			 <td> $stats2 </td></tr><td>$skills2</td>
			 </tr></table></div>";
			 }
	
	}
	
	
	////////////////////////////////////////////
	//BANS
	////////////////////////////////////////////
	if (isset($_GET['bans']) AND !isset($_GET['delete']) AND $_SERVER['REQUEST_METHOD'] != 'POST'){

	$sql = "SELECT COUNT(*) FROM bans ORDER BY id DESC LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 50;
	
	include('pagination.php');
	
	echo '<form method="post" action="">
	<input id="name" type="text" name="name" maxlength="60"/>
	<input type="submit" class="inputButton" value="Search banned users" />
	</form>
	';
	$sql = "SELECT id,name,ip,date,gamename,admin,reason FROM bans ORDER BY date DESC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	
	echo "<div style='padding-right:3%;text-align:right'>
	<a href='index.php?addban'><b><img alt='' style='vertical-align: middle;' width='22px' height='22px' src='../img/items/BTNCritterChicken.gif' border=0/>[+] Add ban</b></a></div>
	<div align='center'>
	<table style='width:98%' border=1><tr>
	<th>ID</th>
	<th>Name</th>
	<th>Game</th>
	<th>Action</th>
	<th><div align='center'>Ip</div></th>
	<th><div align='center'>Date</div></th>
	<th>Banned by</th>
	<th>Reason</th>
	</tr>";
	while ($row = $db->fetch_array($result,'assoc')) {
	
	$date = date($date_format,strtotime($row["date"]));
	$bannedby = strtolower($row["admin"]);
	$ip = substr($row["ip"],0,7)."xx.xx";
	$name = strtolower(trim($row["name"]));
	echo "<tr>
	<td width='48px'>$row[id]</td>
	<td width='130px'><b><a href='../user.php?u=$name'>$row[name]</a></b></td>
	<td width='160px'>$row[gamename]</td>
	<td width='64px'><a title='Delete ban: $row[name]' href='index.php?bans&delete=$row[id]'>Delete</a></td>
	<td width='64px'>$ip</td>
	<td width='150px'><div align='center'>$date</div></td>

	<td width='90px'><a href='../user.php?u=$bannedby'>$row[admin]</a></td>
	<td>$row[reason]</td>
	</tr>";
	}
	echo "</table></div>";
	echo "<br>";
	include('pagination.php');
	}
	
	if (isset($_GET['bans']) AND !isset($_GET['delete']) AND $_SERVER['REQUEST_METHOD'] == 'POST'){
	include("../config.php");
	$searchName = $_POST['name'];
	
	if (strlen($_POST['name']) < 3)
	{
	echo "<br/><br/>Search term have too few characters<br/><br/><a href='index.php?bans'>Back to previous page</a>";
	die;
	}
	
	$sql = "SELECT * FROM bans WHERE LOWER(`name`) LIKE LOWER('%{$searchName}%')  ORDER BY LOWER(`name`) DESC LIMIT 60";
	$result = $db->query($sql);
	    $res = "";
    $banrecords = $db->num_rows($result);
  if ($banrecords>=1)
  {
  	 echo "<div align='center'><br/>Search found: $banrecords maches <a href='index.php?bans'><b>(Back)</b></a>
	 <br><br>
	 <table valign='top' style='width:98%'><tr>
	 <th>ID</th>
	 <th>Username</th>
	 <th>Action</th>
	 <th>Ban Date</th>
	 <th>Game</th>
	 <th>Reason</th>
	 <th>Banned by</th>
	 
	 </tr>";
  

	while ($row = $db->fetch_array($result,'assoc')) {
	$reason = substr($row["reason"],0,50);
    $date = date($date_format,strtotime($row["date"]));
	
	$name = strtolower(trim($row["name"]));
	
	echo "<tr>
	<td>$row[id]</td>
	<td><b><a href='../user.php?u=$name'>$row[name]</a></td>
	<td><a title='Delete ban $row[name]' href='index.php?bans&delete=$row[id]'>[x] Delete</a></td>
	<td>$date</td>
	<td>$row[gamename]</td>
	<td width='250px' title='$row[reason]'>$reason</td>
	<td>$row[admin]</td>
	
	</tr>";
	  }
  }
  else {$res =  "<br/><b>The search term provided yielded no results.</b>";}
  echo "</table></div>$res<br/><br/><a href='index.php?bans'>Back to previous page</a>";
   }

	if (isset($_GET['bans']) AND isset($_GET['delete']) AND $_SERVER['REQUEST_METHOD'] != 'POST'){
	$banid = $_GET["delete"];
	
	$sql = "DELETE FROM bans WHERE id = $banid LIMIT 1";
	$result = $db->query($sql);
	
	if ($result)
	{echo "<div align='center'><table style='width:400px;margin-top:40px;'></tr><th>Ban(ID): $banid successfully deleted! </th></tr><tr><td><a href='index.php?bans'>Back to previous page</a></td></tr></table></div>";}

	}
	
	///////////////////////////////////////
	//ADD BAN
	////////////////////////////////////////
	if (isset($_GET['addban']) AND $_SERVER['REQUEST_METHOD'] != 'POST'){
	echo '<br>
	<div align="center">
	<form method="post"  action=""> 
	<table style="width:50%"><tr><th></th><th>Add ban</th>
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Name*</td><td><input size="40" maxlength="40" value="" name="banname"></td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Game name</td><td><input size="40" maxlength="150" value="" name="bangame"></td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Server</td><td><input size="20" maxlength="60" value="" name="banserver">

</td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Banned by</td><td><input size="40" maxlength="40" value="" name="banby"></td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Reason</td><td><input size="40" maxlength="150" value="" name="banreason"></td></tr>
	
	<td></td>
	<td><input type="submit" name="Submit" class="inputButton" value="Add ban" /></td>
	</tr></table>
    </div></form>
	<br/><br/>';
	}
	
	if (isset($_GET['addban']) AND $_SERVER['REQUEST_METHOD'] == 'POST'){
	$banname = EscapeStr($_POST["banname"]);
	$bangame = EscapeStr($_POST["bangame"]);
	$banby = EscapeStr($_POST["banby"]);
	$banreason = EscapeStr($_POST["banreason"]);
	$banserver = EscapeStr($_POST["banserver"]);
	
	if (strlen($banname) <=3) {echo "<br/><b>Ban Name</b> have too few characters<br/><br/><a href='index.php?addban'>Back to previous page</a>"; die;}
	
	$sql = "INSERT INTO bans(server,name,date,gamename,admin,reason) 
	VALUES('$banserver','$banname',NOW(),'$bangame','$banby','$banreason')";
	
	$result = $db->query($sql);
	if ($result) {echo "<div align='center'><table style='width:400px;margin-top:40px;'></tr><th>$banname successfully banned</th></tr><tr><td><a href='index.php?addban'>Back to previous page</a></td></tr></table></div>";}
	
	}
	
	
	//////////////////////////////////////////////////
	//ADD HERO
	//////////////////////////////////////////////////
	if (isset($_GET['addhero']) AND $_SERVER['REQUEST_METHOD'] != 'POST')
	{
	$upload = "../img/heroes";
	
	$file_directory = dirname("../img/heroes/");
	if (!is_writable($file_directory)) {
              echo "<b>Warning: Image directory is not writable!</b>";
          }

	echo '<br><div align="center">
	<form method="post" enctype="multipart/form-data"  action=""> 
	<table style="width:70%"><tr>
	<td width="96px">HeroID</td><td><input size="6" maxlength="6" value="" name="heroid"> Add image <input type="file" name="image">  (max 20KB, .gif only)</td></tr>
	
	<tr>
	<td width="96px">Original</td><td><input size="6" maxlength="6" value="" name="orig"></td></tr>
	
	<tr>
	<td width="96px">Description</td><td><input size="60" maxlength="60" value="" name="desc"> (Hero name) </td></tr>
	
	<tr>
	<td valign="top" width="96px">Summary</td><td><textarea style="width:600px;height:200px" name="summ"></textarea><br/><br/></td></tr>
	
	<tr>
	<td valign="top" width="96px">Stats</td><td><textarea style="width:600px;height:90px" name="stats"></textarea></td></tr>
	
	<tr>
	<td valign="top" width="96px">Skills</td><td><textarea style="width:600px;height:90px" name="skills"></textarea></td></tr>
	
	<tr><td></td><td>
		<input type="submit" name="Submit" class="inputButton" value="Add hero" /></td>
			</tr></table>
        </div></form><br/><br/>';
	
	
	}
	if (isset($_GET['addhero']) AND $_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST['Submit'])) {

	if ($_POST['heroid'] == "" OR $_POST['orig'] == "" OR $_POST['desc'] == "" OR $_POST['summ'] == "" OR $_POST['stats'] == "" OR $_POST['skills'] == "")
	{echo "<br/><br/>Some fileds are empty!<br/><br/><a href='index.php?addhero'><b>Back to previous page</b></a>"; die;}
	
	         $heroid = strtoupper(convEnt2($_POST['heroid']));
			 $original = strtoupper(convEnt2($_POST['orig']));
			 $description = convEnt2($_POST['desc']);
			 $summ = convEnt2($_POST['summ']);
			 $stats = convEnt2($_POST['stats']);
			 $skills = convEnt2($_POST['skills']);
			 
			 $heroid2 = convEnt($_POST['heroid']);
			 $original2 = convEnt($_POST['orig']);
			 $description2 = convEnt($_POST['desc']);
			 $summ2 = convEnt($_POST['summ']);
			 $stats2 = convEnt($_POST['stats']);
			 $skills2 = convEnt($_POST['skills']);
	
	$sql= "SELECT heroid, original FROM heroes WHERE heroid = '$heroid' LIMIT 1";
	
	$result = $db->query($sql);
	
	if ($db->num_rows($result) >=1) {{echo "<br/><br/>Hero with ID: <b>$heroid</b> already exists<br/><br/><a href='index.php?addhero'><b>Back to previous page</b></a>"; die;}}

	$image = $_FILES['image']['name'];
              if ($image) {
                  $filename = stripslashes($_FILES['image']['name']);
                  $extension = getExtension($filename);
                  $extension = strtolower($extension);
                  if (($extension != "gif")) {
                      echo '<h1>Unknown extension!</h1>';
                      $errors = 1;
                  } else {
                      $size = filesize($_FILES['image']['tmp_name']);
                      
                      if ($size > MAX_SIZE * 1024) {
                          echo '<h1>You have exceeded image size limit!</h1>';
                          $errors = 1;
                      }
                      
                      $image_name = strtoupper(convEnt2($_POST['heroid']));
                      $newname = "../img/heroes/" . $image_name.".gif";
					  if (file_exists($newname))
					  {echo "<br/>File $image_name aready exists"; die;}
					  
                      $copied = copy($_FILES['image']['tmp_name'], $newname);
                      if (!$copied) {
                          echo '<h1>Image copy failed!</h1>';
                          $errors = 1;
                      }
                  }
              }
			  
		
    if (!file_exists("../img/heroes/$heroid.gif"))
	{echo "<br/>Hero image missing. Please upload $heroid.gif into ./img directory.<br/>";}		  

	$sql = "INSERT INTO heroes(heroid,original,description,summary,stats,skills)
	 VALUES('$heroid','$original','$description','$summ','$stats','$skills')";
	 
	 $result = $db->query($sql);

	 if ($result)
	 {echo "Hero $description successfully added!
	 <table><tr>
	 <td>$heroid</td> 
	 <td>$original</td> 
	  <td>$description</td> 
	   <td>$summ</td> 
	    <td>$stats</td> 
		 <td>$skills</td> 
	 </tr></table>";}
	 
	 else {echo "An error occured!";}

	}
	if (isset($_GET['removeHero'])) {
	$rHero = EscapeStr($_GET['removeHero']);
	$sql = "DELETE FROM heroes WHERE heroid = '$rHero' LIMIT 1";
	$result = $db->query($sql);
	
	if ($result)
	{echo "Hero ($rHero) successfully deleted!<br/><br/><a href='index.php?heroes'>Back to previous page</a>";}
	
	}
	
	//////////////////////////////////////////////////////
	///////////  ITEMS  /////////
	
	if (isset($_GET['items']) and !isset($_GET['edit']) AND $_SERVER['REQUEST_METHOD'] != 'POST') {
	
	     if (isset($_GET["l"])) 
	     {$letter = "WHERE LOWER(name) LIKE '".safeEscape($_GET["l"])."%'";} else {$letter = "";}
	
	     if (isset($_GET["l"]) AND $_GET["l"] == "all") 
	     {$letter = "";}
	
	$sql = "SELECT COUNT(itemid) FROM items $letter LIMIT 1";
	
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 50;
	
	include('pagination.php');
	
	$alph = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$countAlph = strlen($alph);
	$letters = "";
	for ($i = 0; $i <= $countAlph; $i++) {
	$abc = substr($alph,$i,1);
	if ($i!=0 AND $i !=$countAlph) {$sufix = " , ";} else {$sufix = "";}
	$letters .= "$sufix<a href='index.php?items&l=$abc'>".strtoupper($abc)."</a> ";
	}

	echo "<div align='center'>
	<table><tr>
	<td style='text-align:center;font-weight: bold;'>
	<a href='index.php?items&l=all'>All</a> 
	| $letters</td></tr></table></div>";
	
	$sql = "SELECT * FROM items $letter ORDER BY LOWER(name) ASC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	
	 echo "<br/><div style='text-align:right;padding-right:3%;'><a href='index.php?additem'><b><img  alt='' style='vertical-align: middle;' width='22px' height='22px' src='../img/items/BTNAbility_Rogue_Sprint.gif' border=0/>[+] Add item</b></a></div>
	 <div align='center'>
	 
	 <table style='width:95%' border=1><tr>
	 <th><div align='center'>Itemid</div></th>
	 <th><div align='center'>Icon</div></th>
	 <th><div align='center'>Name</div></th>
	 <th><div align='center'>Shortname</div></th>
	 <th><div align='center'>Item Info</div></th>
	 
	 
	 <th><div align='center'>Action</div></th>
	 </tr>";
	 while ($row = $db->fetch_array($result,'assoc'))
	         {
			 $itemid = $row['itemid'];
			 $name = $row['name'];
			 $description = $row['item_info'];
			 $shortname = $row['shortname'];
			 $description = convEnt($row['item_info']);
			 $icon = convEnt($row['icon']);
			 
			 $description = substr($description,0,300)."...";
			 
			 //echo "<tr>$hid - $original - $description - $summ - $stats - $skills<br/>";
			 
		$itemimg = "<a href='index.php?items&edit=$itemid'><img alt='' width='32px' height='32px' src='../img/items/$icon' border=0/></a><br/>";
			 
			 
			  echo "<tr class='row'>
			 <td width='32px' valign='top'><div align='center'>$itemid</div></td>
			 <td width='48px'><div align='center'>$itemimg</div></td>
			 
			 <td width='150px' valign='top'>
			 <div align='center'><a href='index.php?items&edit=$itemid'>$name</a></div></td>
			 
			 <td width='140px' valign='top'><div align='center'>$shortname</div><br/></td>
			 <td width='200px' valign='top'><div align='center'><b>$description</b></div></td>

			 <td width='48px' valign='top'><div align='center'><a title='Edit: $description' href='index.php?items&edit=$itemid'><b>Edit</b></a></div></td>
			 </tr>";
			 
			 } echo "</table></div>";
			 echo "<br/>";
			 	include('pagination.php');
	
	}
	
	
	
	if (isset($_GET['additem']) OR isset($_GET['items']) and isset($_GET['edit']) AND $_SERVER['REQUEST_METHOD'] != 'POST') {
	if (isset($_GET['edit']))
	{$item = safeEscape($_GET['edit']);} 	else {$item = "";}

	$name = "";
	$shortname = "";
	$icon = "";
	$icon2 = "";
	
	$button = "Add new item";
	$del_button = '';
	$form = 'enctype="multipart/form-data"';
	$form_image = '<input type="file" name="image">  (max 20KB, .gif only)<br/>';
	$dis = "disabled value = 'click Browse' ";
	
	if (!isset($_GET['additem'])){
	$sql = "SELECT * FROM items WHERE itemid = '$item' LIMIT 1";
	$result= $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
	
	$name = $row["name"];
	$shortname = $row["shortname"];
	$icon = $row["icon"];
	$icon2 = $row["icon"];
	$button = "Edit $shortname";
	$del_button = '<p class="alignright"><a href="javascript:confirmDelete(\'index.php?removeItem='.$item.'\')">[X] Remove '.$item.'</a></p>';
	$form = '';
	$form_image = '';
	$dis = "";
	
	
	if (!file_exists("../img/items/$icon")) {echo "<br/><span style='color:#D60000'>Missing image: /img/items/<b>$icon</b></span><br/>"; $icon2 = "empty.gif";}
	}
	
	echo "<script type='text/javascript'>
function confirmDelete(delUrl) {
  if (confirm('Are you sure you want to delete this $shortname ($item) ?')) {
    document.location = delUrl;
  }
}
</script>";
	
	echo '<form method="post" action="" '.$form.'>
	
	<div align="center">
	<img alt="" width="64px" height="64px" style="vertical-align: middle;" src="../img/items/'.$icon2.'"/>
	<br>'.$name.'<br>
	'.$form_image.'
	<table style="width:55%"><tr><th>Edit items</th><th></th></tr>
	<tr>
	<td width="96px">ItemID</td><td><input size="6" maxlength="6" value="'.$item.'" name="itemid"></td></tr>
	
	<tr>
	<td width="96px">Name</td><td><input size="60" maxlength="60" value="'.$name.'" name="name"></td></tr>
	
	<tr>
	<td width="96px">Short name</td><td><input size="60" maxlength="60" value="'.$shortname.'" name="short"> </td></tr>
	
	<tr>
	<td width="96px">Icon</td><td><input '.$dis.' size="60" maxlength="60" value="'.$icon.'" name="icon">
     <img alt="" width="26px" height="26px" style="vertical-align: middle;" src="../img/items/'.$icon.'"/>
	</td></tr>

	<tr><td></td><td>
		<input type="submit" class="inputButton" value="'.$button .'" />
		'.$del_button.'
		</td>
			</tr></table>
        </div></form><br/><br/>';
	
	}
	
	
	if (isset($_GET['additem']) OR isset($_GET['items']) and isset($_GET['edit'])) {
	//$item = $_GET['edit'];
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$item = EscapeStr($_POST['itemid']);
	$name = EscapeStr($_POST['name']);
	$shortname = EscapeStr($_POST['short']);
	$icon = EscapeStr($_POST['icon']);
	
	if (!file_exists("../img/items/$icon")) {echo "Missing image: /img/items/<b>$icon</b><br/>";}

	if (strlen($item)>=4 AND strlen($name)>=3 AND strlen($shortname)>=3)
	{
	if (!isset($_GET['additem']))
	{
	$sql = "UPDATE items 
	SET itemid = '$item', name = '$name', shortname = '$shortname', icon = '$icon' 
	WHERE itemid = '$item' LIMIT 1";}
	
	else
	{
	$image = $_FILES['image']['name'];
              if ($image) {
                  $filename = stripslashes($_FILES['image']['name']);
                  $extension = getExtension($filename);
                  $extension = strtolower($extension);
                  if (($extension != "gif")) {
                      echo '<h1>Unknown extension!</h1>';
                      $errors = 1;
                  } else {
                      $size = filesize($_FILES['image']['tmp_name']);
                      
                      if ($size > MAX_SIZE * 1024) {
                          echo '<h1>You have exceeded image size limit!</h1>';
                          $errors = 1;
                      }
                      
                      $image_name = EscapeStr($filename);
                      $newname = "../img/items/" . $image_name."";
					  if (file_exists($newname))
					  {echo "File $image_name aready exists<br/>";}
					  
                      $copied = copy($_FILES['image']['tmp_name'], $newname);
                      if (!$copied) {
                          echo '<h1>Image copy failed!</h1>';
                          $errors = 1;
                      }
                  }
              }
			  
	$icon = $image_name;
	$sql = "INSERT INTO items(itemid,name,shortname,icon) 
	VALUES('$item','$name','$shortname','$icon') ";
	
	}
	
	$result = $db->query($sql);
	
	if ($result)  {echo "<br/><b>Item $item updated successfully.</b><br/><br/><a href='index.php?items&edit=$item'>Back to previous page</a>";}
	
	} else {echo "<br/>Some fields are empty or have too few characters!<br/><br/><a href='javascript: history.go(-1);'>Back to previous page</a>";}
	
	 }
	}
	
	if (isset($_GET['removeItem']) AND $_SERVER['REQUEST_METHOD'] != 'POST')
	{
    $item = EscapeStr($_GET['removeItem']);
	
	$sql = "DELETE FROM items WHERE itemid = '$item' LIMIT 1";
	$result = $db->query($sql);
	if ($result)  {echo "<br/><b>Item $item removed successfully.</b><br/><br/><a href='index.php?items'>Back to previous page</a>";}
	
	}
	
	//////////////////////////////////////////////////
	//                     NEWS
	//////////////////////////////////////////////////
	
	if (isset($_GET['addnews'])  AND $_SERVER['REQUEST_METHOD'] != 'POST' AND !isset($_GET['delete_news'])) {
	$button = "Publish news";
	if (isset($_GET['edit_news']))
	{$edit_id = safeEscape($_GET['edit_news']);
	
	$sql2 = "SELECT * FROM news WHERE news_id = $edit_id LIMIT 1";
	$result2 = $db->query($sql2);
	if ($db->num_rows($result2) <=0) {echo "Error: News $edit_id doesnt exists"; die;}
	$button = "Update news";
	$row2 = $db->fetch_array($result2,'assoc');
	$news_content = $row2["news_content"];
	$news_content = BBDecode($news_content);
	$news_content = str_replace("&lt;br&gt;", "", $news_content);
	$news_content = str_replace("<br>", "\n", $news_content);
	$news_title = trim($row2["news_title"]);
	$news_date = date($date_format,strtotime($row2["news_date"]));
	//echo "NEWS: $news_content";
	
	} else {$news_content = ""; $news_title = "";}

	$sql = "SELECT COUNT(news_id) FROM news LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 15;

	//NOTE:  ->>> NEED .jS (to lazy for now :D )
	echo "<script type='text/javascript'>
function confirmDelete(delUrl) {
  if (confirm('Are you sure you want to delete this news?')) {
    document.location = delUrl;
  }
}
</script>";

	echo '
	<br/><div align="center">
	<form name="myForm" method="post" action=""> 
	<div align="center"><b>News title:</b></div>
	<div align="center"><input maxlength="90" type="text" size="90" value="'.$news_title.'" name="new_subject" />
	</div>
	<div class="richeditor">
		<div class="editbar">		
			<button title="bold" onclick="AddTag(\'b\');" type="button" style="background-image:url(\'img/editor/text_bold.gif\');"></button>
			<button title="italic" onclick="AddTag(\'i\');" type="button" style="background-image:url(\'img/editor/text_italic.gif\');"></button>
			<button title="underline" onclick="AddTag(\'u\');" type="button" style="background-image:url(\'img/editor/text_underline.gif\');"></button>
			<button title="strikethrough" onclick="AddTag(\'s\');" type="button" style="background-image:url(\'img/editor/strikethrough.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="hyperlink" onclick="doLink();" type="button" style="background-image:url(\'img/editor/url.gif\');"></button>
			<button title="image" onclick="doImage();" type="button" style="background-image:url(\'img/editor/img.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="big font" onclick="doSize(\'size=18\',\'size\');" type="button" style="background-image:url(\'img/editor/font_plus.gif\');"></button>
			<button title="small font" onclick="doSize(\'size=9\',\'size\');" type="button" style="background-image:url(\'img/editor/font_minus.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="list" onclick="AddTag(\'li\');" type="button" style="background-image:url(\'img/editor/icon_list.gif\');"></button>
			<button title="align left" onclick="AddTag(\'left\');" type="button" style="background-image:url(\'img/editor/text_align_left.gif\');"></button>
			<button title="align center" onclick="AddTag(\'center\');" type="button" style="background-image:url(\'img/editor/text_align_center.gif\');"></button>
			<button title="align right" onclick="AddTag(\'right\');" type="button" style="background-image:url(\'img/editor/text_align_right.gif\');"></button>
			<button title="justify" onclick="AddTag(\'justify\');" type="button" style="background-image:url(\'img/editor/text_align_justify.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			
			<button title="spoiler" onclick="AddTag(\'spoiler\');" type="button" style="background-image:url(\'img/editor/spoiler.gif\');"></button>

			<button title="color" onclick="showColorGrid2(\'none\')" type="button" style="background-image:url(\'img/editor/colors.gif\');"></button><span id="colorpicker201" class="colorpicker201"></span>

			<button title="youtube" onclick="InsertYoutube();" type="button" style="background-image:url(\'img/editor/icon_youtube.gif\');"></button>
	</div>
	
	 <textarea class="reply" id="reply" name="reply" style="padding-top:4px;height:260px;width:680px;">'.$news_content.'</textarea>
    <br>
	<input type="submit" class="inputButton" value="'.$button.'" />
	</div>';
	
		if (isset($_GET['edit_news'])){	
		$pre_title = $news_title;
		$pre_news = BBCode($news_content);
		$pre_news = str_replace("\n","<br>",$pre_news);
		
		echo "<hr><table style='width:70%;'><tr>
		<th><div align='left'>Preview</div></th><th></th></tr>
		<tr>
		<td><p class='alignleft'><b>$news_title</b></p> <p class='alignright'>$news_date</p></td></tr>
		<tr>
		<td style='padding:8px;'>$pre_news </td>
		
		</tr></table><hr><br/>";
		}
	
	include('pagination.php');
	$sql = "SELECT * FROM news ORDER BY news_date DESC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	 echo "<br><div align='center'><b>NEWS</b><table border=1 style='width:95%;'><tr>
	 <th>ID</th>
	 <th>Content</th>
	 <th><div align='center'>Date</div></th>
	 <th><div align='center'>Action</div></th>
	 </tr>";
	 while ($row = $db->fetch_array($result,'assoc')) {
	 $dateis = date($date_format,strtotime($row["news_date"]));
	 $title = "$row[news_title]";
	 $text = "$row[news_content]";
	 $text = BBDecode($text);
	 $text = substr($text,0,200)." ...";
	echo "<tr>
	<td valign='top' width='40px'><div align='left'>$row[news_id]</div></td>
	<td valign='top' width='250px'><b><a href='index.php?addnews&edit_news=$row[news_id]'>$title</a></b><br><hr style='width:50%;'>$text<br><br><br></td>
	<td valign='top' width='80px'><div align='center'>$dateis</div></td>
	<td valign='top' width='80px'><div align='center'><a href='index.php?addnews&edit_news=$row[news_id]'>Edit</a>
	| <a href='Javascript:confirmDelete(\"index.php?delete_news=$row[news_id]\")'>Delete</a></div>
	</td>
	</tr>
	<tr><th></th><th></th><th></th><th></th></tr>";
	 
	 } echo "</table></div><br/>";
	 include('pagination.php');

	}
	
     //////////////////////////////////////////////////
	//                    POST NEWS
	//////////////////////////////////////////////////
	if (isset($_GET['addnews'])  AND $_SERVER['REQUEST_METHOD'] == 'POST' AND !isset($_GET['delete_news'])) {
	//ADD NEWS
	if (!isset($_GET['edit_news']))
	{

	$mytext = trim($_POST['reply']);
	$mytext = my_nl2br($mytext);
	$mytext = convEnt2($mytext);
	$mytext = BBCode($mytext);
	
	$mytitle = trim($_POST['new_subject']); 
	$mytitle = my_nl2br($mytitle);
	$mytitle = convEnt2($mytitle);

	//if(get_magic_quotes_gpc()==0) $text=addslashes($text);
	$mytext = str_replace("\n", "<br>", $mytext);

	if (strlen($mytext) <=5) {echo "<br/>News content have to few characters!<br/>"; die;}
	if (strlen($mytitle) >=91) {echo "<br/>News title have to many characters!<br/>"; die;}
	
	
	$sql = "INSERT INTO news (news_content,news_title,news_date) VALUES
	('$mytext','$mytitle',NOW())";
	
	$result = $db->query($sql);
	
	$news_id = $db->get_insert_id();
	
	if ($result) {echo "<div align='center'>
	<table style='width:320px;margin-top:32px;'>
	<tr><th>News successfully added!</th></tr>
	<tr>
	<td><a href='index.php?addnews&edit_news=$news_id'>Back to previous page</a></td>
	</tr></table></div>";}
	  }
	  	//EDIT NEWS
		if (isset($_GET['edit_news']))
		{
		$update = "";
		
		$news_id = safeEscape($_GET['edit_news']);
		$mytext = trim($_POST['reply']);
		//$mytext = my_nl2br($mytext);
		$mytext = convEnt2($mytext);
		$mytext = BBCode($mytext);
		$mytext = str_replace("\n", "<br>", $mytext);
		
		$mytitle = trim($_POST['new_subject']);
		$mytitle = my_nl2br($mytitle);
		$mytitle = convEnt2($mytitle);
		$mytitle = BBCode($mytitle);
		
		//$update.=", news_date = NOW()";
		
		$sql = "UPDATE news SET news_content = '$mytext', news_title='$mytitle' $update WHERE news_id = $news_id  LIMIT 1";
		
		$result = $db->query($sql);
		if ($result) {echo "<div align='center'>
	<table style='width:320px;margin-top:32px;'>
	<tr>
	<th>News successfully updated!</th></tr><tr><td><a href='index.php?addnews&edit_news=$news_id'>Back to previous page</a></td></tr></table></div>";}
		}
		
	}

	////////////////////////////// DELETE NEWS ///////////////////////////////
	if (isset($_GET['delete_news'])  AND $_SERVER['REQUEST_METHOD'] != 'POST') {
	$del_news = safeEscape($_GET['delete_news']);
	$sql = "DELETE FROM news WHERE news_id = $del_news LIMIT 1";
	
	$result = $db->query($sql);
	
	if ($result) {echo "<div align='center'>
	<table style='width:320px;margin-top:32px;'>
	<tr><th>News successfully deleted!</th></tr><tr><td>
	<a href='index.php?addnews'>Back to previous page</a></td></tr></table></div>";}
	}
	
	
	/////////////////////////////////////////////////////////
   //                  CONFIGURATION                      //
  /////////////////////////////////////////////////////////
  
    if (isset($_GET['conf']))
	   {
	   if (!file_exists("../config.php")) {echo "Missing config.php"; die;}
	   if (!is_writable("../config.php")) {echo "config.php is not writable"; die;}
	   
	  $server = get_value_of('$server');
      $server = trim($server);
	  
	  $username = get_value_of('$username');
      $username = trim($username);
	  
	  $password = get_value_of('$password');
      $password = trim($password);
	  
	  $database = get_value_of('$database');
      $database = trim($database);
	  
	  ////////////////////////////
	   
	  $default_style = get_value_of('$default_style');
      $default_style = trim($default_style);
	  
	  $default_language = get_value_of('$default_language');
      $default_language = trim($default_language);
	  
	  $bans_per_page = get_value_of('$bans_per_page');
      $bans_per_page = trim($bans_per_page);
	  
	  $games_per_page = get_value_of('$games_per_page');
      $games_per_page = trim($games_per_page);
	  
	  $heroes_per_page = get_value_of('$heroes_per_page');
      $heroes_per_page = trim($heroes_per_page);
	  
	  $top_players_per_page = get_value_of('$top_players_per_page');
      $top_players_per_page = trim($top_players_per_page);
	  
	  $news_per_page = get_value_of('$news_per_page');
      $news_per_page = trim($news_per_page);
	  
	  $search_limit = get_value_of('$search_limit');
      $search_limit = trim($search_limit);
	  
	  $top_stats = get_value_of('$top_stats');
      $top_stats = trim($top_stats);
	  
	  $displayUsersDisconnects = get_value_of('$displayUsersDisconnects');
      $displayUsersDisconnects = trim($displayUsersDisconnects);
	  
	  if ($displayUsersDisconnects == "1") {$udy = "checked";$udn = "";} else {
          $udy = "";
          $udn = "checked";}
		  
		  
	  $UserAchievements = get_value_of('$UserAchievements');
      $UserAchievements = trim($UserAchievements);
		  
		  if ($UserAchievements == "1") {$acy = "checked";$acn = "";} else {
          $acy = "";
          $acn = "checked";}
		  
		  
	  $AllTimeStats = get_value_of('$AllTimeStats');
      $AllTimeStats = trim($AllTimeStats);
		  
		  if ($AllTimeStats == "1") {$atsy = "checked";$atsn = "";} else {
          $atsy = "";
          $atsn = "checked";}	  
	
      $ScoreStart = get_value_of('$ScoreStart');
      $ScoreStart = trim($ScoreStart);	
	  $ScoreWins = get_value_of('$ScoreWins');
      $ScoreWins = trim($ScoreWins);	
	  $ScoreLosses = get_value_of('$ScoreLosses');
      $ScoreLosses = trim($ScoreLosses);
      $ScoreDisc = get_value_of('$ScoreDisc');
      $ScoreDisc = trim($ScoreDisc);	  
		  
	  $replayLocation = get_value_of('$replayLocation');
      $replayLocation = trim($replayLocation);
	  
	  $max_pagination_link = get_value_of('$max_pagination_link');
      $max_pagination_link = trim($max_pagination_link);
	  
	  $scoreFormula = get_value_of('$scoreFormula');
      $scoreFormula = trim($scoreFormula);
	  
	  $minPlayedRatio = get_value_of('$minPlayedRatio');
      $minPlayedRatio = trim($minPlayedRatio);
	  
	  $minGamesPlayed = get_value_of('$minGamesPlayed');
      $minGamesPlayed = trim($minGamesPlayed);
	  
	  $date_format = get_value_of('$date_format');
      $date_format = trim($date_format);
	  
	  $monthly_stats = get_value_of('$monthly_stats');
      $monthly_stats = trim($monthly_stats);
	  
	  $monthRow1 = get_value_of('$monthRow1');
      $monthRow1 = trim($monthRow1);

	  $monthRow2 = get_value_of('$monthRow2');
      $monthRow2 = trim($monthRow2);
	  
	  $monthRow3 = get_value_of('$monthRow3');
      $monthRow3 = trim($monthRow3);
	  
	  $monthRow4 = get_value_of('$monthRow4');
      $monthRow4 = trim($monthRow4);
	  
	  $monthRow5 = get_value_of('$monthRow5');
      $monthRow5 = trim($monthRow5);
	  
	  	  if ($monthRow1 == "1") {$r1y = "checked";$r1n = "";} else {
          $r1y = "";
          $r1n = "checked";}
		  
		  if ($monthRow2 == "1") {$r2y = "checked";$r2n = "";} else {
          $r2y = "";
          $r2n = "checked";}
		  
		  if ($monthRow3 == "1") {$r3y = "checked";$r3n = "";} else {
          $r3y = "";
          $r3n = "checked";}
		  
		  if ($monthRow4 == "1") {$r4y = "checked";$r4n = "";} else {
          $r4y = "";
          $r4n = "checked";}
		  
		  if ($monthRow5 == "1") {$r5y = "checked";$r5n = "";} else {
          $r5y = "";
          $r5n = "checked";}
	  
	  $DaysOnMonthlyStats = get_value_of('$DaysOnMonthlyStats');
      $DaysOnMonthlyStats = trim($DaysOnMonthlyStats);
	  	  
	  if ($DaysOnMonthlyStats == "1") {$daymy = "checked";$daymn = "";} else {
          $daymy = "";
          $daymn = "checked";}
	  
	  $TopRanksOnMonthly = get_value_of('$TopRanksOnMonthly');
      $TopRanksOnMonthly = trim($TopRanksOnMonthly);
	  
	  	  
	  if ($TopRanksOnMonthly == "1") {$try = "checked";$trn = "";} else {
          $try = "";
          $trn = "checked";}
		  
		  
	  $UserPointsOnGamePage = get_value_of('$UserPointsOnGamePage');
      $UserPointsOnGamePage = trim($UserPointsOnGamePage);
	  	  
	  if ($UserPointsOnGamePage == "1") {$upy = "checked";$upn = "";} else {
          $upy = "";
          $upn = "checked";}
		  
	  $AccuratePointsCalculation = get_value_of('$AccuratePointsCalculation');
      $AccuratePointsCalculation = trim($AccuratePointsCalculation);
	  	  
	  if ($AccuratePointsCalculation == "1") {$apcy = "checked";$apcn = "";} else {
          $apcy = "";
          $apcn = "checked";}
		  
		  
	  $HideBannedUsersOnTop = get_value_of('$HideBannedUsersOnTop');
      $HideBannedUsersOnTop = trim($HideBannedUsersOnTop);
	  	  
	  if ($HideBannedUsersOnTop == "1") {$hbuy = "checked";$hbun = "";} else {
          $hbuy = "";
          $hbun = "checked";}	  
		  
		  
      $ScoreMethod = get_value_of('$ScoreMethod');
      $ScoreMethod = trim($ScoreMethod); 
      if ($ScoreMethod == "1") {$scy = "checked";$scn = "";} else {
          $scy = "";
          $scn = "checked";}	  
		  
	  
	  $head_admin = get_value_of('$head_admin');
      $head_admin = trim($head_admin);
	  
	  $bot_name = get_value_of('$bot_name');
      $bot_name = trim($bot_name);
	  
	  $LEAVER = get_value_of('$LEAVER');
      $LEAVER = trim($LEAVER);
	  
	  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	  echo '<div align="center"> <form method="post" action="">
	  <table style="width:690px;" border=1><tr><th>Configuration</th><th></th></tr>
	  <tr>
	  <td width="160px">Default Style</td>
	  <td><input value="'.$default_style.'" type="text" name="style" size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Language</td>
	  <td><input value="'.$default_language.'" type="text" name="lang" size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Head admin</td>
	  <td><input value="'.$head_admin.'" type="text" name="head" maxlength=30 size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Bot Name</td>
	  <td><input value="'.$bot_name.'" type="text" name="bot" maxlength=30 size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Bans per page</td>
	  <td><input value="'.$bans_per_page.'" type="text" name="bans" maxlength=5 size=5 /> The number of results returned in a page on the bans page</td></tr>
	  
	  <tr>
	  <td width="160px">Games per page</td>
	  <td><input value="'.$games_per_page.'" type="text" name="games" size=5 /> The number of results returned in a page on the game history page</td></tr>
	  
	  <tr>
	  <td width="160px">Heroes per page</td>
	  <td><input value="'.$heroes_per_page.'" type="text" name="heroes" size=5 /> The number of results returned in a page on the hero statistics page</td></tr>
	  
	  <tr><th>Top page</th><th></th></tr>

	  <tr>
	  <td width="160px">Top Players per page</td>
	  <td><input value="'.$top_players_per_page.'" type="text" name="topplayers" size=5 /> The number of results returned in a page on the top players page</td></tr>	  
	  	  
	  <tr>
	  <td width="160px">All Time Stats on Top page</td>
	  <td>
	  <input type="radio" name="ats" '.$atsy.' value="1" /> Yes
	  <input type="radio" name="ats" '.$atsn.' value="0" /> No 
	  | (Show All Time Stats on Top page)</td></tr>
	    
	  <tr>
	  <td width="160px">Players on top stats</td>
	  <td><input value="'.$top_stats.'" type="text" name="topstats" size=5 /> The number of entries in each highscore list on all time top stats</td></tr>
	  
	  <tr>
	  <td width="160px">Hide Banned Users</td>
	  <td>
	  <input type="radio" name="hbu" '.$hbuy.' value="1" /> Yes
	  <input type="radio" name="hbu" '.$hbun.' value="0" /> No 
	  | (Hide banned users on Top and Monthly page)</td></tr>
	  
	  <tr><th>Misc</th><th></th></tr>
	  
	  <tr>
	  <td width="160px">News per page</td>
	  <td><input value="'.$news_per_page.'" type="text" name="news" size=5 /> The number of news returned on the front page</td></tr>
	  
	  <tr>
	  <td width="160px">Search limit</td>
	  <td><input value="'.$search_limit.'" type="text" name="search" size=5 /> The max. number of results returned on a search page</td></tr>
	  
	  <tr>
	  <td width="150px">Pagination links</td>
	  <td><input value="'.$max_pagination_link.'" type="text" name="pagination" size=5 /> Page links after and before current page</td></tr>
	  
	  <!--<tr>
	  <td width="160px">Score formula</td>
	  <td><textarea id="formula" name="formula" style="width:500px;height:64px;" >'.$scoreFormula.'</textarea></td></tr>-->
	  
	  <tr>
	  <td valign="top" width="160px">Min Played Ratio</td>
	  <td><input value="'.$minPlayedRatio.'" type="text" name="minratio" size=5 /> <br>Minimal ratio (lefttime/duration) that a player/hero has to complete a game to be counted as win/loss</td></tr>
	  
	  <tr>
	  <td width="160px">Min Games Played</td>
	  <td><input value="'.$minGamesPlayed.'" type="text" name="gamesplayed" size=5 /> Min. games played in order to be displayed on Top Players page</td></tr>
	  
	  <tr>
	  <td width="160px">Date Format</td>
	  <td><input value="'.$date_format.'" type="text" name="date" size=30 /> (<b>'.date($date_format).'</b>) - PHP <a  target="_blank" href="http://php.net/manual/en/function.date.php"><b>date()</b></a> function</td></tr>
	  
	  <tr>
	  <td width="160px">Display user disconnects</td>
	  <td>
	  <input type="radio" name="ud" '.$udy.' value="1" /> Yes
	  <input type="radio" name="ud" '.$udn.' value="0" /> No 
	  | (Display user disconnects on user page)</td></tr>
	  
	  	  <tr>
	  <td width="160px">Enable user achievements</td>
	  <td>
	  <input type="radio" name="ach" '.$acy.' value="1" /> Yes
	  <input type="radio" name="ach" '.$acn.' value="0" /> No 
	  | (Display user achievements on user page)</td></tr>
	  
	  <tr>
	  <td width="160px">Replay Location</td>
	  <td><input value="'.$replayLocation.'" type="text" name="replay" size=30 /> (http://myDotaOsSite.com/<b>'.$replayLocation.'</b>)</td></tr>
	  
	  	  <tr><th>Monthly</th><th></th></tr>
	  
	  <tr>
	  <td width="160px">Monthly Stats</td>
	  <td><input value="'.$monthly_stats.'" type="text" name="monthly" maxlength=5 size=5 /> Number of entries in each highscore list</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 1</td>
	  <td>
	  <input type="radio" name="mr1" '.$r1y.' value="1" /> Yes
	  <input type="radio" name="mr1" '.$r1n.' value="0" /> No 
	  | (Top: Kills,Assists,Deaths,Creep Kills,Creep Denies)</td></tr>
      
	  <tr>
	  <td width="160px">Monthly row 2</td>
	  <td>
	  <input type="radio" name="mr2" '.$r2y.' value="1" /> Yes
	  <input type="radio" name="mr2" '.$r2n.' value="0" /> No 
	  | (Top Gold,Neutrals,Towers,Rax,Couriers Kills)</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 3</td>
	  <td>
	  <input type="radio" name="mr3" '.$r3y.' value="1" /> Yes
	  <input type="radio" name="mr3" '.$r3n.' value="0" /> No 
	  | (Best K/D, A/D Ratio, Most games, Best Win %,Top Stay %)</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 4</td>
	  <td>
	  <input type="radio" name="mr4" '.$r4y.' value="1" /> Yes
	  <input type="radio" name="mr4" '.$r4n.' value="0" /> No 
	  | (Most Kills,Assists,Deaths,Creeps,Denies)</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 5</td>
	  <td>
	  <input type="radio" name="mr5" '.$r5y.' value="1" /> Yes
	  <input type="radio" name="mr5" '.$r5n.' value="0" /> No 
	  | (AVG Kills,Assists,Deaths,Creeps,Denies)</td></tr>
	  
	  <tr>
	  <td width="160px">Display days on monthly stats</td>
	  <td>
	  <input type="radio" name="ddm" '.$daymy.' value="1" /> Yes
	  <input type="radio" name="ddm" '.$daymn.' value="0" /> No 
	  | (Display statistics for each day on monthly stats)</td></tr>
	  
	  <tr>
	  <td width="160px">Display top ranks</td>
	  <td>
	  <input type="radio" name="dtr" '.$try.' value="1" /> Yes
	  <input type="radio" name="dtr" '.$trn.' value="0" /> No 
	  | (Display top ranks by score on monthly stats)</td></tr>
	  
	  <tr><th>Points and Score</th><th></th></tr>
	  
	  <tr>
	  <td width="160px">Score Method</td>
	  <td>
	  <input type="radio" name="scm" '.$scy.' value="1" /> Type 1 | 
	  <input type="radio" name="scm" '.$scn.' value="2" /> Type 2
	  <br><b>Score Type 1</b> Using score formula. <br><b>Score Type 2</b> This method use league system to calculate user score. <br>Eg. wins*5 - losses*3 - disconnects*10</td></tr>
	  
	  <tr>
	  <td width="160px"><b>Method 2</b></td>
	  <td>Settings</td></tr>
	  
	  
	  <tr>
	  <td width="160px">Start Score</td>
	  <td><input value="'.$ScoreStart.'" type="text" name="scstart" maxlength=10 size=10 /> (User start score)</td></tr>
	  
	  <tr>
	  <td width="160px">Wins Points</td>
	  <td><input value="'.$ScoreWins.'" type="text" name="scwins" maxlength=5 size=5 /> (+Points when user won)</td></tr>
	  
	  <tr>
	  <td width="160px">Lose Points</td>
	  <td><input value="'.$ScoreLosses.'" type="text" name="scloss" maxlength=5 size=5 /> (-Points when user  lose)</td></tr>
	  
	  <tr>
	  <td width="160px">Disconnect Points</td>
	  <td><input value="'.$ScoreDisc.'" type="text" name="scdisc" maxlength=5 size=5 /> (-Points when user disconnect before game end)</td></tr>
	  
	  <tr>
	  <td width="160px"><b>Game page</b></td>
	  <td></td></tr>
	  
	  <tr>
	  <td width="160px">User points per game</td>
	  <td>
	  <input type="radio" name="upg" '.$upy.' value="1" /> Yes
	  <input type="radio" name="upg" '.$upn.' value="0" /> No 
	  | (Show Points gained for each game for all users on game page)</td></tr>
	  
	  <tr>
	  <td width="160px">Accurate points calculation</td>
	  <td>
	  <input type="radio" name="apc" '.$apcy.' value="1" /> Yes
	  <input type="radio" name="apc" '.$apcn.' value="0" /> No 
	  | <a title="If this option above is enabled, points  will be calculated accurately (from database)
	  It will calculate total score before and after selected game.
	  This will also take up much more resources">(<b>*</b>Points from database. <b>This take up much more resources</b><br><span style="color:red">Only for Score Method 1</span)</a></td></tr>
	  
	  <tr><th>Database</th><th></th></tr>
	  <tr>
	  <td width="160px">Server</td>
	  <td><input value="'.$server.'" type="text" name="server" maxlength=60 size=30 /> </td></tr>
	  <tr>
	  <td width="160px">DB Username</td>
	  <td><input value="'.$username.'" type="text" name="username" maxlength=50 size=30 /> </td></tr>
	  <tr>
	  <td width="160px">DB Password</td>
	  <td><input value="'.$password.'" type="password" name="password" maxlength=50 size=30 /> </td></tr>
	  <tr>
	  <td width="160px">Database</td>
	  <td><input value="'.$database.'" type="text" name="database" maxlength=50 size=30 /> </td></tr>
 
	  </table><br/><input type="submit" class="inputButton" value="Submit" /><br/><br/>';
	  
	    }
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  write_value_of('$default_style', "$default_style", $_POST['style']);
	  write_value_of('$default_language', "$default_language", $_POST['lang']);
	  write_value_of('$bans_per_page', "$bans_per_page", $_POST['bans']);
	  write_value_of('$games_per_page', "$games_per_page", $_POST['games']);
	  write_value_of('$heroes_per_page', "$heroes_per_page", $_POST['heroes']);
	  write_value_of('$top_players_per_page', "$top_players_per_page", $_POST['topplayers']);
	  write_value_of('$news_per_page', "$news_per_page", $_POST['news']);
	  write_value_of('$search_limit', "$search_limit", $_POST['search']);
	  write_value_of('$top_stats', "$top_stats", $_POST['topstats']);
	  write_value_of('$HideBannedUsersOnTop', "$HideBannedUsersOnTop", $_POST['hbu']);
	  write_value_of('$ScoreMethod', "$ScoreMethod", $_POST['scm']);
	  write_value_of('$ScoreStart', "$ScoreStart", $_POST['scstart']);
	  write_value_of('$ScoreWins', "$ScoreWins", $_POST['scwins']);
	  write_value_of('$ScoreLosses', "$ScoreLosses", $_POST['scloss']);
	  write_value_of('$ScoreDisc', "$ScoreDisc", $_POST['scdisc']);
	  
	  write_value_of('$max_pagination_link', "$max_pagination_link", $_POST['pagination']);
	  write_value_of('$minPlayedRatio', "$minPlayedRatio", $_POST['minratio']);
	  write_value_of('$minGamesPlayed', "$minGamesPlayed", $_POST['gamesplayed']);
	  write_value_of('$date_format', "$date_format", $_POST['date']);
	  write_value_of('$monthly_stats', "$monthly_stats", $_POST['monthly']);
	  write_value_of('$monthRow1', "$monthRow1", $_POST['mr1']);
	  write_value_of('$monthRow2', "$monthRow2", $_POST['mr2']);
	  write_value_of('$monthRow3', "$monthRow3", $_POST['mr3']);
	  write_value_of('$monthRow4', "$monthRow4", $_POST['mr4']);
	  write_value_of('$monthRow5', "$monthRow5", $_POST['mr5']);
	  write_value_of('$DaysOnMonthlyStats', "$DaysOnMonthlyStats", $_POST['ddm']);
	  write_value_of('$TopRanksOnMonthly', "$TopRanksOnMonthly", $_POST['dtr']);
	  write_value_of('$head_admin', "$head_admin", $_POST['head']);
	  write_value_of('$bot_name', "$bot_name", $_POST['bot']);
	  write_value_of('$replayLocation', "$replayLocation", $_POST['replay']);
	  write_value_of('$UserAchievements', "$UserAchievements", $_POST['ach']);
	  write_value_of('$UserPointsOnGamePage', "$UserPointsOnGamePage", $_POST['upg']);
	  write_value_of('$AccuratePointsCalculation', "$AccuratePointsCalculation", $_POST['apc']);
	  
	  write_value_of('$server', "$server", $_POST['server']);
	  write_value_of('$username', "$username", $_POST['username']);
	  write_value_of('$password', "$password", $_POST['password']);
	  write_value_of('$database', "$bot_name", $_POST['database']);
	  
	  write_value_of('$displayUsersDisconnects', "$displayUsersDisconnects", $_POST['ud']);

	  //write_value_of('$scoreFormula', "$scoreFormula", $_POST['formula']);
	  
	  echo "<br>Configuration updated successfully<br><br>
	  <a href='index.php?conf'>Back to previous page</a><br><br>";
	  }
	   
	   }

	   ///////////////////////////////////////////////////////////////////////////////
	   //// BACKUP ///
	   ///////////////////////////////////////////////////////////////////////////////
	   if (isset($_GET['backup']) AND !isset($_GET['doit'])){
	   $database_size = $db->database_size($database);
       echo "<div align='center'><table style='width:400px;margin-top:20px;'><tr><th>Backup database ($database_size)<p class='alignright'><a href='index.php?show_tables'>Show tables</a></p></th></tr>
	   <tr>
	   <td align='center' style='padding:16px;'><a class='inputButton' href='index.php?backup&doit'>Click here to begin backup</a><br><br><i>Please be patient as this process can take a while</i></td></tr>
	   <td style='padding:4px;'><a class='inputButton' href='index.php?optimize'>Optimize tables</a></td>
	   </tr></table></div>";
	   
	   echo "<br>";
	   if (file_exists("./backup"))
	   {
      $myDirectory = opendir("./backup");

      while($entryName = readdir($myDirectory)) {
	  $dirArray[] = $entryName;
      }
      closedir($myDirectory);

      $indexCount	= count($dirArray);
      $totfiles = $indexCount-2;
      if ($totfiles <=0) {$totfiles=0;}

       // sort 'em
      sort($dirArray);

      // print 'em
      echo("<div align='center'><TABLE style='width:90%;' >\n");
      echo("<TR><TH ><b>Backup files:</b> $totfiles file(s)</TH></TR>\n");
      // loop through the array of files and print them all
      for($index=0; $index < $indexCount; $index++) {
        if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		echo("<TR><TD height='32'><a href=\"backup/$dirArray[$index]\">$dirArray[$index]</a> | <a href=\"?delete_backup=$dirArray[$index]\"><b>Delete</b></a></td>");

		echo("</TR>\n");
	      }
      }
      echo("</TABLE></div>");
	    } else {echo "Directory <b>backup</b> not exists. Please create backup directory in your ACP folder";}
	   }

	   if (isset($_GET['backup']) AND isset($_GET['doit'])){
       include("backup_func.php");
	       if (file_exists("./backup"))
	       {backup_tables($server,$username,$password,$database,'*');}
	       else {"Directory <b>backup</b> not exists. Please create backup directory in your ACP folder";}
	       }
		   
	   if (isset($_GET['delete_backup']) AND !isset($_GET['backup']) AND !isset($_GET['doit'])){
	   
       if (file_exists("./backup/".$_GET['delete_backup'])){
       $res = unlink("./backup/".$_GET['delete_backup']);}
	          if ($res) {echo "<br><br>File '".$_GET['delete_backup']."' successfully deleted.<br><br>
			  <a href='index.php?backup'>Back to previous page</a>";} 
			  else {echo "File not exists";}
       }
	   
	   if (isset($_GET['show_tables']) AND !isset($_GET['backup']) AND !isset($_GET['doit'])){
	   $table_admins = $db->table_size("admins",$database);
	   $table_bans = $db->table_size("bans",$database);
	   $table_dotagames = $db->table_size("dotagames",$database);
	   $table_dotaplayers = $db->table_size("dotaplayers",$database);
	   $table_downloads = $db->table_size("downloads",$database);
	   $table_gameplayers = $db->table_size("gameplayers",$database);
	   $table_games = $db->table_size("games",$database);
	   $table_heroes = $db->table_size("heroes",$database);
	   $table_items = $db->table_size("items",$database);
	   $table_news = $db->table_size("news",$database);
	   $table_scores = $db->table_size("scores",$database);
	   //$database_size = $db->database_size($database);
	   echo "<br><br><div align='center'><table border=1 style='width:430px;'>
	   <tr><th>Table: $database</th><th style='padding:6px;'>Size:<p class='alignright'></p></th></tr>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>admins</b></td><td style='padding:6px;'>$table_admins</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>bans</b></td><td style='padding:6px'>$table_bans</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>dotagames</b></td><td style='padding:6px'>$table_dotagames</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>dotaplayers</b></td><td style='padding:6px'>$table_dotaplayers</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>downloads</b></td><td style='padding:6px'>$table_downloads</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>gameplayers</b></td><td style='padding:6px'>$table_gameplayers</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>games</b></td><td style='padding:6px'>$table_games</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>heroes</b></td><td style='padding:6px'>$table_heroes</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>items</b></td><td style='padding:6px'>$table_items</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>news</b></td><td style='padding:6px'>$table_news</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>scores</b></td><td style='padding:6px'>$table_scores</td>
	   </tr></table><br><br><a href='index.php?backup'>Back to previous page</a>";
	   }
	   
	   if (isset($_GET['optimize']) AND !isset($_GET['backup']) AND !isset($_GET['doit']))
	   {
	$sql = " 
	 OPTIMIZE TABLE 
	`admins` , 
	`bans` , 
	`dotagames` , 
	`dotaplayers` , 
	`downloads` , 
	`gameplayers` , 
	`games` , 
	`heroes` , 
	`items` , 
	`news` , 
	`scores` ";
	
	$result = $db->query($sql);
	if ($result)
	{echo "<br /><div align='center'>All tables optimized.<br /><br /><a href = 'index.php?backup'>Back to previous page</a></div><br>";}
	   
	   }
	   
	   ///////////////////////////////////////////////////////////////////////////////
	   //Auto check version on login
	  // make sure curl is installed
	  ///////////////////////////////////////////////////////////////////////////////
 if (!$_GET){
   if (function_exists('curl_init')) {
   // initialize a new curl resource
   $ch = curl_init();
   // set the url to fetch
   curl_setopt($ch, CURLOPT_URL, 'http://openstats.iz.rs/version.php');
   // don't give me the headers just the content
   curl_setopt($ch, CURLOPT_HEADER, 0);
   // return the value instead of printing the response to browser
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   // use a user agent to mimic a browser
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
   $vers = curl_exec($ch);
   // Close the session and free all resources
   curl_close($ch);
} else {
   echo "Auto-check version failed. Curl is NOT installed. Please check your PHP configuration.<br>However, you can manually check latest version:<br><br><a target='_blank' class='inputButton' href='https://sourceforge.net/projects/dotaopenstats/'>Download OpenStats</a><br><br>";}
   
      

	   echo "<div align='center'><table class='tableA'><tr>
	   <th>DotA OpenStats Dashboard<p class='alignright'>Version ".VERSION."</p></th>
	   </tr>";
      if ($vers != VERSION) {echo "<tr>
	  <td align='center'>
	  <span style='background-color:#FFFFE0;color:#000;padding:4px;border:2px solid #E6DB55;'><b>Important:</b> before updating, please backup your database and files.</span></td></tr>
	  <tr><td align='center'><br><b>An updated version of Dota OpenStats is available.</b><br><br></td></tr>
	  <tr><td align='center'>You can update to OpenStats ".$vers."<br><br>Download the package and install it:
	  <tr><td align='center'><br><a target='_blank' class='inputButton' href='https://sourceforge.net/projects/dotaopenstats/'>Download ".$vers."</a><br><br></td></tr>
	  ";} 
	  else
	  {
	  echo "<tr><td align='center'><b>You have the latest version of DotA OpenStats</b></td></tr>
	  <tr><td align='center'><br>You do not need to update. However, if you want to re-install version ".$vers.", you can download the package:</td></tr>
	  <tr><td align='center'><br><a target='_blank' class='inputButton' href='https://sourceforge.net/projects/dotaopenstats/'>Download ".$vers."</a><br><br></td></tr>";
	  }
	  
	  echo "</table></div><br><br>";
	   
	echo '</body><div>
	 <table><tr>
	 <td style="padding-right:12px;text-align:right;" align="right">
	 &copy; '.date("Y").' <a href=\'http://openstats.iz.rs\'><b>DotA OpenStats</b></a></td>
	 </tr></table></div>';
	 }

	} //IS LOOGED
	?>