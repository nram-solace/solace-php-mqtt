<?php

use Mosquitto\Client;

define('SOLACE_URL', 'localhost');
define('PORT', 1883);
define('USERNAME', 'default');
define('PASSWORD', 'default');
define('TOPIC', 'test/php/mqtt/102');
define('CLIENT_ID', "MqttDirectTopicPublisher-" . getmypid());
define('NUMBER_OF_MESSAGES', 100);
define('MQTT_QOS',1);
define('CLEAN_SESSION', True);
define('TIMEOUT', 60);

function connect($r) {
	wprint("Connect successful. response code {$r}");
}

function disconnect() {
	wprint("Disconnected cleanly");
}

# print to browser
function wprint($s) {
    echo nl2br(date("Y-m-d H:i:s"). " : {$s}\n");
    #ob_flush();
    #flush();
}

#-------------------------------------------------------------------------
# Main
#
ob_implicit_flush(true);
ob_end_flush();
#ob_start();
wprint("Connecting to Solace broker : ".SOLACE_URL.":".PORT);
wprint("Client username : ".USERNAME);
wprint("CLIENT ID : ".CLIENT_ID);
wprint("TOPIC : ".TOPIC);
wprint("MQTT QOS : ".MQTT_QOS);
wprint(CLEAN_SESSION?"CLEAN SESSION: Yes":"CLEAN SESSION: No");

$client = new Mosquitto\Client(CLIENT_ID);
$client->setCredentials(USERNAME, PASSWORD);
$client->onConnect('connect');
$client->onDisconnect('disconnect');
$client->connect(SOLACE_URL, PORT, TIMEOUT);
wprint("Connected!");

$nmsgs = 0 ;
wprint("Publishing  messages (Max: ". NUMBER_OF_MESSAGES. " ):\n");
while ($nmsgs < NUMBER_OF_MESSAGES) {
    $nmsgs += 1;
	$message = "Hello World ". $nmsgs;
	$client->publish(TOPIC, $message, MQTT_QOS, false);
    wprint("Published message {$nmsgs} to topic:". TOPIC. ": {$message}");
	$client->loop();
	sleep(1);
}

wprint("Thats all folks!");
