<?php
	require('_config.php');

	$playersloginip = Recordset::query("
		select ip, id_player from (
		select count(1) ip, id_player from (
		select distinct ip, id_player from player_login ) w
		group by id_player ) y where y.ip > 50 order by ip desc
	");

	while($playerloginip = $playersloginip->row_array()) {
		$playerslogin = Recordset::query("select distinct INET_NTOA(ip) ip from player_login where id_player = ".$playerloginip['id_player']);
		
		while($playerlogin = $playerslogin->row_array()) {
			$details = json_decode(file_get_contents("http://ipinfo.io/".$playerlogin['ip']."/json"));
			echo $details->country; // -> "Mountain View"
			echo "---";
		}

		die();
	}
