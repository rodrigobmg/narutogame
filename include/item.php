<?php
	function item_tooltip($for, $item, $show_reqs = false, $pre_extra = '') {
		if(is_numeric($item)) {
			$item = new Item($item);
		}
		
		$requirements = Item::getRequirementLog();
		
		if(!sizeof($requirements)) {
			$requirements = array();
		}
?>
    <div class="ex_tooltip" title="<?php echo $for ?>">
		<?php echo $pre_extra ?>
        <div>
			<strong class="azul"><?php echo $item->nome ?></strong><br />
			<?php echo $item->descricao ?>
        </div><br />
        <?php if($show_reqs): ?>
			<strong style="font-size:12px; color:#8d9aa8"><?php echo t('item_tooltip.requerimentos') ?></strong><hr />
			<ul>
			<?php foreach($requirements as $requirement): ?>
				<li><?php echo $requirement ?></li>
			<?php endforeach; ?>
			</ul>
        <?php endif; ?>
        <br />
        <strong style="font-size:12px; color:#8d9aa8"><?php echo t('geral.bonus')?></strong><hr />
        <ul>
			<?php $ats_type = !$item->tipo_bonus ? "%" : t('geral.pontos') ?>
			<?php if($item->atk_fisico): ?>
				<li><?php echo $item->atk_fisico ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.atk_fisico') ?></li>
			<?php endif; ?>
			<?php if($item->atk_magico): ?>
				<li><?php echo $item->atk_magico ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.atk_magico') ?></li>
			<?php endif; ?>
			<?php if($item->tai): ?>
				<li><?php echo $item->tai ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.tai') ?></li>
			<?php endif; ?>
			<?php if($item->ken): ?>
				<li><?php echo $item->ken ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.ken') ?></li>
			<?php endif; ?>
			<?php if($item->nin): ?>
				<li><?php echo $item->nin ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.nin') ?></li>
			<?php endif; ?>
			<?php if($item->gen): ?>
				<li><?php echo $item->gen ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.gen') ?></li>
			<?php endif; ?>
			<?php if($item->agi): ?>
				<li><?php echo $item->agi ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.agi') ?></li>
			<?php endif; ?>
			<?php if($item->ene): ?>
				<li><?php echo $item->ene ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.ene') ?></li>
			<?php endif; ?>
			<?php if($item->con): ?>
				<li><?php echo $item->con ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.con') ?></li>
			<?php endif; ?>
			<?php if($item->inte): ?>
				<li><?php echo $item->inte ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.inte') ?></li>
			<?php endif; ?>
			<?php if($item->forc): ?>
				<li><?php echo $item->forc ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.forc') ?></li>
			<?php endif; ?>
			<?php if($item->res): ?>
				<li><?php echo $item->res ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.at.res') ?></li>
			<?php endif; ?>
			<?php if($item->def_base): ?>
				<li><?php echo $item->def_base ?> <?php echo t('item_tooltip.at.def_base') ?></li>
			<?php endif; ?>
			<?php if($item->def_fisico): ?>
				<li><?php echo $item->def_fisico ?> <?php echo t('item_tooltip.at.def_fisico') ?></li>
			<?php endif; ?>
			<?php if($item->def_magico): ?>
				<li><?php echo $item->def_magico ?> <?php echo t('item_tooltip.at.def_magico') ?></li>
			<?php endif; ?>
			<?php if((float)$item->prec_fisico): ?>
				<li><?php echo $item->prec_fisico ?> <?php echo t('item_tooltip.at.prec_fisico') ?></li>
			<?php endif; ?>
			<?php if((float)$item->prec_magico): ?>
				<li><?php echo $item->prec_magico ?> <?php echo t('item_tooltip.at.prec_magico') ?></li>
			<?php endif; ?>
			<?php if((float)$item->crit_min): ?>
				<li><?php echo $item->crit_min ?> <?php echo t('item_tooltip.at.crit_min') ?></li>
			<?php endif; ?>
			<?php if((float)$item->crit_max): ?>
				<li><?php echo $item->crit_max ?> <?php echo t('item_tooltip.at.crit_max') ?></li>
			<?php endif; ?>
			<?php if((float)$item->crit_total): ?>
				<li><?php echo $item->crit_total ?> <?php echo t('item_tooltip.at.crit_total') ?></li>
			<?php endif; ?>
			<?php if((float)$item->esq_min): ?>
				<li><?php echo $item->esq_min ?> <?php echo t('item_tooltip.at.esq_min') ?></li>
			<?php endif; ?>
			<?php if((float)$item->esq_max): ?>
				<li><?php echo $item->esq_max ?> <?php echo t('item_tooltip.at.esq_max') ?></li>
			<?php endif; ?>
			<?php if((float)$item->esq_total): ?>
				<li><?php echo $item->esq_total ?> <?php echo t('item_tooltip.at.esq_total') ?></li>
			<?php endif; ?>
			<?php if((float)$item->esq): ?>
				<li><?php echo $item->esq ?> <?php echo t('item_tooltip.at.esq') ?></li>
			<?php endif; ?>
			<?php if((float)$item->det): ?>
				<li><?php echo $item->det ?> <?php echo t('item_tooltip.at.det') ?></li>
			<?php endif; ?>
			<?php if((float)$item->conv): ?>
				<li><?php echo $item->conv ?> <?php echo t('item_tooltip.at.conv') ?></li>
			<?php endif; ?>
			<?php if((float)$item->conc): ?>
				<li><?php echo $item->conc ?> <?php echo t('item_tooltip.at.conc') ?></li>
			<?php endif; ?>
			<?php if((float)$item->esquiva): ?>
				<li><?php echo $item->esquiva ?> <?php echo t('item_tooltip.at.esquiva') ?></li>
			<?php endif; ?>
			<?php if ($item->id_tipo == 25): ?>
				<?php if($item->req_con): ?>
					<li><?php echo sprintf(t('item_tooltip.at.req_con'), $item->req_con) ?></li>
				<?php endif; ?>
				<?php if($item->req_tai): ?>
					<li><?php echo sprintf(t('item_tooltip.at.req_tai'), $item->req_tai) ?></li>
				<?php endif; ?>
				<?php if($item->req_ken): ?>
					<li><?php echo sprintf(t('item_tooltip.at.req_ken'), $item->req_ken) ?></li>
				<?php endif; ?>
				<?php if($item->req_for): ?>
					<li><?php echo sprintf(t('item_tooltip.at.req_for'), $item->req_for) ?></li>
				<?php endif; ?>
				<?php if($item->req_int): ?>
					<li><?php echo sprintf(t('item_tooltip.at.req_int'), $item->req_int) ?></li>
				<?php endif; ?>
				<?php if($item->req_agi): ?>
					<li><?php echo sprintf(t('item_tooltip.at.req_agi'), $item->req_agi) ?></li>
				<?php endif; ?>
			<?php endif ?>
			<?php if($item->id_tipo == 37): ?>
				<?php
					$kinjutsu_dir	= $item->tipo_bonus ? 'enemy' : 'friendly';
				?>
				<?php if($item->bonus_hp): ?>
					<li><?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_hp')) ?></li>
				<?php endif; ?>
				<?php if($item->bonus_sp): ?>
					<li><?php echo sprintf(t('item_tooltip..kinjutsu.' . $kinjutsu_dir . '.bonus_sp')) ?></li>
				<?php endif; ?>
				<?php if($item->bonus_sta): ?>
					<li><?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_sta')) ?></li>
				<?php endif; ?>
				<?php if($item->consume_hp): ?>
					<li><?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.consume_hp'), $item->consume_hp) ?></li>
				<?php endif; ?>
				<?php if($item->consume_sp): ?>
					<li><?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.consume_sp'), $item->consume_sp) ?></li>
				<?php endif; ?>
				<?php if($item->consume_sta): ?>
					<li><?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.consume_sta'), $item->consume_sta) ?></li>
				<?php endif; ?>			
			<?php else: ?>
				<?php if($item->bonus_hp): ?>
					<li><?php echo $item->bonus_hp ?><?php echo t('item_tooltip.at.bonus_hp') ?></li>
				<?php endif; ?>
				<?php if($item->bonus_sp): ?>
					<li><?php echo $item->bonus_sp ?><?php echo $ats_type ?> <?php echo t('item_tooltip.at.bonus_sp') ?></li>
				<?php endif; ?>
				<?php if($item->bonus_sta): ?>
					<li><?php echo $item->bonus_sta ?><?php echo $ats_type ?> <?php echo t('item_tooltip.at.bonus_sta') ?></li>
				<?php endif; ?>
				<?php if($item->consume_hp): ?>
					<li><?php echo sprintf(t('item_tooltip.at.consume_hp'), $item->consume_hp) ?></li>
				<?php endif; ?>
				<?php if($item->consume_sp): ?>
					<li><?php echo sprintf(t('item_tooltip.at.consume_sp'), $item->consume_sp) ?></li>
				<?php endif; ?>
				<?php if($item->consume_sta): ?>
					<li><?php echo sprintf(t('item_tooltip.at.consume_sta'), $item->consume_sta) ?></li>
				<?php endif; ?>
				<?php if($item->bonus_treino): ?>
					<li><?php echo $item->bonus_treino ?> <?php echo t('item_tooltip.at.bonus_treino') ?></li>
				<?php endif; ?>
			<?php endif ?>
			<?php if($item->turnos < 0): ?>
				<li><?php echo $item->turnos ?> <?php echo t('item_tooltip.at.turnos') ?></li>
			<?php endif; ?>
			<?php if($item->turnos > 0): ?>
				<li><?php echo $item->turnos ?> <?php echo t('item_tooltip.at.turnos2') ?></li>
			<?php endif; ?>
			<?php if($item->ryou): ?>
				<li><?php echo $item->ryou ?><?php echo t('item_tooltip.at.ryou') ?></li>
			<?php endif; ?>
        </ul>
	<?php if($item->hasModifiers()): ?>
		<?php 
			$modifiers = $item->getModifiers();
			$ats_type  = !$item->tipo_bonus ? "%" : "ponto(s)";
		?>
        <br />
		<hr />
		<?php echo t('item_tooltip.efeitos.p') ?>:
		<br />
		<ul>
		<?php if($modifiers['self_atk_f']): ?>
			<li><?php echo $modifiers['self_atk_fisico'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.atk_fisico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_atk_m']): ?>
			<li><?php echo $modifiers['self_atk_magico'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.atk_magico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_agi']): ?>
			<li><?php echo $modifiers['self_agi'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.agi') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_con']): ?>
			<li><?php echo $modifiers['self_con'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.con') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_forc']): ?>
			<li><?php echo $modifiers['self_forc'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.forc') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_ene']): ?>
			<li><?php echo $modifiers['self_ene'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.ene') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_inte']): ?>
			<li><?php echo $modifiers['self_inte'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.inte') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_res']): ?>
			<li><?php echo $modifiers['self_res'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.res') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_def_base']): ?>
			<li><?php echo $modifiers['self_def_base'] ?> <?php echo t('item_tooltip.mod.def_base') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_def_fisico']): ?>
			<li><?php echo $modifiers['self_def_fisico'] ?> <?php echo t('item_tooltip.mod.def_fisico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_def_magico']): ?>
			<li><?php echo $modifiers['self_def_magico'] ?> <?php echo t('item_tooltip.mod.def_magico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_crit']): ?>
			<li><?php echo $modifiers['self_crit'] ?> <?php echo t('item_tooltip.mod.crit') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_esq']): ?>
			<li><?php echo $modifiers['self_esq'] ?> <?php echo t('item_tooltip.mod.esq') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_def_extra']): ?>
			<li><?php echo $modifiers['self_def_extra'] ?> <?php echo t('item_tooltip.mod.def_extra') ?></li>
		<?php endif; ?>
		<?php if($modifiers['self_esquiva']): ?>
			<li><?php echo $modifiers['self_esquiva'] ?> <?php echo t('item_tooltip.mod.esquiva') ?></li>
		<?php endif; ?>
		</ul>
        <br />
		<hr />
		<?php echo t('item_tooltip.efeitos.e') ?>: <br />
		<ul>
		<?php if($modifiers['target_atk_fisico']): ?>
			<li><?php echo $modifiers['target_atk_fisico'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.atk_fisico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_atk_magico']): ?>
			<li><?php echo $modifiers['target_atk_fisico'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.atk_magico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_agi']): ?>
			<li><?php echo $modifiers['target_agi'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.agi') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_con']): ?>
			<li><?php echo $modifiers['target_con'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_forc']): ?>
			<li><?php echo $modifiers['target_forc'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.forc') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_ene']): ?>
			<li><?php echo $modifiers['target_ene'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.ene') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_inte']): ?>
			<li><?php echo $modifiers['target_inte'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.inte') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_res']): ?>
			<li><?php echo $modifiers['target_res'] ?> <?php echo $ats_type ?> <?php echo t('item_tooltip.mod.res') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_def_base']): ?>
			<li><?php echo $modifiers['target_def_base'] ?> <?php echo t('item_tooltip.mod.def_base') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_def_fisico']): ?>
			<li><?php echo $modifiers['target_def_fisico'] ?> <?php echo t('item_tooltip.mod.def_fisico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_def_magico']): ?>
			<li><?php echo $modifiers['target_def_magico'] ?> <?php echo t('item_tooltip.mod.def_magico') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_crit']): ?>
			<li><?php echo $modifiers['target_crit'] ?> <?php echo t('item_tooltip.mod.crit') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_esq']): ?>
			<li><?php echo $modifiers['target_esq'] ?> <?php echo t('item_tooltip.mod.esq') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_def_extra']): ?>
			<li><?php echo $modifiers['target_def_extra'] ?> <?php echo t('item_tooltip.mod.def_extra') ?></li>
		<?php endif; ?>
		<?php if($modifiers['target_esquiva']): ?>
			<li><?php echo $modifiers['target_esquiva'] ?> <?php echo t('item_tooltip.mod.esquiva') ?></li>
		<?php endif; ?>
		</ul>			
	<?php endif; ?>
    </div>
    <script type="text/javascript">
        updateTooltips();
    </script>
<?php
	}
	
	function generic_tooltip($for, $data) {
?>
		<?php if(is_array($data)): ?>
			<div class="ex_tooltip" title="<?php echo $for ?>">
				<ul>
				<?php foreach($data as $item): ?>
    				<li><?php echo $item ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
        <?php else: ?>
			<div class="ex_tooltip" title="<?php echo $for ?>">
				<?php echo $data ?>
			</div>        
		<?php endif; ?>
		<script type="text/javascript">
            updateTooltips();
		</script>
<?php
	}
	
	function bonus_tooltip($for, $item, $item_next = NULL, $extra = NULL) {
		$item	= is_numeric($item) ? new Item($item) : $item;
		
		if($item_next) {
			$item_next	= is_a($item, 'Item') ? $item_next : new Item($item_next);
		}
		
		$arDescsB	= array(
			'agi'			=> t('at.agi'),
			'con'			=> t('at.con'),
			'forc'			=> t('at.for'),
			'inte'			=> t('at.int'),
			'res'			=> t('at.res'),
			'nin'			=> t('at.nin'),
			'gen'			=> t('at.gen'),
			'tai'			=> t('at.tai'),
			'ken'			=> t('at.ken'),
			'atk_fisico'	=> t('formula.atk_fisico'),
			'atk_magico'	=> t('formula.atk_magico'),
			'def_base'		=> t('formula.def_base'),
			'def_fisico'	=> t('formula.def_fisico'),
			'def_magico'	=> t('formula.def_magico'),
			'ene'			=> t('at.ene'),
			'prec_fisico'	=> t('formula.prec_fisico'),
			'prec_magico'	=> t('formula.prec_magico'),
			'crit_min'		=> t('formula.crit_min'),
			'crit_max'		=> t('formula.crit_max'),
			'crit_total'	=> t('formula.crit_total'),
			'esq_min'		=> t('formula.esq_min'),
			'esq_max'		=> t('formula.esq_max'),
			'esq_total'		=> t('formula.esq_total'),
			'esq'			=> t('formula.esq'),
			'det'			=> t('formula.det'),
			'conv'			=> t('formula.conv'),
			'conc'			=> t('formula.conc'),
			'esquiva'		=> t('formula2.esquiva')
		);

		$arDescs	= StaticCache::get('bonus_tooltip_ardescs');
		
		if(!$arDescs) {
			$arDescs	= array_merge($arDescsB, array(
				'bonus_hp'		=> t('formula.hp'),
				'bonus_sp'		=> t('formula.sp'),
				'bonus_sta'		=> t('formula.sta'),
			));
			
			StaticCache::store('bonus_tooltip_ardescs', $arDescs);
		}
		
		$arImages	= array(
			'agi'			=> 'layout/icones/agi.png',
			'con'			=> 'layout/icones/conhe.png',
			'forc'			=> 'layout/icones/forc.png',
			'inte'			=> 'layout/icones/inte.png',
			'res'			=> 'layout/icones/defense.png',
			'nin'			=> 'layout/icones/nin.png',
			'gen'			=> 'layout/icones/gen.png',
			'tai'			=> 'layout/icones/tai.png',
			'ken'			=> 'layout/icones/ken.png',
			'atk_fisico'	=> 'layout/icones/atk_fisico.png',
			'atk_magico'	=> 'layout/icones/atk_magico.png',
			'def_base'		=> 'layout/icones/shield.png',
			'def_fisico'	=> 'layout/icones/def_fisico.png',
			'def_magico'	=> 'layout/icones/def_magico.png',
			'ene'			=> 'layout/icones/ene.png',
			'hp'			=> 'layout/icones/p_hp.png',
			'sp'			=> 'layout/icones/p_chakra.png',
			'sta'			=> 'layout/icones/p_stamina.png',
			'bonus_hp'		=> 'layout/icones/p_hp.png',
			'bonus_sp'		=> 'layout/icones/p_chakra.png',
			'bonus_sta'		=> 'layout/icones/p_stamina.png',
			'prec_fisico'	=> 'layout/icones/prec_tai.png',
			'prec_magico'	=> 'layout/icones/prec_nin_gen.png',
			'crit_min'		=> 'layout/icones/target2.png',
			'crit_max'		=> 'layout/icones/target2.png',
			'crit_total'	=> 'layout/icones/target2.png',
			'esq_min'		=> 'layout/icones/esquiva.png',
			'esq_max'		=> 'layout/icones/esquiva.png',
			'esq_total'		=> 'layout/icones/esquiva.png',
			'esq'			=> 'layout/icones/esquiva.png',
			'det'			=> 'layout/icones/deter.png',
			'conv'			=> 'layout/icones/convic.png',
			'conc'			=> 'layout/icones/target2.png',
			'esquiva'		=> 'layout/icones/esquiva.png'
		);
		
		$show_level		= $item_next && $item_next->level <= 3 ? true : false;
		$tipo			= $item->id_tipo;
		$kinjutsu_dir	= $item->tipo_bonus ? 'enemy' : 'friendly';
?>
	<div class="ex_tooltip" title="<?php echo $for ?>">
		<table border="0" cellpadding="2" cellspacing="2" style="width:255px !important;">
		<tr>
			<td colspan="6"><strong class="azul" style="font-size:12px"><?php echo $item->nome ?></strong><br /></td>
		</tr>
        <tr>
  			<?php //if(in_array($tipo, array(5, 6, 7, 24))): ?>
  			<?php if($tipo == 5 || $tipo == 6 || $tipo == 9 || $tipo == 42 || $tipo == 43 || $tipo == 47 || $tipo == 48 || 
  					 $tipo == 7 || $tipo == 24): ?>
			<?php else: ?>
				<td colspan="6"><span class="<?php echo $item->raridade ?>"><?php echo $item->raridade ?></span></td>
			<?php endif ?>
		</tr>
        <tr>
			<td colspan="6">
				<?php echo Recordset::query('SELECT nome_' . Locale::get() . ' FROM item_tipo WHERE id=' . $tipo, true)->row()->{'nome_' . Locale::get()} ?>
				<?php
					if ($item->id_tipo == 5) {
						if($item->sem_turno) {
							if($item->hasModifiers()) {
								$dir	= 'debuff';

								foreach ($item->getModifiers() as $k => $v) {
									if(preg_match('/self_/', $k) && $v) {
										$dir	= 'buff';
										break;
									}
								}

								echo ' - ' . t('item_tooltip.type5.' . $dir);
							}
						} else {
							if($item->def_base || $item->def_magico || $item->def_fisico) {
								echo ' - ' . t('item_tooltip.type5.def');
							} else {
								echo ' - ' . t('item_tooltip.type5.atk');
							}
						}

						if($item->id_elemento) {
							echo ' - ' . t('item_tooltip.type5.elem');
						}
					}
				?>
			</td>
		</tr>
    	<?php if($tipo != 43 && $tipo != 42 && $tipo != 47 && $tipo != 48 && $tipo != 9 && $tipo != 37 && $tipo != 40 && $tipo != 4): // Ramem): ?>
		<tr>
			<td colspan="6"><strong class="laranja"><?php echo t('item_tooltip.valores_combate') ?></strong><hr /></td>
		</tr>
		<?php endif; ?>
		<?php if($show_level && $tipo != 37): ?>
		<tr>
			<td colspan="2"><?php echo t('item_tooltip.nivel_atual') ?></td>
			<td align="center"><?php echo t('item_tooltip.prox_nivel') ?></td>
		</tr>
		<?php endif; ?>
		<?php if($tipo == 24): // Medicinal ?>
			<?php if($item->bonus_hp): ?>
			<tr>
				<td><img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" /> <?php echo t('item_tooltip.recupera2') ?></td>
				<td>+<?php echo $item->bonus_hp ?></td>
				<?php if($show_level): ?>
					<td><span style="color:#3C3"+<?php echo $item_next->bonus_hp ?></span></td>
				<?php endif; ?>
			</tr>
			<?php endif; ?>
			<?php if($item->bonus_sp): ?>
			<tr>
				<td><img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" /> <?php echo t('item_tooltip.recupera2') ?></td>
				<td>+<?php echo $item->bonus_sp ?></td>
				<?php if($show_level): ?>
					<td><span style="color:#3C3"+<?php echo $item_next->bonus_sp ?></span></td>
				<?php endif; ?>
			</tr>
			<?php endif; ?>
			<?php if($item->bonus_sta): ?>
			<tr>
				<td><img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" /> <?php echo t('item_tooltip.recupera2') ?></td>
				<td>+<?php echo $item->bonus_sta ?></td>
				<?php if($show_level): ?>
					<td><span style="color:#3C3"+<?php echo $item_next->bonus_sta ?></span></td>
				<?php endif; ?>
			</tr>
			<?php endif; ?>
		<?php elseif($tipo == 37): ?>
			<tr>
				<td colspan="2">
					<hr />
					<?php if($item->bonus_treino): ?>
						<?php echo t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.all') ?>
					<?php else: ?>
						<?php echo t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.one') ?>
					<?php endif ?>
					<hr />
				</td>
			</tr>
			<?php if($item->defensivo): ?>
			<tr>
				<td colspan="2">
					<?php echo t('item_tooltip.kinjutsu.dead' . ($item->bonus_treino ? '_all' : '')) ?>
					<hr />
				</td>
			</tr>
			<?php endif ?>
			<tr>
				<td>
					<?php if($show_level): ?>
					<table border="0" cellspacing="0" cellpadding="4">
						<tr>
							<td style="white-space: nowrap"><?php echo t('item_tooltip.nivel_atual') ?></td>
							<td align="center" style="white-space: nowrap"><?php echo t('item_tooltip.prox_nivel') ?></td>							
						</tr>
						<?php if($item->bonus_hp): ?>
						<tr>
							<td width="40" align="center">
								<img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" />
								<?php echo $item->bonus_hp ?>
							</td>
							<td align="center">
								<?php echo $item_next->bonus_hp ?>
							</td>
							<td>
								<?php echo t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_hp2') ?>
							</td>
						</tr>
						<?php endif ?>
						<?php if($item->bonus_sp): ?>
						<tr>
							<td width="40" align="center">
								<img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" />
								<?php echo $item->bonus_sp ?>
							</td>
							<td align="center">
								<?php echo $item_next->bonus_sp ?>
							</td>
							<td>
								<?php echo t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_sp2') ?>
							</td>
						</tr>
						<?php endif ?>
						<?php if($item->bonus_sta): ?>
						<tr>
							<td width="40" align="center">
								<img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" />
								<?php echo $item->bonus_sta ?>
							</td>
							<td align="center">
								<?php echo $item_next->bonus_sta ?>
							</td>
							<td>
								<?php echo t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_sta2') ?>
							</td>
						</tr>
						<?php endif ?>
					</table>
					<?php else: ?>
						<?php if($item->bonus_hp): ?>
							<img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" />
							<?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_hp'), $item->bonus_hp) ?><br />
						<?php endif ?>
						<?php if($item->bonus_sp): ?>
							<img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" />
							<?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_sp'), $item->bonus_sp) ?><br />
						<?php endif ?>
						<?php if($item->bonus_sta): ?>
							<img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" />
							<?php echo sprintf(t('item_tooltip.kinjutsu.' . $kinjutsu_dir . '.bonus_sta'), $item->bonus_sta) ?><br />
						<?php endif ?>
					<?php endif ?>
				</td>
			</tr>
		<?php elseif($item->sem_turno): // Buffs e afins ?>
			<?php
				$rMod = $item->getModifiers();
				
				if($show_level) {
					$rModNext = $item_next->getModifiers();
				}
				
				$e_self = $rMod['self_ken']  || $rMod['self_tai']  || $rMod['self_nin']  || $rMod['self_gen'] || $rMod['self_agi'] || $rMod['self_ene'] ||
					      $rMod['self_forc'] || $rMod['self_inte'] || $rMod['self_res'] || $rMod['self_atk_magico'] || $rMod['self_atk_fisico'] ||
						  $rMod['self_def_base']	|| $rMod['self_def_fisico']	|| $rMod['self_def_magico'] || $rMod['self_esquiva'];
				
				$e_target = $rMod['target_ken']  || $rMod['target_tai']  || $rMod['target_nin']  || $rMod['target_gen'] || $rMod['target_agi'] || $rMod['target_ene'] ||
					        $rMod['target_forc'] || $rMod['target_inte'] || $rMod['target_res'] || $rMod['target_atk_magico'] || $rMod['target_atk_fisico'] ||
							$rMod['target_def_base'] || $rMod['target_def_fisico'] || $rMod['target_def_magico'] || $rMod['target_esquiva'];
			?>
			<?php if($e_self): ?>
				<tr>
					<td colspan="6"><?php echo t('item_tooltip.efeitos.p') ?></td>
				</tr>
				<?php foreach($arDescsB as $k => $v): ?>
				<?php 
					if(!$rMod['self_' . $k]) {
						continue; 
					}
				?>
				<tr>
					<td><img align="absmiddle" src="<?php echo img($arImages[$k]) ?>" /> <?php echo $v ?></td>
					<td>+ <?php echo $rMod['self_' . $k] ?></td>
					<?php if($show_level): ?>
					<td align="center"><span class="verde">+ <?php echo $rModNext['self_' . $k] ?></span></td>					
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			
			<?php if($e_target): ?>
				<tr>
					<td colspan="6"><?php echo t('item_tooltip.efeitos.e') ?></td>
				</tr>
				<?php foreach($arDescsB as $k => $v): ?>
				<?php 
					if(!$rMod['target_' . $k]) {
						continue; 
					}
				?>
				<tr>
					<td><img align="absmiddle" src="<?php echo img($arImages[$k]) ?>" /> <?php echo $v ?></td>
					<td><?php echo $rMod['target_' . $k] ?></td>
					<?php if($show_level): ?>
					<td align="center"><span style="color:#3C3">+ <?php echo $rModNext['target_' . $k] ?></span></td>					
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>		
			<?php endif; ?>
		<?php else: // Ataque e tal ?>
			<?php if($item->defensivo): ?>
				<?php if ($item->def_base): ?>
					<tr>
						<td><img align="absmiddle" src="<?php echo img($arImages['def_base']) ?>" /> <?php echo $arDescs['def_base'] ?></td>
						<td align="left">+ <?php echo $item->def_base ?></td>
						<?php if($show_level): ?>
						<td align="center"><span style="color:#3C3">+ <?php echo $item_next->def_base ?></span></td>					
						<?php endif; ?>
					</tr>
				<?php endif ?>

				<?php if ($item->def_fisico): ?>
					<tr>
						<td><img align="absmiddle" src="<?php echo img($arImages['def_fisico']) ?>" /> <?php echo $arDescs['def_fisico'] ?></td>
						<td align="left">+ <?php echo $item->def_fisico ?></td>
						<?php if($show_level): ?>
						<td align="center"><span style="color:#3C3">+ <?php echo $item_next->def_fisico ?></span></td>					
						<?php endif; ?>
					</tr>
				<?php endif ?>

				<?php if ($item->def_magico): ?>
					<tr>
						<td><img align="absmiddle" src="<?php echo img($arImages['def_magico']) ?>" /> <?php echo $arDescs['def_magico'] ?></td>
						<td align="left">+ <?php echo $item->def_magico ?></td>
						<?php if($show_level): ?>
						<td align="center"><span style="color:#3C3">+ <?php echo $item_next->def_magico ?></span></td>					
						<?php endif; ?>
					</tr>
				<?php endif ?>
			<?php else: ?>
				<?php if($item->atk_fisico): ?>
				<tr>
					<td><img align="absmiddle" src="<?php echo img($arImages['atk_fisico']) ?>" /> <?php echo $arDescs['atk_fisico'] ?></td>
					<td align="left">+ <?php echo $item->atk_fisico ?></td>
					<?php if($item_next): ?>
					<td align="center"><span style="color:#3C3">+ <?php echo $item_next->atk_fisico ?></span></td>					
					<?php endif; ?>
				</tr>
				<?php endif; ?>
				<?php if($item->atk_magico): ?>
				<tr>
					<td><img align="absmiddle" src="<?php echo img($arImages['atk_magico']) ?>" /> <?php echo $arDescs['atk_magico'] ?></td>
					<td align="left">+ <?php echo $item->atk_magico ?></td>
					<?php if($show_level): ?>
					<td align="center"><span style="color:#3C3">+ <?php echo $item_next->atk_magico ?></span></td>					
					<?php endif; ?>
				</tr>
				<?php endif; ?>
			<?php endif; ?>
			<?php //if(in_array($tipo, array(10, 11, 12, 13, 14, 15, 29, 30, 31))): ?>
			<?php if($tipo == 10 || $tipo == 11 || $tipo == 12 || $tipo == 13 ||
					 $tipo == 14 || $tipo == 15 || $tipo == 29 || $tipo == 30 || $tipo == 31): ?>
			<?php
				$eq_ats	= array('bonus_hp', 'bonus_sp', 'bonus_sta', 'ken','tai', 'nin', 'gen', 'ene', 'agi', 'con', 'inte', 'forc', 'res', 'def_base', 'def_fisico', 'def_magico', 'prec_fisico', 'prec_magico', 'crit_min', 'crit_max','crit_total','esq_min', 'esq_max', 'esq_total','esq', 'det', 'conv', 'conc','esquiva');
			?>
			<?php foreach($eq_ats as $eq_at): ?>
			<?php
				$eq_at_v	= $item->$eq_at;
			?>
			<?php if($eq_at_v != 0): ?>
			<tr>
				<td><img align="absmiddle" src="<?php echo img($arImages[$eq_at]) ?>" /> <?php echo $arDescs[$eq_at] ?></td>
				<td align="left"><?php echo $eq_at_v > 0 ? '+' . $eq_at_v : $eq_at_v ?>
				<?php if($eq_at == 'bonus_hp' || $eq_at == 'bonus_sp' || $eq_at == 'bonus_sta'): ?>
				%
				<?php endif; ?>
				</td>
			</tr>
			<?php endif; ?>
			<?php endforeach ?>
			<?php endif; ?>
		<?php endif; ?>
		<tr>
		<?php if($tipo == 9): // Ramem): ?>
			<td colspan="6">  	
				<img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" /> <?php echo sprintf(t('item_tooltip.recupera'), $item->consume_hp*-1) ?><br />
				<img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" /> <?php echo sprintf(t('item_tooltip.recupera'), $item->consume_sp*-1) ?><br />
				<img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" /> <?php echo sprintf(t('item_tooltip.recupera'), $item->consume_sta*-1) ?><br  />
			</td>
		<?php elseif($tipo == 42): ?>
			<td colspan="6">  	
				<hr />
				<span class="verde">Ganha <?php echo $item->preco ?> de Experiência</span>
			</td>
       <?php elseif($tipo == 47): ?>
			<td colspan="6"> 
				<hr /> 	
				<span class="verde">Ganha <?php echo $item->preco ?> de Treinamento</span>
			</td>
      	<?php elseif($tipo == 48): ?>
			<td colspan="6"> 
				<hr /> 	
				<span class="verde">Ganha <?php echo $item->preco ?> ponto(s) para o Sorteio de Bijuu</span>
			</td>
        <?php elseif($tipo == 43): ?>
			<td colspan="6">  	
				<hr />
				<span class="verde">Ganha <?php echo $item->preco ?> de Ryous</span>
			</td>    
		<?php //elseif(!in_array($tipo, array(10, 11, 12, 13, 14, 15, 29, 30, 31))): ?>
		<?php elseif($tipo != 10 && $tipo != 11 && $tipo != 12 && $tipo != 13 &&
					 $tipo != 14 && $tipo != 15 && $tipo != 29 && $tipo != 30 &&
					 $tipo != 31 && $tipo != 4 && $tipo != 40): ?>
			<td colspan="6">
			<hr />
				<?php if($item->id_tipo == 37): ?>
					<?php if($show_level): ?>
						<table cellspacing="0" cellpadding="4">
							<tr>
								<td style="white-space: nowrap"><?php echo t('item_tooltip.nivel_atual') ?></td>
								<td align="center" style="white-space: nowrap"><?php echo t('item_tooltip.prox_nivel') ?></td>							
							</tr>
							<?php if($item->consume_hp): ?>				
							<tr>
								<td align="center">
									<img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" /> <?php echo $item->consume_hp ?>%
								</td>
								<td align="center">
									<?php echo $item_next->consume_hp ?>%
								</td>
								<td>
									<?php echo t('item_tooltip.base.hp') ?>
								</td>
							</tr>
							<?php endif; ?>
							<?php if($item->consume_sp): ?>
							<tr>
								<td align="center">
									<img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" /> <?php echo $item->consume_sp ?>%
								</td>
								<td align="center">
									<?php echo $item_next->consume_sp ?>%
								</td>
								<td>
									<?php echo t('item_tooltip.base.sp') ?>
								</td>
							</tr>
							<?php endif; ?>
							<?php if($item->consume_sta): ?>
							<tr>
								<td align="center">
									<img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" /> <?php echo $item->consume_sta ?>%
								</td>
								<td align="center">
									<?php echo $item_next->consume_sta ?>%
								</td>
								<td>
									<?php echo t('item_tooltip.base.sta') ?>
								</td>
							</tr>
							<?php endif; ?>
						</table>
					<?php else: ?>
						<?php echo t('item_tooltip.consome_combate') ?><br /><br />
						<?php if($item->consume_hp): ?>				
						<img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" /> <?php echo $item->consume_hp ?>% <?php echo t('item_tooltip.base.hp') ?><br />
						<?php endif; ?>
						<?php if($item->consume_sp): ?>
						<img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" /> <?php echo $item->consume_sp ?>% <?php echo t('item_tooltip.base.sp') ?><br />
						<?php endif; ?>
						<?php if($item->consume_sta): ?>
						<img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" /> <?php echo $item->consume_sta ?>% <?php echo t('item_tooltip.base.sta') ?><br />
						<?php endif; ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo t('item_tooltip.consome_combate') ?>
					<?php if($item->consume_hp): ?>				
					<img align="absmiddle" src="<?php echo img($arImages['hp']) ?>" /> <?php echo $item->consume_hp ?>
					<?php endif; ?>
					<?php if($item->consume_sp): ?>
					<img align="absmiddle" src="<?php echo img($arImages['sp']) ?>" /> <?php echo $item->consume_sp ?>
					<?php endif; ?>
					<?php if($item->consume_sta): ?>
					<img align="absmiddle" src="<?php echo img($arImages['sta']) ?>" /> <?php echo $item->consume_sta ?>
					<?php endif; ?>
				<?php endif ?>
				<hr />
			</td>
        <?php endif; ?>
		</tr>
		</table>
	<?php if($extra): ?>
		<?php echo $extra ?>
	<?php endif; ?>
	</div>
	<script type="text/javascript">updateTooltips();</script>
<?php		
	}

	function vila_item_tooltip($for, $item, $extra = "") {
		global $basePlayer;
		
		if(!is_array($item)) {
			$item	= Recordset::query('SELECT * FROM item WHERE id=' . $item)->row_array();	
		}
		
		$reqs	= Vila::hasRequirements($basePlayer->id_vila, $item);
		$log	= Vila::getRequirementLog();
		
		echo '<div class="ex_tooltip" title="' . $for . '">';
		
		echo '<b class="azul" style="font-size:13px">' . $item['nome_' . Locale::get()] . '</b><br /><br />';
		echo '<ul>';

		echo '<b>'.t('vila.v17').':</b><br />';
		echo join('', $log);

		echo '<br /><b>Bônus:</b><br />';

		if($item['tai']) {
			echo '<li>'.t('vila.v18').' +' . $item['tai'] . ' ' .t('vila.v19') .'</li>';	
		}
		
		if($item['ken']) {
			echo '<li>'.t('vila.v18').' +' . $item['tai'] . ' ' .t('vila.v19') .'</li>';	
		}

		if($item['nin']) {
			echo '<li>+' . $item['nin'] . ' ' .t('vila.v20') .'</li>';
		}

		if($item['gen']) {
			echo '<li>' .t('vila.v21') .' +' . $item['gen'] . '% ' .t('vila.v22') .'</li>';
		}

		if($item['agi']) {
			echo '<li>' .t('vila.v23') .' ' . $item['agi'] . ' ' .t('vila.v24') .'</li>';
		}

		if($item['con']) {
			echo '<li>' .t('vila.v25') .' ' . $item['con'] . '% ' .t('vila.v26') .'</li>';
		}

		if($item['forc']) {
			echo '<li>' .t('vila.v25') .' ' . $item['forc'] . '% ' .t('vila.v27') .'</li>';
		}

		if((int)$item['crit_max']) {
			echo '<li>-' . (int)$item['crit_max'] . '% ' .t('vila.v28') .'</li>';
		}
		if((int)$item['crit_total']) {
			echo '<li>-' . (int)$item['crit_total'] . '% ' .t('vila.v28') .'</li>';
		}

		if((int)$item['crit_min']) {
			echo '<li>' .t('vila.v29') .' +' . (int)$item['crit_min'] . '% ' .t('vila.v30') .'</li>';
		}

		if($item['ene']) {
			echo '<li>'.t('vila.v31') .' ' . $item['ene'] . '% ' .t('vila.v32') .'</li>';
		}

		if($item['inte']) {
			echo '<li>'.t('vila.v31') .' ' . $item['inte'] . '% ' .t('vila.v33') .'</li>';
		}

		if($item['res']) {
			echo '<li>'.t('vila.v31') .' ' . $item['res'] . '% ' .t('vila.v34') .'</li>';
		}

		if($item['atk_fisico']) {
			echo '<li>'.t('vila.v31') .' ' . $item['atk_fisico'] . '% ' .t('vila.v35') .'</li>';
		}

		if($item['atk_magico']) {
			echo '<li>+' . $item['atk_magico'] . ' ' .t('vila.v36') .'</li>';
		}

		if($item['def_base']) {
			echo '<li>+' . $item['def_base'] . ' ' .t('vila.v37') .'</li>';
		}

		if($item['prec_fisico']) {
			echo '<li>'.t('vila.v31') .' ' . $item['prec_fisico'] . '% ' .t('vila.v38') .'</li>';
		}

		if($item['prec_magico']) {
			echo '<li>'.t('vila.v31') .' ' . $item['prec_magico'] . '% ' .t('vila.v39') .'</li>';
		}

		if((int)$item['esq_min']) {
			echo '<li>'. sprintf(t('vila.v40'), (int)$item['esq_min']) .'</li>';
		}

		if((int)$item['esq_max']) {
			echo '<li>'. sprintf(t('vila.v41'), (int)$item['esq_max']) .'</li>';
		}
		if((int)$item['esq_total']) {
			echo '<li>'. sprintf(t('vila.v41'), (int)$item['esq_total']) .'</li>';
		}

		if((int)$item['esq']) {
			echo '<li>'. sprintf(t('vila.v42'), (int)$item['esq']) .'</li>';
		}

		if((int)$item['det']) {
			echo '<li>'. t('vila.v43') .'</li>';
		}

		if((int)$item['conv']) {
			echo '<li>'. sprintf(t('vila.v44'), (int)$item['conv']) .'</li>';
		}

		if((int)$item['conc']) {
			echo '<li>'. sprintf(t('vila.v45'), (int)$item['conc']) .'</li>';
		}

		if((int)$item['req_con']) {
			echo '<li>'. sprintf(t('vila.v46'), (int)$item['req_con']) .'</li>';
		}
		
		if((int)$item['esquiva']) {
			echo '<li>'. sprintf(t('vila.v42'), (int)$item['esquiva']) .'</li>';
		}
		
		echo '</ul>';
		echo $extra;
		echo '</div>';
		echo '<script type="text/javascript">updateTooltips();</script>';
	}
	
	function specialization_tooltip($item, $for, $player, $extra = '') {
		if(is_numeric($item)) {
			$item = new Item($item);
		}
?>		
	    <div class="ex_tooltip ex_tooltip_esp" title="<?php echo $for ?>">
	        <div>
				<strong class="azul"><?php echo $item->nome ?> - Nv. <?php echo $item->ordem ?></strong><br />
				<?php echo $item->descricao ?>
	        </div><br />
		    <ul>
		    	<?php if($item->bonus_hp || $item->bonus_sp || $item->bonus_sta): ?>
			    	<?php if($item->bonus_hp): ?>
			    		<?php if($item->bonus_hp > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.bonus_hp.plus'), abs($item->bonus_hp)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.bonus_hp.minus'), abs($item->bonus_hp)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->bonus_sp): ?>
			    		<?php if($item->bonus_sp > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.bonus_sp.plus'), abs($item->bonus_sp)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.bonus_sp.minus'), abs($item->bonus_sp)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->bonus_sta): ?>
			    		<?php if($item->bonus_sta > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.bonus_sta.plus'), abs($item->bonus_sta)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.bonus_sta.minus'), abs($item->bonus_sta)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
			    	<hr />
		    	<?php endif ?>
	
		    	<?php if($item->consume_hp || $item->consume_sp || $item->consume_sta): ?>
			    	<?php if($item->consume_hp): ?>
			    		<?php if($item->consume_hp > 0): ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.consume_hp.plus'), abs($item->consume_hp)) ?></li>
			    		<?php else: ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.consume_hp.minus'), abs($item->consume_hp)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->consume_sp): ?>
			    		<?php if($item->consume_sp > 0): ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.consume_sp.plus'), abs($item->consume_sp)) ?></li>
			    		<?php else: ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.consume_sp.minus'), abs($item->consume_sp)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->consume_sta): ?>
			    		<?php if($item->consume_sta > 0): ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.consume_sta.plus'), abs($item->consume_sta)) ?></li>
			    		<?php else: ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.consume_sta.minus'), abs($item->consume_sta)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
			    	<hr />
		    	<?php endif ?>
	
		    	<?php if($item->tai || $item->nin || $item->gen || $item->ken): ?>
			    	<?php if($item->tai): ?>
			    		<?php if($item->tai > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.tai.plus'), abs($item->tai)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.tai.minus'), abs($item->tai)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->nin): ?>
			    		<?php if($item->nin > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.nin.plus'), abs($item->nin)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.nin.minus'), abs($item->nin)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->gen): ?>
			    		<?php if($item->gen > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.gen.plus'), abs($item->gen)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.gen.minus'), abs($item->gen)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
			    	<hr />
		    	<?php endif ?>

		    	<?php if($item->atk_fisico || $item->atk_magico || $item->def_base): ?>
			    	<?php if($item->atk_fisico): ?>
			    		<?php if($item->atk_fisico > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.atk_fisico.plus'), abs($item->atk_fisico)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.atk_fisico.minus'), abs($item->atk_fisico)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
			    	<?php if($item->def_base): ?>
			    		<?php if($item->def_base > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.def_base.plus'), abs($item->def_base)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.def_base.minus'), abs($item->def_base)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>
		
			    	<?php if($item->atk_magico): ?>
			    		<?php if($item->atk_magico > 0): ?>
			    			<li class="bonus"><?php echo sprintf(t('item_tooltip.esp.atk_magico.plus'), abs($item->atk_magico)) ?></li>
			    		<?php else: ?>
			    			<li class="loss"><?php echo sprintf(t('item_tooltip.esp.atk_magico.minus'), abs($item->atk_magico)) ?></li>
			    		<?php endif ?>
			    	<?php endif ?>		
			    	<hr />
		    	<?php endif ?>
		    	<?php echo $extra ?>
		    </ul>
	    </div>
		<script type="text/javascript">updateTooltips();</script>
<?php
	}

	function enhance_tooltip($for, $item, $bis = true, $return = false, $player = false, $slot_id = false, $target = null, $filled = false) {
		if(is_numeric($item)) {
			$item = new Item($item);
		}
		
		$style		= !$bis ? 'style="color: #F00"' : '';
		$t_pm		= function ($t, $value, $item, $bis) {
			$color	= '';
		
			if(!$bis) {
				$value	= floor($value / 2);
				$color	= 'style="color: #F00"';
			}

			if(preg_match('/consume\.(sp|hp|sta)/', $t)) {
				$sign	= '%';
			} else {
				$sign	= (!$item->tipo_bonus ? '%' : '');
			}
		
			return '<li ' . $color . '>' . sprintf(t($t . '.' . ($value > 0 ? 'plus' : 'minus')), abs($value) . $sign) . '</li>';
		};
		
		if($return) {
			ob_start();
		}
?>
	    <div class="ex_tooltip" title="<?php echo $for ?>">
	        <div>
				<strong class="azul"><?php echo $item->nome ?></strong><br /><br />
	        </div>
	        <!--
	        <?php if(!$item->defensivo): ?>
		        <div <?php echo $style ?>><?php echo sprintf(t('enhance_tooltip.slot'), $item->tempo_espera) ?><hr /></div>
			<?php else: ?>
		        <div <?php echo $style ?>><?php echo sprintf(t('enhance_tooltip.slot_only'), $item->defensivo) ?><hr /></div>			
			<?php endif ?>
			-->
			<?php if($item->sem_turno): ?>
				<?php
					$mod			= $item->getModifiers();
					$has_buff_dir	= false;
					$buff_dir		= 'debuff';
					$target_dir		= 'debuff';

					if(sizeof($mod)) {
						$has_buff_dir	= true;
					}

					foreach($mod as $k => $v) {
						if(preg_match('/self_/', $k) && $v) {
							$buff_dir	= 'buff';
							break;
						}
					}

					if(sizeof($mod) && $target) {
						$mod	= Recordset::query('SELECT * FROM item_modificador WHERE id_item=' . $target['id_item'], true);

						if($mod->num_rows) {
							foreach($mod->row_array() as $k => $v) {
								if(preg_match('/self_/', $k) && $v) {
									$target_dir	= 'buff';
									break;
								}
							}							
						}
					}

					$style_buff	= '';

					if($target && !$target['sem_turno'] || ($target && $target_dir != $buff_dir)) {
						$style_buff	= 'style="color: #F00"';
					}
				?>
				<?php /*<?php if ($has_buff_dir): ?>
					<div <?php echo $style_buff ?>><?php echo t('enhance_tooltip.' . $buff_dir . '_only') ?></div>
				<?php else: ?>
					<div <?php echo $style_buff ?>><?php echo t('enhance_tooltip.buff_or_debuff') ?></div>
				<?php endif ?>*/?>
			<?php else: ?>
				<?php /*
				<?php
					$style_buff	= '';

					if($target && $target['sem_turno']) {
						$style_buff	= 'style="color: #F00"';
					}
				?>
				
				<div <?php echo $style_buff ?>><?php echo t('enhance_tooltip.not_buff') ?></div>
				<?php if($item->id_elemento): ?>
					<?php
						$style_elem	= '';

						if($target && !$target['id_elemento']) {
							$style_elem	= 'style="color: #F00"';
						}
					?>
					<div <?php echo $style_elem ?>><?php echo t('enhance_tooltip.element_only') ?></div>
				<?php endif ?>
				
				<?php if($item->def_base): ?>
					<?php
						$style_def	= '';

						if(($target && $target['sem_turno']) || ($target && !$target['def_base'])) {
							$style_def	= 'style="color: #F00"';
						}
					?>
					<div <?php echo $style_def ?>><?php echo t('enhance_tooltip.defense_only') ?></div>
				<?php else: ?>
					<?php
						$style_atk	= '';

						if(($target && $target['sem_turno']) || ($target && $target['def_base'])) {
							$style_atk	= 'style="color: #F00"';
						}
					?>
					<div <?php echo $style_atk ?>><?php echo t('enhance_tooltip.attack_only') ?></div>
				<?php endif ?>
				*/ ?>
			<?php endif ?>
			<?php /*<?php if ($item->req_graduacao && $player): ?>
				<?php
					$ak			= $player->id_vila == 6 ? '_ak' : '';
					$grad1		= Recordset::query('SELECT nome' . $ak . '_' . Locale::get() . ' AS nome FROM graduacao WHERE id=' . ($item->req_graduacao - 1))->row()->nome;
					$grad_style	= '';

					if($target && ($item->req_graduacao - 1) > $target['req_graduacao']) {
						$grad_style	= 'style="color: #F00"';
					}
				?>
				<div <?php echo $grad_style ?>><?php echo sprintf(t('enhance_tooltip.grad'), $grad1) ?></div>
			<?php endif ?>
			*/?>
	        <div>
	        	<b class="laranja"><?php echo t('enhance_tooltip.enhances') ?></b><br /><br />
				<ul style="margin:0; padding:0">
					<?php if($item->atk_magico != 0): ?>
						<?php echo $t_pm('enhance_tooltip.atk_magico', $item->atk_magico, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->atk_fisico != 0): ?>
						<?php echo $t_pm('enhance_tooltip.atk_fisico', $item->atk_fisico, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->def_base != 0): ?>
						<?php echo $t_pm('enhance_tooltip.def_base', $item->def_base, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->consume_hp != 0): ?>
						<?php echo $t_pm('enhance_tooltip.consume.hp', $item->consume_hp, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->consume_sp != 0): ?>
						<?php echo $t_pm('enhance_tooltip.consume.sp', $item->consume_sp, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->consume_sta != 0): ?>
						<?php echo $t_pm('enhance_tooltip.consume.sta', $item->consume_sta, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->bonus_hp != 0): ?>
						<?php echo $t_pm('enhance_tooltip.bonus.hp', $item->bonus_hp, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->bonus_sp != 0): ?>
						<?php echo $t_pm('enhance_tooltip.bonus.sp', $item->bonus_sp, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->bonus_sta != 0): ?>
						<?php echo $t_pm('enhance_tooltip.bonus.sta', $item->bonus_sta, $item, $bis) ?>
					<?php endif ?>

					<?php if($item->req_agi != 0): ?>
						<?php echo $t_pm('enhance_tooltip.reqs.agi', $item->req_agi, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->req_con != 0): ?>
						<?php echo $t_pm('enhance_tooltip.reqs.con', $item->req_con, $item, $bis) ?>
					<?php endif ?>

					<?php if($item->turnos != 0): ?>
						<?php echo $t_pm('enhance_tooltip.duration', $item->turnos, $item, $bis) ?>
					<?php endif ?>
					<?php if($item->cooldown != 0): ?>
						<?php echo $t_pm('enhance_tooltip.cooldown', $item->cooldown, $item, $bis) ?>
					<?php endif ?>

					<?php if($item->id_elemento): ?>
						<li <?php echo $style ?>><?php echo sprintf(t('enhance_tooltip.element.' . ($item->id_elemento2 < 0 ? 'inc' : 'red')), Recordset::query('SELECT nome FROM elemento WHERE id=' . $item->id_elemento)->row()->nome, abs($bis ? $item->id_elemento2 : $item->id_elemento2 / 2)) ?></li>
					<?php endif ?>

					<?php if($item->prec_fisico != 0): ?>
						<?php echo $t_pm('enhance_tooltip.precision', $item->prec_fisico, $item, $bis) ?>
					<?php endif ?>
				</ul>
				<?php if($item->sem_turno): ?>
					<?php
						$modifiers	= $item->getModifiers();
					?>
					<?php if(sizeof($modifiers)): ?>
						<?php
							$arDescsB	= array(
								'agi'			=> t('at.agi'),
								'con'			=> t('at.con'),
								'forc'			=> t('at.for'),
								'inte'			=> t('at.int'),
								'res'			=> t('at.res'),
								'nin'			=> t('at.nin'),
								'gen'			=> t('at.gen'),
								'tai'			=> t('at.tai'),
								'ken'			=> t('at.ken'),
								'atk_fisico'	=> t('formula.atk_fisico'),
								'atk_magico'	=> t('formula.atk_magico'),
								'def_base'		=> t('formula.def_base'),
								'ene'			=> t('at.ene'),
								'prec_fisico'	=> t('formula.prec_fisico'),
								'prec_magico'	=> t('formula.prec_magico'),
								'crit_min'		=> t('formula.crit_min'),
								'crit_max'		=> t('formula.crit_max'),
								'crit_total'	=> t('formula.crit_total'),
								'esq_min'		=> t('formula.esq_min'),
								'esq_max'		=> t('formula.esq_max'),
								'esq_total'		=> t('formula.esq_total'),
								'esq'			=> t('formula.esq'),
								'det'			=> t('formula.det'),
								'conv'			=> t('formula.conv'),
								'conc'			=> t('formula.conc'),
								'esquiva'		=> t('formula2.esquiva')
							);
							
							$has_mine	= false;
							$has_enemy	= false;
							
							foreach($arDescsB as $_ => $at) {
								if($modifiers['self_' . $_]) {
									$has_mine	= true;
								}
	
								if($modifiers['target_' . $_]) {
									$has_enemy	= true;
								}
							}
						?>
						<?php if($has_mine): ?>
							<div><?php echo t('enhance_tooltip.buff_mine') ?></div>
							<ul style="margin:0; padding:0">
							<?php foreach($arDescsB as $_ => $at): ?>
								<?php if(!$modifiers['self_' . $_]) continue; ?>
								<li <?php echo $style ?>><?php echo $at ?>: <?php echo $bis ? $modifiers['self_' . $_] : $modifiers['self_' . $_] / 2 ?></li>
							<?php endforeach ?>
							</ul>
						<?php endif ?>
						<?php if($has_enemy): ?>
							<div><?php echo t('enhance_tooltip.buff_enemy') ?></div>
							<ul style="margin:0; padding:0">
							<?php foreach($arDescsB as $_ => $at): ?>
								<?php if(!$modifiers['target_' . $_]) continue; ?>
								<li <?php echo $style ?>><?php echo $at ?>: <?php echo $bis ? $modifiers['target_' . $_] : $modifiers['target_' . $_] / 2 ?></li>
							<?php endforeach ?>
							</ul>
						<?php endif ?>
					<?php endif ?>
				<?php endif ?>
				<?php /*
				<?php if($player && $slot_id > 1): ?>
					<?php
						$l1_atks		= 0;
						$l2_atks		= 0;
						$l3_atks		= 0;
						$l4_atks		= 0;
						$l5_atks		= 0;
						$atks			= 0;

						$l1_atks_total	= 0;
						$l2_atks_total	= 0;
						$l3_atks_total	= 0;
						$l4_atks_total	= 0;
						$l5_atks_total	= 0;
						$atks_total		= 0;
					
						switch($slot_id) {
							case 2:
								$l1_atks	= 5;
							
								break;

							case 3:
								$l2_atks	= 13;
							
								break;

							case 4:
								$l3_atks	= 22;
							
								break;

							case 5:
								$l4_atks	= 30;
							
								break;
						}
						
						$atks	= $l1_atks + $l2_atks + $l3_atks + $l4_atks;
						
						foreach($player->getItems(5) as $item) {
							if($item->aprimoramento) {
								for($f = sizeof($item->aprimoramento); $f >= 1; $f--) {
									$var	= 'l' . $f . '_atks_total';
									$$var++;
									$atks_total++;
								}
							}
						}
					?>
					<hr />
					<div><?php echo t('enhance_tooltip.reqs.extra') ?><br /><br /></div>
					<ul>
						<!--
						<?php if($l1_atks): ?>
						<li <?php echo $l1_atks > $l1_atks_total ? 'style="color: #F00"' : 'style="text-decoration:line-through"' ?>><?php echo sprintf(t('enhance_tooltip.reqs.l1'), $l1_atks) ?></li>
						<?php endif ?>
						<?php if($l2_atks): ?>
						<li <?php echo $l2_atks > $l2_atks_total ? 'style="color: #F00"' : 'style="text-decoration:line-through"' ?>><?php echo sprintf(t('enhance_tooltip.reqs.l2'), $l2_atks) ?></li>
						<?php endif ?>
						<?php if($l3_atks): ?>
						<li <?php echo $l3_atks > $l3_atks_total ? 'style="color: #F00"' : 'style="text-decoration:line-through"' ?>><?php echo sprintf(t('enhance_tooltip.reqs.l3'), $l3_atks) ?></li>
						<?php endif ?>
						<?php if($l4_atks): ?>
						<li <?php echo $l4_atks > $l4_atks_total ? 'style="color: #F00"' : 'style="text-decoration:line-through"' ?>><?php echo sprintf(t('enhance_tooltip.reqs.l4'), $l4_atks) ?></li>
						<?php endif ?>
						-->
						<li <?php echo $atks > $atks_total ? 'style="color: #F00"' : 'style="text-decoration:line-through"' ?>><?php echo sprintf(t('enhance_tooltip.reqs.all'), $atks, ($atks - $atks_total < 0 ? 0 : $atks - $atks_total)) ?></li>
					</ul>
				<?php endif ?>
				*/ ?>
				<hr />
				<?php echo sprintf(t('enhance_tooltip.slot'), $item->tempo_espera) ?>
				<hr />
				<?php if($player && $slot_id): ?>
					<?php if ($filled): ?>
						<?php
							$pvp_discount	= 0;

							if($player->hasItem(array(22653, 22654, 22655)) && $vip_pvp = $player->getVIPItem(array(22653, 22654, 22655))) {
								$pvp_discount	= $vip_pvp['vezes'];
							}

							//$pvp_points_avail	= $player->ponto_batalha - $player->ponto_batalha_gasto;
							$needed_pvp_ponits	= $item->coin - percent($pvp_discount, $item->coin);
						?>
						<?php  /*<div <?php echo $pvp_points_avail >= $needed_pvp_ponits ? '' : 'style="color: #F00"' ?>><?php echo $needed_pvp_ponits ?> Pontos de batalha requerido</div>*/?>
					<?php else: ?>
						<div <?php echo Player::getFlag('ponto_aprimoramento', $player->id) >= $slot_id ? '' : 'style="color: #F00"' ?>><?php echo sprintf(t('enhance_tooltip.point_needed'), $slot_id) ?></div>
					<?php endif ?>
				<?php endif ?>
	        </div>
	    </div>
		<script type="text/javascript">updateTooltips();</script>
<?php
		if($return) {
			return ob_get_clean();
		}
	}
