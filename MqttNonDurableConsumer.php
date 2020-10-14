<?php

use Mosquitto\Client;

define('SOLACE_URL', 'localhost');
define('USERNAME', 'default');
define('PASSWORD', 'default');
define('PORT', 1883);
define('TOPIC', 'test/php/#');
define('CLIENT_ID', "MqttNonDurableConsumer-" . getmypid());
define('NUMBER_OF_MESSAGES', 10);
define('MQTT_QOS',1);
define('CLEAN_SESSION', True);
define('TIMEOUT', 60);;
$nmsgs = 0;

#--------------------------------------------------------------------
# callbacks
#
function connect($r) {
	wprint("Connect successful. response code {$r}");
}

function subscribe() {
	wprint("Subscribe successful");
}

function message($message) {
	global $nmsgs, $client;
	$nmsgs++;
	wprint("({$nmsgs}) Got a message on topic :". $message->topic."\n".$message->payload);
	if ($nmsgs >= NUMBER_OF_MESSAGES) {
		wprint("Received {$nmsgs} messages. Ending loop");
		$client->exitLoop();
	}
    #ob_flush(); flush();
}

function disconnect() {
	wprint("Disconnected cleanly");
}


# print to browser
function wprint($s) {
    echo nl2br(date("Y-m-d H:i:s"). " : {$s}\n");
}

#-------------------------------------------------------------------------
# Main
#
#ob_start();
ob_implicit_flush(true);
ob_end_flush();
wprint("Connecting to Solace broker : ".SOLACE_URL.":".PORT);
wprint("Client username : ".USERNAME);
wprint("CLIENT ID : ".CLIENT_ID);
wprint("TOPIC : ".TOPIC);
wprint("MQTT QOS : ".MQTT_QOS);
wprint(CLEAN_SESSION?"CLEAN SESSION: Yes":"CLEAN SESSION: No");

$client = new Mosquitto\Client(CLIENT_ID, CLEAN_SESSION);
$client->setCredentials(USERNAME, PASSWORD);
wprint("ClientID :".CLIENT_ID);
#ob_flush(); flush();

$client->onConnect('connect');
$client->onDisconnect('disconnect');
$client->onSubscribe('subscribe');
$client->onMessage('message');
$client->connect(SOLACE_URL, PORT, TIMEOUT);
wprint("Connected!");
wprint("Subcribing to topic :".TOPIC."\n");
#ob_flush(); flush();
$client->subscribe(TOPIC, MQTT_QOS); 

$client->loopForever();
