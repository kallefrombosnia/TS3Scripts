<?php

/*

	Name: onClientConnect
	Author: kalle
	Version: v1.0	

	Description: This script subscribes to oncliententerview event, and fires callback on function.
				 In my function I took event invoker and his database id.
				 Later I used that dbid to find specific information about user.
				 Purpose is to assign new group if ip of invoker matches our allowed arrays.

*/

require_once('TeamSpeak3/TeamSpeak3.php');
$ts3_host = "localhost";
$ts3_q_port = "10011";
$ts3_s_port = "9987";
$ts3_username = "serveradmin";
$ts3_password = "9kg6eRKk";
$ts3_nick = "R4P3.net";
$selectedOnes = array('127.0.0.1');
$group = '7';
$ts3 = TeamSpeak3::factory("serverquery://$ts3_username:$ts3_password@$ts3_host:$ts3_q_port/?server_port=$ts3_s_port&blocking=0");
$ts3->selfUpdate(array('client_nickname'=> $ts3_nick));
TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryWaitTimeout", "onWaitTimeout");
TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyCliententerview", "onClientEnterView");
$ts3->notifyRegister("server");
while(1) 
{
    $ts3->getAdapter()->wait();
}
function onWaitTimeout($time, TeamSpeak3_Adapter_Abstract $adapter)
{
    if($adapter->getQueryLastTimestamp() < time()-300)
    {
        $adapter->request('clientupdate');
    }
}
function onClientEnterView(TeamSpeak3_Adapter_ServerQuery_Event $event){ 
    global $group;
    global $ts3;
    global $selectedOnes;
    $userInfo = $event->getData();
    $client = $ts3->clientGetByDbId($userInfo['client_database_id']); 
    $groups = array_filter(explode(',', $client->client_servergroups));
    
    if(in_array($client->connection_client_ip, $selectedOnes)){  
        if(in_array($group, $groups, TRUE)){
            
            return;    
        }else{
            $ts3->serverGroupClientAdd($group,$userInfo['client_database_id']);
            return;
        }  
    }    
} 




