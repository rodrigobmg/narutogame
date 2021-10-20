<?php
	require('_config.php');
	
	$batalhas	= Recordset::query('
		SELECT 
			a.id,
			a.id_player,
			a.npc_tipo,
			a.data_atk,
			a.session,
			DAY(DATEDIFF(a.data_atk, NOW())) AS dias,
			MINUTE(TIMEDIFF(a.data_atk, NOW())) AS minutos,
			b.id_equipe,
			b.id_guild,
			b.id_missao_guild,
			b.id_missao,
			b.id_evento
		
		FROM 
			batalha a JOIN player b ON b.id=a.id_player
		
		WHERE
			a.npc_tipo IS NOT NULL AND
			a.finalizada=0
	');
	
	foreach($batalhas->result_array() as $batalha) {
		if($batalha['minutos'] >= 5 || $batalha['dias'] >= 1) {
			echo "+ FOUND {$batalha['id']}\n";
			
			$instance	= unserialize(SharedStore::G('_BATALHA_' . $batalha['id_player']));

			switch($batalha['npc_tipo']) {
				case 'equipe':
					Recordset::update('evento_npc_equipe', array(
						'batalha'		=> '0'
					), array(
						'id_equipe'		=> $batalha['id_equipe'],
						'id_evento'		=> $batalha['id_evento'],
						'id_evento_npc'	=> $instance->id
					));
				
					break;

				case 'global':
					Recordset::update('evento_npc_evento', array(
						'batalha_global'	=> '0'
					), array(
						'id_evento_npc'		=> $instance->id,
						'id_evento'			=> $instance->id_evento
					));					
				
					break;

				case 'vila':
					Recordset::update('npc_vila', array(
						'id_player_batalha'	=> '0',
						'batalha'			=> '0',
					), array(
						'id'				=> $baseEnemy->id
					));				
				
					break;
			}
			
			Recordset::update('batalha', array(
				'finalizada'	=> 1
			), array(
				'id'			=> $batalha['id']
			));
			

			Recordset::update('player', array(
				'id_batalha'	=> 0,
				'id_vila_atual'	=> array('escape' => false, 'value' => 'id_vila'),
				'dentro_vila'	=> 1,
				'derrotas_npc'	=> array('escape' => false, 'value' => 'derrotas_npc+1')
			), array(
				'id'	=> $batalha['id_player']
			));
		}
	}