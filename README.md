# Solace PHP MQTT Samples

## About

PHP samples to access Solace with Mosquitto MQTT API

## DISCLAIMER

This is **not** a Solace product and not covered by Solace support.

## USAGE

### Create Required callback functions

``` PHP

# connect callback
function connect($r) {
  echo("Connect successful. response code {$r}");
}

# disconnect callback
function disconnect() {
 echo("Disconnected cleanly");
}

# subscribe callback
function subscribe() {
 echo("Subscribe successful");
}

# on message call back
function on_message($message) {
  echo("Got a message on topic :". $message->topic."\n".$message->payload);
}

```

### Connecting to Solace broker

``` PHP
$client = new Mosquitto\Client(CLIENT_ID);
$client->setCredentials(USERNAME, PASSWORD);
$client->onConnect('connect');
$client->onDisconnect('disconnect');
$client->connect(SOLACE_URL, PORT, 60);
```

Required variables (CLIEND_ID, USERNAME, PASSWORD, SOLACE_URL, PORT,...) are assumed to be defined before hand.

### Publish to Solace

``` PHP
   $client->publish("test/topic", "hello world", 0, false);
```

### Subscribe to a topic

``` PHP
...
$client->onMessage('on_message');
$client->connect(SOLACE_URL, PORT, 60);

$client->subscribe("test/#", 0); 
```

Pl take a look at the sample PHP files for other use cases (such as QOS 1 publisher, Queue consumer,etc.) accessing Solace with Mosquitto-PHP.

## INSTALLATION

This has been tested to work with PHP 7.4.8 on MacOS Catalina with SolOS 9.6.

1. Install [Mosquitto](http://mosquitto.org/) libraries for your platform. Follow Installation notes from the package.
2. Install [Mosquitto-PHP](https://github.com/mgdm/Mosquitto-PHP). Follow install notes from the package.

### Required

- HTTP server (eg: Apache httpd) with PHP enabled
- Mosquitto MQTT Libraries
- Mosquitto PHP module

### Useful links

- [https://docs.solace.com/]
- [https://docs.solace.com/Open-APIs-Protocols/MQTT/MQTT-get-started.htm]
- [http://mosquitto.org/]
- [https://github.com/mgdm/Mosquitto-PHP]
- [https://mosquitto-php.readthedocs.io/en/latest/client.html]

## AUTHOR

Ramesh Natarajan, Solace PSG
