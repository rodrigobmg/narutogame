<div id="esquerda">
	<div id="menu_repete" style="<?php echo $_SESSION['basePlayer'] ? 'padding-top: 60px' : '' ?>">
		<?php if ($_SESSION['orig_user_id']): ?>
			<a class="button" href="javascript:;" id="support-switch-back">Voltar para usuário original</a>
			<br />
			<br />
			<br />
			<script type="text/javascript">
				$(document).ready(function () {
					$('#support-switch-back').on('click', function () {
						lock_screen(true);

						$.ajax({
							url:		'?secao=suporte_ticket&id=<?php echo $_SESSION['orig_ticket_id'] ?>',
							data:		{switch_back: 1},
							type:		'post',
							success:	function () {
								location.href	= '?secao=suporte_ticket&id=<?php echo $_SESSION['orig_ticket_id'] ?>';
							}
						});
					});
				});
			</script>
		<?php endif ?>
		<?php if (!$_SESSION['basePlayer']): ?>
			<?php if(!$_SESSION['logado']): ?>
            <div class="titulos-menu"><img src="<?php echo img('layout/menu-titulos/cadastro-rapido'.(Locale::get()=="br" ? "" : "2").'.png') ?>" alt="<?php echo t('templates.t10')?>"/></div>
				<form id="fast-signup-form" onsubmit="return false">
					<div class="form-group">
						<label>E-Mail</label>
						<input type="text" autocomplete="off" name="email" />
					</div>
					<div class="form-group">
						<label><?php echo t('cadastro.ca5')?></label>
						<input type="text" autocomplete="off" name="email_confirmacao" />
					</div>
					<div class="form-group">
						<label><?php echo t('cadastro.ca6')?></label>
						<input type="password" autocomplete="off" name="senha" />
					</div>
					<div class="form-group">
						<label><?php echo t('cadastro.ca7')?></label>
						<input type="password" autocomplete="off" name="confirma_senha" />
					</div>
					<div class="form-group captcha">
						<label><?php echo t('recupera_senha.cod_segur')?></label>
						<input type="text" name="captcha" maxlength="3" />
						<img src="index.php?acao=captcha&_cache=<?= date("YmdHis") ?>&quick" class="image" />
					</div>
					<div class="form-group aceite">
						<input type="checkbox" name="aceite" value="1" />
						<?php echo t('cadastro.ca14')?> <a href="index.php?secao=termos_uso" target="_blank" class="linkTopo"><?php echo t('cadastro.ca15')?></a>
					</div>
					<div class="form-group submit-container">
						<a class="submit"><?php echo t('botoes.jogar_agora') ?></a>
					</div>
				</form>
			<?php else: ?>
            
				<?php if ($user_is_banned): ?>
					<?php if ($is_permanent_ban): ?>
						<b>Conta banida Permanentemente</b>
					<?php else: ?>
						<b>Conta banida até:<br /><?php echo date('d/m/Y H:i:s', $ban_will_end_at) ?></b>
					<?php endif ?>
					<br />
					<br />
				<?php endif ?>
			<?php endif; ?>
			<?php if($basePlayer && $basePlayer->exp >= Player::getNextLevel($basePlayer->level) && !$basePlayer->id_batalha): ?>
				<input type="button" class="button" id="bNextLevel" style="margin-left: 24px" onclick="location.href='?secao=proximo_nivel'" value="<?php echo t('templates.t7')?>" />
			<?php endif ?>
		  	<?php foreach($menu as $menu_item): ?>
				<?php if(!sizeof($menu_item['items'])) continue; ?>
				<div class="titulos-menu"><img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/menu-titulos/<?php echo $menu_item['img'] ?>" alt="<?php echo $menu_item['img'] ?>"/></div>
				<ul>
					<?php if(is_array($menu_item['items'])): ?>
						<?php foreach($menu_item['items'] as $menu_subitem): ?>
							<li><a href="<?php echo (isset($menu_subitem['externo']) && $menu_subitem['externo'] ? "": "?secao=") . $menu_subitem['href'] ?>"><?php echo $menu_subitem['item'] ?></a></li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			<?php endforeach; ?>
			<div class="titulos-menu"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/menu-titulos/publicidade'.(Locale::get()=="br" ? "" : "2").'.png') ?>" alt="<?php echo t('templates.t10')?>"/></div>
			<?php
				$links_patrocinados = Recordset::query('SELECT id, nome, href FROM link_patrocinado ORDER BY id', true);
			?>
			<ul>
				<?php foreach($links_patrocinados->result_array() as $link_patrocinado): ?>
					<li><a href="?acao=link&id=<?php echo $link_patrocinado['id'] ?>&go=<?php echo urlencode($link_patrocinado['href'])?>" target="blank"><?php echo $link_patrocinado['nome'] ?></a></li>
				<?php endforeach; ?>
			</ul>
			<br /><br />
		<?php else: ?>
			<div>
				<?php require 'personagem.php' ?>
				<div class="break"></div>
			</div>
			<?php /*
			<!-- DIPLOMACIA ALERTA! -->
			<?php if(date("N") == 5): ?>
				<div class="titulos-menu"><img src="<?php echo img('layout/menu-titulos/diplomacia.png') ?>" /></div>
				<ul>
					<li>
						<a id="left-diplomacy-link" href="?secao=diplomacia"><?php echo t('templates.t39')?></a>
						<?php ob_start(); ?>
						  <b><?php echo t('templates.t40')?></b>
						<?php echo generic_tooltip('left-diplomacy-link', ob_get_clean()) ?>
					</li>
				</ul>
			<?php endif; ?>
			<!-- DIPLOMACIA ALERTA! -->
			*/?>
			<?php
				$missao_tempo		= menu_conds(Recordset::query('SELECT * FROM menu WHERE id=67', true)->row_array(), $basePlayer)  ? 'missoes_espera' : '';
				$missao_intrativa	= menu_conds(Recordset::query('SELECT * FROM menu WHERE id=69', true)->row_array(), $basePlayer)  ? 'missoes_status' : '';
				$missao_especial	= menu_conds(Recordset::query('SELECT * FROM menu WHERE id=145', true)->row_array(), $basePlayer) ? 'missoes_status&especial' : '';
				$missao_guild		= menu_conds(Recordset::query('SELECT * FROM menu WHERE id=191', true)->row_array(), $basePlayer) ? 'guild_missoes_status' : '';
				$missao_guild2		= menu_conds(Recordset::query('SELECT * FROM menu WHERE id=192', true)->row_array(), $basePlayer) ? 'guild_missoes_status_guild' : '';
				$missao_invasao		= $basePlayer->missao_invasao ? 'missoes_invasao' : '';
				
			?>
			<?php if ($missao_tempo || $missao_intrativa || $missao_especial || $missao_invasao || $missao_guild || $missao_guild2): ?>
				<div class="titulos-menu"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/menu-titulos/missao_atual.png') ?>" /></div>
				<ul>
					<?php if ($missao_tempo): ?>
						<li><a href="?secao=<?php echo $missao_tempo ?>"><?php echo t('titulos.missoes_status') ?></a></li>
					<?php endif ?>
					<?php if ($missao_intrativa): ?>
						<li><a href="?secao=<?php echo $missao_intrativa ?>"><?php echo t('menus.missao_status') ?></a></li>
					<?php endif ?>
					<?php if ($missao_especial): ?>
						<li><a href="?secao=<?php echo $missao_especial ?>"><?php echo t('menus.missao_status_especial') ?></a></li>
					<?php endif ?>
					<?php if ($missao_invasao): ?>
						<li><a href="?secao=<?php echo $missao_invasao ?>"><?php echo t('menus.guild_invasao') ?></a></li>
					<?php endif ?>
					<?php if ($missao_guild): ?>
						<li><a href="?secao=<?php echo $missao_guild ?>"><?php echo t('menus.guild_status_missao_solo') ?></a></li>
					<?php endif ?>
					<?php if ($missao_guild2): ?>
						<li><a href="?secao=<?php echo $missao_guild2 ?>"><?php echo t('menus.guild_status_missao_guild') ?></a></li>
					<?php endif ?>
				</ul>
			<?php endif ?>
			<?php if ($basePlayer->id_evento): ?>
				<div class="titulos-menu"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/menu-titulos/evento_atual.png') ?>" /></div>
				<ul>
					<li><a href="?secao=evento_detalhe&id=<?php echo encode($basePlayer->id_evento) ?>"><?php echo t('menus.equipe_detalhe_evento') ?></a></li>
				</ul>
			<?php endif ?>
			<?php require 'menu_missoes.php' ?>
		<?php if($_SESSION['basePlayer']): ?>
			<!-- Evento Global -->
			<?php if($basePlayer->eventoGlobal()): ?>
				<div class="titulos-menu"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/menu-titulos/evento_atual.png') ?>" /></div>
				<?php
					$sql_g = Recordset::query("select count(id) as total from evento_npc_evento where id_evento=181 and morto_global = 0");
					$total_z = $sql_g->row_array();
				?>
				<ul>
					<?php if($basePlayer->id_vila == 6):  ?>
						<li>
							<b class="vinho"><?php echo t('templates.t15')?></b><br /><br />    
							<span class="chumbo"><?php echo t('templates.t16')?> <strong><?php echo $total_z['total'];?> Zetsus </strong>!!</span>
						</li>
					<?php else: ?>
						<li>
							<b class="vinho"><?php echo t('templates.t15')?></b><br /><br />    
							<span class="chumbo"><?php echo t('templates.t17')?> <strong><?php echo $total_z['total'];?> Zetsus</strong> <?php echo t('templates.t18')?> !!</span>
						</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>		

			<!-- Evento Vila -->
			<?php 
				$eventoVila = Recordset::query("SELECT * FROM evento_vila WHERE iniciado=1");	
			?>
			<?php if($eventoVila->num_rows): ?>
				<div class="titulos-menu"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/menu-titulos/evento_atual.png') ?>" /></div>
				<?php foreach($eventoVila->result_array() as $evento): ?>
					<strong class="vinho" style="font-size:14px"><?php echo $evento['nome_' . Locale::get()] ?></strong><br /><br />
					<?php
						switch($evento['tipo']) {
							case 'bijuu';	$tipo	= '34'; break;
							case 'armas';	$tipo	= '35'; break;
							case 'espadas';	$tipo	= '36'; break;
						}
						
						$total	= Recordset::query('SELECT COUNT(id) AS total FROM item WHERE id_tipo=' . $tipo)->row()->total;
						$have	= Recordset::query('SELECT SUM(CASE WHEN (SELECT aa.id_vila FROM player aa WHERE aa.id=a.id_player) = ' . $basePlayer->id_vila . ' THEN 1 ELSE 0 END) AS total FROM player_item a WHERE id_item_tipo=' . $tipo)->row()->total;
					?>
					<div id="evento-descricao" class="chumbo"><?php echo t('jogador_vip.jv30')?> <span class="laranja_menu"><?php echo $have ?> <?php echo t('geral.de')?> <?php echo $total ?> <?php echo t('jogador_vip.jv31')?> <?php echo $evento['nome_' . Locale::get()] ?></span> <?php echo t('jogador_vip.jv32')?>.</div><br /><br />

					<?php
						$my_items	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM item a JOIN player_item b ON a.id=b.id_item AND b.id_item_tipo=' . $tipo . ' WHERE b.id_player=' . $basePlayer->id);
					?>
					<?php if($my_items->num_rows): ?>
					<span class="chumbo"><?php echo t('jogador_vip.jv33')?>:</span>
					<ul>
						<?php foreach($my_items->result_array() as $my_item): ?>
						<li class="laranja_menu"><?php echo $my_item['nome'] ?></li>
						<?php endforeach ?>
					</ul>
					
					<?php endif ?>
				<?php endforeach ?>
			<?php endif ?>
		<?php endif ?>
		<?php foreach($menu as $menu_item): ?>
			<?php if($menu_item['id'] != 1) continue; ?>
			<div class="titulos-menu"><img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/menu-titulos/<?php echo $menu_item['img'] ?>" alt="<?php echo $menu_item['img'] ?>"/></div>
			<ul>
				<?php if(is_array($menu_item['items'])): ?>
					<?php foreach($menu_item['items'] as $menu_subitem): ?>
						<li><a href="<?php echo (isset($menu_subitem['externo']) && $menu_subitem['externo'] ? "": "?secao=") . $menu_subitem['href'] ?>"><?php echo $menu_subitem['item'] ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		<?php endforeach; ?>
		<?php endif ?>
		
		
<script id="_waur2p">var _wau = _wau || []; _wau.push(["dynamic", "49an6wmdnc", "r2p", "c4302bffffff", "small"]);</script><script async src="//waust.at/d.js"></script>
		<br /><br />		
	</div>
</div>
<div id="direita">