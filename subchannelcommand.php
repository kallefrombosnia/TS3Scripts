<?php
/*
	 Name: Subchannel creator script with command !wn <name>
	 Author: kalle
	 Version: v1.0	
*/

$login_name = 'serveradmin';  	//query login info
$login_password = 'rgDRpNLR'; 	// =||=
$ip = 'localhost';            	//ex. 127.0.0.1/ 254.13.121.12 
$query_port = '10011';		  	//default 10011
$virtualserver_port = '9987'; 	//default 9987
$bot_name = 'some special shit';          	//bot name
$channelid = "1";       	//Channel where is bot going to  NOTE: Dont make this default channel. If you put default channel delete $ts3_VirtualServer->clientMove($ts3_id_bota, $channelid);
$cadmin = '5';                 // Channel admin id


//FRAMEWORK
$filename = 'library/TeamSpeak3/TeamSpeak3.php';

if (file_exists($filename)) {
    require_once($filename);
} else {
    die ("The file $filename does not exist");
}

$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$login_name.":".$login_password."@".$ip.":".$query_port."/?server_port=".$virtualserver_port."&nickname=R4P3&blocking=0");

$ts3_id_bota = $ts3_VirtualServer->whoamiGet('client_id');
$ts3_VirtualServer->clientMove($ts3_id_bota, $channelid);
$ts3_VirtualServer->selfUpdate(array("client_nickname" => $bot_name));

TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryWaitTimeout", "onWaitTimeout");
TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyTextmessage", "onTextMessage");
$ts3_VirtualServer->notifyRegister("server");
$ts3_VirtualServer->notifyRegister("channel");
$ts3_VirtualServer->notifyRegister("textserver");
$ts3_VirtualServer->notifyRegister("textchannel");
$ts3_VirtualServer->notifyRegister("textprivate");

while(1) 
{
    $ts3_VirtualServer->getAdapter()->wait();
}

function onWaitTimeout($time, TeamSpeak3_Adapter_Abstract $adapter)
{
    if($adapter->getQueryLastTimestamp() < time()-300)
    {
        $adapter->request('clientupdate');
    }
}

function onTextMessage(TeamSpeak3_Adapter_ServerQuery_Event $event, TeamSpeak3_Node_Host $host) 
{

    global $channelid;
    global $cadmin;
    $info = $event->getData();
    $srv = $host->serverGetSelected();

    if($info["targetmode"] == 2)
    {
		
	$mystring = $info["msg"];
        $pos1 = strpos($mystring, " ");

        if($pos1 > 0)
        {
            $rijec = substr($mystring, 0, $pos1);
        }
        else
        {  
            $rijec = $mystring;
        }


    	if($rijec == '!wn')
        {
        	$name = str_replace('!wn ','', $info["msg"]);
            $createsub = $srv->channelCreate(array(
                "channel_name" => $name,
                "channel_topic" => "My temp channel",
                "channel_codec" => TeamSpeak3::CODEC_SPEEX_ULTRAWIDEBAND,
                "channel_codec_quality" => 0x08,
                "channel_flag_permanent" => true,
                "cpid" => $channelid                               
                ));

            $srv->clientGetByName($info["invokername"]->toString())->setChannelGroup($createsub, $cadmin);
            $srv->clientMove($srv->clientGetByName($info["invokername"]), $createsub);
            $srv->clientGetByName($info["invokername"]->toString())->message("[b][color=green]Channel Created![/color][/b]");
        }else{
        	$srv->clientGetByName($info["invokername"]->toString())->message("Command Not found");
        }
    }
}
?>
