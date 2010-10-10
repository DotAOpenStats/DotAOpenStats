<?php

session_start();

$ADMINISTRATORS = array(
  'admin' => 'admin'
);

$MODERATORS = array(
  'user1' => 'pass1',
  'user2' => 'pass2'
);

$PUBLISHERS = array(
  'john' => 'doe',
  'dota' => 'openstats'
);

////////////////////////////////////////////////////////////
/*      REMEMBER: dont put "," after last user in array
       'username' => 'password'

       If you dont have moderators or publisher, 
       just put your administrator username and password for ADMINISTATORS
       and put empty username/password for others
 

    Example:
	
$ADMINISTRATORS = array(
  'john' => 'pass',
  'user2' => 'password2'
);

$MODERATORS = array(
  '' => '',
  '' => ''
);

$PUBLISHERS = array(
  '' => '',
  '' => ''
);

*/
////////////////////////////////////////////////////////////
$admin_style = 'style2.css';

   //ini_set ("display_errors", "1");
   //error_reporting(E_ALL);

?>