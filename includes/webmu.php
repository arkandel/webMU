<?php
/**
 * Copyright (C) 2013 peredur.net
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) : ?>
<!doctype html>
<html>
<head>
	<meta charset='UTF-8' />
	<style>
		input, textarea {border:1px solid #CCC;margin:0px;padding:0px}

		#body {max-width:800px;margin:auto}
		#log {width:100%;height:400px}
		#message {width:100%;line-height:20px}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="fancywebsocket.js"></script>
	<script>
		var Server;

		function log( text ) {
			$log = $('#log');
			//Add text to log
			$log.append(($log.val()?"\n":'')+text);
			//Autoscroll
			$log[0].scrollTop = $log[0].scrollHeight - $log[0].clientHeight;
		}

		function send( text ) {
			var username=<?php echo json_encode($_SESSION["username"]); ?>;
			Server.send( 'message', username+text );
		}

		$(document).ready(function() {
			log('Connecting...');
			Server = new FancyWebSocket('ws://192.168.1.165:9300/test');

			$('#message').keypress(function(e) {
				if ( e.keyCode == 13 && this.value ) {
					log( 'You: ' + this.value );
					send( this.value );

					$(this).val('');
				}
			});

			//Let the player know we're connected
			Server.bind('open', function() {
				log( "Connected." );
			});

			//OH NOES! Disconnection occurred.
			Server.bind('close', function( data ) {
				log( "Disconnected." );
			});

			//Log any messages sent from server
			Server.bind('message', function( payload ) {
				log( payload );
			});

			Server.connect();
		});
	</script>
</head>

<body>
	<div id='body'>
		<textarea id='log' name='log' readonly='readonly'></textarea><br/>
		<input type='text' id='message' name='message' />
	</div>
</body>

</html>
<?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>
