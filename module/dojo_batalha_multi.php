<?php
	$batalha	= Recordset::query('SELECT * FROM batalha_multi WHERE id=' . $basePlayer->id_batalha_multi)->row_array();
	$equipe		= Recordset::query('SELECT * FROM equipe WHERE id=' . $basePlayer->id_equipe)->row_array();
	$evento		= Recordset::query('SELECT * FROM evento4 WHERE id=' . $equipe['id_evento4b'], true)->row_array();
	
	$lsz		= $batalha['id_tipo'] == 1 ? 1 : 4;
	
	//if($_SESSION['universal']) {
		for($f = 1; $f <= $lsz; $f++) {
			$check = @gzuncompress($batalha['npc' . $f]);
			
			if(!$check) {
				$update['npc' . $f] 	= gzcompress($batalha['npc' . $f]);
				$batalha['npc' . $f]	= $update['npc' . $f];
				
				Recordset::update('batalha_multi', $update, array(
					'id'	=> $batalha['id']
				));
			}
		}
	//}

	$evento4	= true;
	$pvpToken	= $_SESSION['_pvpToken'] = md5(rand(1, 9999999)); 
?>
<div class="titulo-secao titulo-secao-dojo titulo-secao-dojo-3"><p><?php echo t('dojo_batalha_multi.db1');?></p></div>
<style type="text/css">
	.morto {
		width: 78px;
		height: 80px;
		background-image: URL(images/layout/combate/multi_morto.png);
		position: absolute;
		top: 30px;	
		left: 20px;
		display: none
	}
	
	.team-role-box {
		position: relative
	}
	
	.team-role-box .ex_tooltip {
		z-index: 100 !important
	}
</style>
<script type="text/javascript">
	var _pvpTimer				= null;
	var _pvpRequestR			= true;
	var _cTarget				= <?php echo $batalha['id_tipo'] == 1 ? 1 : 'null' ?>;
	var _cTargetH				= null;
	var _cTargetClickable		= true;
	var _canAtk					= true;
	var _pToken					= '<?php echo $pvpToken ?>';
	var _pvp_multi_npc_sound	= false;
	
	$(document).ready(function () {
		_pvpTimer = setInterval(function () {
			if(!_pvpRequestR) return;
			
			_pvpRequestR = false;
			
			$.ajax({
				url: 'index.php?acao=dojo_batalha_multi_ping',
				dataType: 'script',
				type: 'post',
				data: {_pvpToken: _pToken},
				success: function (e) {
					eval(e);
				
					_pvpRequestR = true;
				},
				error: function () {
					_pvpRequestR = true;
				}
			});
		}, 3000);
		
		$('.pFrame').click(function () {
			if(!_canAtk) {
				return;
			}

			if(!_cTargetClickable) {
				return;
			}			

			$('.pFrame').css('background-image', 'none');
			$(this).css('background-image', 'url(<?php echo img('layout/combate/pixel_trans_b.png') ?>)');
			_cTargetH = this.id.toString().replace(/[^\d]+/, '');
		});
		
		$('.eFrame').click(function () {
			if(!_canAtk) {
				return;
			}
			
			if(!_cTargetClickable) {
				return;
			}
		
			$('.eFrame').css('background-image', 'none');
			$(this).css('background-image', 'url(<?php echo img('layout/combate/pixel_trans.png') ?>)');
			_cTarget = this.id.toString().replace(/[^\d]+/, '');
		});
	});

	function doAttack(id, opt) {
		if(opt != 97) {
			if(!_cTarget) {
				alert('<?php echo t('dojo_batalha_multi.db2');?>');
				return;
			}
		}

		if(opt == 97 && !_cTargetH) {
			alert('<?php echo t('dojo_batalha_multi.db3');?>');
			return;
		}

		_pvpRequestR = false;
		
		_canAtk = opt ? true : false;
		var action = opt || 1;
	
		
		var target = _cTarget;
		
		if(!opt) {
			pPvpAtkEnabling();
			_cTarget = null;
			_cTargetH = null;
			
			$('.pFrame').css('background-image', 'none');
			$('.eFrame').css('background-image', 'none');
		}
	
		$.ajax({
			url: 'index.php?acao=dojo_batalha_multi_ping',
			type: 'post',
			data: {item: id, action: action, target: target, _pvpToken: _pToken, target_h: _cTargetH},
			dataType: 'script',
			success: function () {
				_pvpRequestR = true;
			},
			error: function () {
				_pvpRequestR = true;
			}
		});	
	}
</script>
<div id="cnVitoria" style="display: none">
	<div id="msg_help" class="msg_gai">
	    <div class="msg">
	        <div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
            <b><?php echo t('dojo_batalha_multi.db4');?></b>
			<p>
				<?php echo t('dojo_batalha_multi.db5');?>:<br />
				&bull; <?php echo $evento['ryou'] ?> Ryous<br />
				&bull; <?php echo $evento['exp'] ?> <?php echo t('geral.pontos_exp')?><br />
				&bull; <?php echo $evento['treino'] ?> <?php echo t('geral.pontos_treino')?><br />
				<?php echo t('dojo_batalha_multi.db6');?>
			</p>
            </div>
	    </div>
	</div>
</div>
<div id="cnDerrota" style="display: none">
	<div id="msg_help" class="msg_gai">
	    <div class="msg">
			<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
            <b><?php echo t('dojo_batalha_multi.db7');?></b>
			<p>
            	<?php echo t('dojo_batalha_multi.db8');?>
			</p>
            </div>
	    </div>
	</div>
</div>
<div style="clear: both"></div>
<div id="painel-pvp-multi-container">
	<div id="painel-pvp-multi-players">
		<div class="pvp-versus-box pvp-versus-left">
			<?php for($f = 1; $f <= 4; $f++): ?>
				<?php if($f == 3): ?>
					<div style="clear: both"></div>
				<?php endif; ?>
				<div style="float: left; position: relative; cursor: pointer; width:125px; height:215px; z-index: <?php echo $f ?>; margin-bottom: 45px" id="cnpFrame<?php echo $f ?>" class="pFrame">
					<?php 
						if($batalha['p' . $f] == $basePlayer->id) {
							$player = $basePlayer;
						} else {
							$player = new Player($batalha['p' . $f]);
						}
						
						$role_id	= Player::getFlag('equipe_role', $player->id);
						$role_img	= "layout/equipe_roles/nenhum.png";
						$has_role	= false;

						if($role_id != "") {
							$role_lvl	= Player::getFlag('equipe_role_' . $role_id . '_lvl', $player->id);
							$role		= Recordset::query('SELECT id, imagem FROM item WHERE id_tipo=22 AND id_habilidade=' . $role_id . ' AND ordem=' . ($role_lvl >= 1 ? $role_lvl : 1))->row_array();
							$role_img	= 'layout/' . $role['imagem'];
							$has_role	= true;
						}				
					?>
					<div class="morto" style="margin-top: 10px" id="cnPMorto<?php echo $f ?>"></div>
					<div style="float: left;" class="team-role-box">
						<img width="20" src="<?php echo img($role_img) ?>" id="i-role-<?php echo $f ?>" data-tooltip-float="1" style="margin-top: -1px"/>
						<?php if($role_id != ''): ?>
							<?php specialization_tooltip($role['id'], 'i-role-' . $f, null) ?>					
						<?php endif; ?>
					</div>
					<div style="height: 24px; float: left; width: 98px" id="cnPBuff<?php echo $f ?>"></div>
					<div style="clear: both"></div>
					<img src='<?php echo player_imagem($player->id, "pequena"); ?>' />
					
					<b><?php echo $player->getAttribute('nome') ?></b><br />
					
					<div style="position:relative; top: 5px">
						<?php barra_exp5($player->hp,  $player->max_hp,  119, 'HP:  ' .  $player->hp, "#2C531D", "#537F3D", 3, "id='cnPHP"  . $f . "'", "hp") ?>
						<?php barra_exp5($player->sp,  $player->max_sp,  119, 'CHK:  ' . $player->sp, "#2C531D", "#537F3D", 3, "id='cnPSP"  . $f . "'", "sp") ?>
						<?php barra_exp5($player->sta, $player->max_sta, 119, 'STA: ' .  $player->sta,"#2C531D", "#537F3D", 3, "id='cnPSTA" . $f . "'", "sta") ?>
					</div>
				</div>
			<?php endfor; ?>
		</div>
		<div id="pvp-separator">
			<div id="pvp-log"><div id="cnLog"></div></div>
			<div id="cnAction" style="position:relative; top: 10px">--</div>
		</div>
		<div class="pvp-versus-box pvp-versus-right">
			<?php if($batalha['id_tipo'] == 1): ?>
				<?php $npc = gzunserialize($batalha['npc1']) ?>
				<div>
					<div style="height: 20px; width: 100%" id="cnEBuff5"></div>
					<img src='<?php echo img('layout/profile-4x4/desafios/' . $npc->getAttribute('imagem')) ?>' /><br />
					<b><?php echo $npc->getAttribute('nome') ?></b>
					<div style="position:relative; top: 10px; left: 10px">
						<?php barra_exp4($npc->hp,  $npc->max_hp,  219, 'HP:  ' .  $npc->hp, "id='cnEHP5'", "r") ?>
						<?php barra_exp4($npc->sp,  $npc->max_sp,  261, 'CHK:  ' . $npc->sp, "id='cnESP5'" , "r") ?>
						<?php barra_exp4($npc->sta, $npc->max_sta, 232, 'STA: ' .  $npc->sta, "id='cnESTA5'" , "r") ?>
					</div>
				</div>
			<?php elseif($batalha['id_tipo'] == 2): ?>
				<div>
				<?php for($f = 5; $f <= 8; $f++): ?>
				<?php $npc = gzunserialize($batalha['npc' . ($f - 4)]); ?>
				<?php if($f == 7): ?>
					<div style="clear: both"></div>
				<?php endif; ?>
				<div class="eFrame" id="cneFrame<?php echo $f ?>" style="float: right; height:215px; cursor: pointer; position: relative; width:125px; margin-bottom: 45px">
					<div class="morto" id="cnEMorto<?php echo $f - 4 ?>"></div>
					<div style="height: 20px; width: 100%" id="cnEBuff<?php echo $f ?>"></div>
					<img src='<?php echo img('layout/profile-4x4/desafios/' . $npc->getAttribute('imagem')); ?>' /><br />
					<b><?php echo $npc->getAttribute('nome') ?></b>
					<div style="position:relative; top:5px;">
						<?php barra_exp5($npc->hp,  $npc->max_hp,  119, 'HP:  ' .  $npc->sp, "#2C531D", "#537F3D", 3, "id='cnEHP"  . $f . "'", "hp") ?>
						<?php barra_exp5($npc->sp,  $npc->max_sp,  119, 'CHK:  ' . $npc->sp, "#2C531D", "#537F3D", 3, "id='cnESP"  . $f . "'", "sp") ?>
						<?php barra_exp5($npc->sta, $npc->max_sta, 119, 'STA: ' .  $npc->sta,"#2C531D", "#537F3D", 3, "id='cnESTA" . $f . "'", "sta") ?>
					</div>			
				</div>
				<?php endfor; ?>		
				</div>
			<?php else: ?>
				<?php for($f = 1; $f <= 4; $f++): ?>
				<?php $player = new Recordset('SELECT * FROM player WHERE id=' . $batalha['e' . $f]) ?>
				<div>
					<img src='<?php  ?>' />
					<?php echo $player['nome'] ?>
				</div>
				<?php endfor; ?>
			<?php endif; ?>
		</div>
		<div class="break"></div>
	</div>
	<div style="clear: both"></div>
	<?php require('template/painel_pvp.php') ?>
	<?php require('template/painel_pvp_actionbar.php') ?>
</div>
