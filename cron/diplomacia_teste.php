<pre>
<?php
	require('_config.php');

	//Recordset::query("TRUNCATE TABLE diplomacia");

	function max_key($a) {
		$m = 0;
		$hm = false;
		
		foreach($a as $k => $v) {
			if($v > $m) {
				$m = $v;
				$hm = $k;
			}
		}
		
		return $hm ? $hm : false;
	}
	
	function array_equality($a) {
		foreach($a as $k => $v) {
			foreach($a as $kb => $vb) {
				if($k === $kb) continue;
				
				if($v == $vb) {
					return $v;
				}
			}
		}
		
		return false;
	}

	for($a = 1; $a <= 8; $a++) {
		for($b = 1; $b <= 8; $b++) {
			echo "+ Vila $a -> $b => ";
			
			if($a == $b) {
				$dipl = 1;
				
				echo "SKIP => 1 --> ";
			} else {
				$rDipl = Recordset::query("
					SELECT
					SUM(dipl0) AS dipl0,
					SUM(dipl1) AS dipl1,
					SUM(dipl2) AS dipl2
					FROM
					(
						SELECT
						(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $a . " AND id_vilab=" . $b . " AND dipl=0) AS dipl0,
						(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $a . " AND id_vilab=" . $b . " AND dipl=1) AS dipl1,
						(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $a . " AND id_vilab=" . $b . " AND dipl=2) AS dipl2
						UNION
						SELECT
						(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $b . " AND id_vilab=" . $a . " AND dipl=0) AS dipl0,
						(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $b . " AND id_vilab=" . $a . " AND dipl=1) AS dipl1,
						(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $b . " AND id_vilab=" . $a . " AND dipl=2) AS dipl2
					) w
				")->row_array();
				
				$dipl = max_key($rDipl);
				
				if($dipl === false) { // Ninguem votou
					echo "NODIPL => 0 --> ";
				
					$dipl = 0;
				} else {
					$equality = array_equality($rDipl);
					
					echo '> ' . $equality . ' -- ' . $rDipl[$dipl] . ' <';
					
					if($equality >= $rDipl[$dipl]) { // Se ouver empate e for maior q o valor maximo, neutro
						echo "EQL => 0 --> ";
					
						$dipl = 0;
					} else { // É oq votaram =)
						$dipl = preg_replace("/[^\d]/s", "", $dipl);
					
						echo "VOTE => " . $dipl . " --> ";
					}
				}
			}
			
			
			echo 'FINAL: ' . $dipl . PHP_EOL;
			//Recordset::query("INSERT INTO diplomacia(id_vila, id_vilab, dipl) VALUES($a, $b, $dipl)");			
		}
	}

	//Recordset::query("TRUNCATE TABLE diplomacia_voto");
	
	/*
	Recordset::query("TRUNCATE TABLE diplomacia");
	Recordset::query("TRUNCATE TABLE diplomacia_voto");
	
	for($a = 1; $a <= 8; $a++) {
		for($b = 1; $b <= 8; $b++) {
			if($a == $b) {
				$dipl = 1;
			} else {
				$dipl = 2;
			}
			
			Recordset::query("INSERT INTO diplomacia(id_vila, id_vilab, dipl) VALUES($a, $b, $dipl)");
		}
	}
	*/
	
	die();