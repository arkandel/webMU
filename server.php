<?php
//include_once 'includes/world_functions.php';
// prevent the server from timing out
set_time_limit(0);

// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';

/* 	A character leaves a room.
	Input: Moving character's ID, old room id, new room id
	Returns: Nothing.
*/
function changeRoom($movingCharacterID, $oldroom, $newroom)
{
	global $Server;

	// We wipe the roomlists clean in both the old and new room. We could just update entries, too, but to prevent getting out of sync later.
	$Server->world[$oldroom]=array();
	$Server->world[$newroom]=array();
	
	// First we assign the moving character their new room.
	$Server->character[$movingCharacterID]['room'] = $newroom;
	$Server->world[$newroom][]=$Server->character[$movingCharacterID]['name'];
		
	// First we tell people in the character's new and old rooms that they moved and update their room lists.
	foreach ( $Server->wsClients as $otherCharacterID => $client )
		if ( $otherCharacterID != $movingCharacterID && $Server->character[$otherCharacterID]['room']==$oldroom )
		{
			$Server->wsSend($otherCharacterID, $Server->character[$movingCharacterID]['name'] . " has left the room.");
			$Server->world[$oldroom][]=$Server->character[$otherCharacterID]['name'];
		}
		else
		if ($otherCharacterID != $movingCharacterID && $Server->character[$otherCharacterID]['room']==$newroom )
		{
			$Server->wsSend($otherCharacterID, $Server->character[$movingCharacterID]['name'] . " has arrived.");
			$Server->world[$newroom][]=$Server->character[$otherCharacterID]['name'];
		}

	$Server->log( $Server->character[$movingCharacterID]['name'] . " ($movingCharacterID) is now in room $newroom." );

	// Update rooms with their final denizens at the end.
	updateRoom($oldroom);
	updateRoom($newroom);
}

/* 	Update a room's denizen list for its denizens.
	Input: room id
	Returns: Nothing.
*/
function updateRoom($room)
{
	global $Server;

	$serverCommand = array(	"updateRoomList",$Server->world[$room]);
	
	foreach ( $Server->wsClients as $characterID => $client )
		if ( $Server->character[$characterID]['room']==$room )
		{
			$Server->wsSend($characterID, json_encode($serverCommand));
		}
}

function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	// check if message length is 0
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}

	if($message[0]=='|')
	{
		$command = explode(' ', trim($message) );
		switch($command[0])
		{
			case "|username":
				$Server->character[$clientID]['name'] = $command[1];
				$Server->log( "$ip ($clientID) is now called ".$Server->character[$clientID]['name'] );
				break;
			case "|changeRoom":
				changeRoom($clientID, $Server->character[$clientID]['room'], $command[1]);
				break;
		}
		$commandIssued = 1; // If it's a command, don't send it to the textbox.
	}
	
	// In case someone is in the middle of logging on and hasn't gotten a room number yet, we'll assume it's the OOC room.
	if (!isset($Server->character[$clientID]['room']))
		$Server->character[$clientID]['room'] =1;

	//Send the message to everyone but the person who said it
	foreach ( $Server->wsClients as $id => $client )
		if ( $id != $clientID && $Server->character[$clientID]['room'] == $Server->character[$id]['room'] )
		{
			if (!isset($commandIssued))
				$Server->wsSend($id, $Server->character[$clientID]['name']." said \"$message\"");
		}
}

// when a client connects
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
/*
	$Server->log( $Server->character[$clientID]['name'] . " - $ip ($clientID) - has connected." );

	//Send a join notice to everyone but the person who joined
	foreach ( $Server->wsClients as $id => $client )
		if ( $id != $clientID )
			$Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");
*/
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( $Server->character[$clientID]['name'] . " - $ip ($clientID) - has disconnected." );

	//Send a user left notice to everyone in the room
	foreach ( $Server->wsClients as $id => $client )
		if ( $id != $clientID && $Server->character[$clientID]['room'] == $Server->character[$id]['room'] )
			$Server->wsSend($id, $Server->character[$clientID]['name'] . " has disconnected from the game.");

}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
$Server->wsStartServer('192.168.1.165', 9300);

?>
