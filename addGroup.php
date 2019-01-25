<?php
/*

	Name: User group adder
	Author: kalle
	Version: v1.0	

	Description: Adds all online users to specific group

*/

include_once('TeamSpeak3/TeamSpeak3.php');
// Connection configuration
$login_name = 'serveradmin';  			// query login info
$login_password = ''; 			        // =||=
$ip = 'localhost';            			// ex. 127.0.0.1/ 254.13.121.12 
$query_port = '10011';		  			  // default 10011
$virtualserver_port= '9987'; 			  // virtual server port
$group = '7';                       // Specific group
try
{
	$ts3 = TeamSpeak3::factory("serverquery://".$login_name.":".$login_password."@".$ip.":".$query_port."/?server_port=".$virtualserver_port."&nickname=R4P3&blocking=0");
	
	// query clientlist from virtual server
	$clientList = $ts3->clientList();
	foreach ($clientList as $client) {
		if($client['client_type'] == 1) continue;
		if(!in_array($group, explode(',', $client['client_servergroups']))){
			$cldbid = $client->getInfo();
			$ts3->serverGroupClientAdd($group,$cldbid['client_database_id']);
		}	
	}
} 
	catch(Exception $e)
{
  echo ($e);
}
?>