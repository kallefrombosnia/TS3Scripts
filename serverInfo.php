<?php

/*

	Name: Server info query
	Author: kalle
	Version: v1.0	

	Description: Script that queries server info and displays it on website.
				 Just an example.

*/


// First include our framework that we are gonna use
// You can download it from here https://github.com/planetteamspeak/ts3phpframework
include_once('TeamSpeak3/TeamSpeak3.php');
// Connection configuration
$login_name = 'serveradmin';  			// query login info
$login_password = '9kg6eRKk'; 			// =||=
$ip = 'localhost';            			// ex. 127.0.0.1/ 254.13.121.12 
$query_port = '10011';		  			// default 10011
$virtualserver_port= '9987'; 			// virtual server port
// Using try/ catch to resolve feature 'check if server is online'
try
{
	// Connecting query bot to the our specific  TS3  server 
	$ts3 = TeamSpeak3::factory("serverquery://".$login_name.":".$login_password."@".$ip.":".$query_port."/?server_port=".$virtualserver_port."&nickname=R4P3&blocking=0");
	/*
		Now we have object in $ts3 variable so lets continue.
		To check what info object give to us, we can var_dump($ts3);
		This gives us a shit load of information so thats good.
		But we dont need all of it, just specific things.
	*/
	// Shows the ip of your server
	echo ($ts3->getAdapterHost().'<br>');
	// Shows the port of your server
	echo ($ts3->virtualserver_port.'<br>');
	// Shows the name of your server
	echo ($ts3->virtualserver_name.'<br>');
	// Shows the uptime of your server
	echo (TeamSpeak3_Helper_Convert::seconds($ts3->virtualserver_uptime).'<br>');
	// Shows server current version
	echo (TeamSpeak3_Helper_Convert::version($ts3->virtualserver_version).'<br>');
	// Shows currents clients online / current slots on server
	echo ($ts3->virtualserver_clientsonline."/ ".$ts3->virtualserver_maxclients);
} 
	catch(Exception $e)
{
  // Grab error and show server as offline
  echo ("Server Status: offline <br>" . $e);
}
?>