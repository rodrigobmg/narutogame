<?php
	$bg		= ++$color_counter % 2 ? "class='cor_sim'" : "class='cor_nao'";
	$reqs	= Item::hasRequirement($i, $basePlayer);
?>
<tr <?php echo $bg ?>>
	<td width="90"  align="center">
		<div class="img-lateral-dojo2">
			<img src="<?php echo img('layout/invocacoes/'. $invocacao['id'].'/'.$i->getAttribute('ordem').'.png')?>" width="53" height="53" style="margin-top:5px"/>
		</div>	
	</td>
	<td width="172"  align="center"><strong class="amarelo" style="font-size:13px"><?php echo $i->getAttribute('nome_'. Locale::get()) ?></strong></td>
	<td width="138"  align="center">
		<img id="i-item-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" style="cursor: pointer" />
		<?php echo generic_tooltip('i-item-' . $i->id, Item::getRequirementLog()) ?>
	</td>
	<td width="273"  height="34" align="center" class="bonus-text">
		<?php if($i->nin): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->nin ?></strong> <img src="<?php echo img('layout/icones/nin.png') ?>" /><br /><?php echo t('at.nin')?> 
		</p>
		<?php endif; ?>
		<?php if($i->tai): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->tai ?></strong> <img src="<?php echo img('layout/icones/tai.png') ?>" /><br /><?php echo t('at.tai')?>  
		</p>
		<?php endif; ?>
		<?php if($i->ken): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->ken ?></strong> <img src="<?php echo img('layout/icones/ken.png') ?>" /><br /><?php echo t('at.ken')?>  
		</p>
		<?php endif; ?>
		<?php if($i->gen): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->gen ?></strong> <img src="<?php echo img('layout/icones/gen.png') ?>" /><br /><?php echo t('at.gen')?>  
		</p>
		<?php endif; ?>
		<?php if($i->ene): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->ene ?></strong> <img src="<?php echo img('layout/icones/ene.png') ?>" /><br /><?php echo t('at.ene')?>  
		</p>
		<?php endif; ?>
		<?php if($i->forc): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->forc ?></strong> <img src="<?php echo img('layout/icones/forc.png') ?>" /><br /><?php echo t('at.for')?>  
		</p>
		<?php endif; ?>
		<?php if($i->inte): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->inte ?></strong> <img src="<?php echo img('layout/icones/inte.png') ?>" /><br /><?php echo t('at.int')?>  
		</p>
		<?php endif; ?>
		<?php if($i->con): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->con ?></strong> <img src="<?php echo img('layout/icones/conhe.png') ?>" /><br /><?php echo t('at.con')?>  
		</p>
		<?php endif; ?>
		<?php if($i->agi): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->agi ?></strong> <img src="<?php echo img('layout/icones/agi.png') ?>" /><br /><?php echo t('at.agi')?>  
		</p>
		<?php endif; ?>
		<?php if($i->res): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->res ?></strong> <img src="<?php echo img('layout/icones/defense.png') ?>" /><br /><?php echo t('at.res')?>  
		</p>
		<?php endif; ?>

		<?php if($i->det): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->det ?>%</strong> <img src="<?php echo img('layout/icones/deter.png') ?>" /><br /><?php echo t('formula.det')?>  
		</p>
		<?php endif; ?>
		<?php if($i->conc): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->conc ?>%</strong> <img src="<?php echo img('layout/icones/target2.png') ?>" /><br /><?php echo t('formula.conc')?> 
		</p>
		<?php endif; ?>
		<?php if($i->conv): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->conv ?>%</strong> <img src="<?php echo img('layout/icones/convic.png') ?>" /><br /><?php echo t('formula.conv')?> 
		</p>
		<?php endif; ?>
		<?php if($i->esq): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->esq ?>%</strong> <img src="<?php echo img('layout/icones/esquiva.png') ?>" /><br /><?php echo t('formula.esq')?> 
		</p>
		<?php endif; ?>
		<?php if($i->atk_fisico): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->atk_fisico ?></strong> <img src="<?php echo img('layout/icones/atk_fisico.png') ?>" /><br /><?php echo t('formula.atk_fisico')?> 
		</p>
		<?php endif; ?>
		<?php if($i->atk_magico): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->atk_magico ?></strong> <img src="<?php echo img('layout/icones/atk_magico.png') ?>" /><br /><?php echo t('formula.atk_magico')?>
		</p>
		<?php endif; ?>

		<?php if($i->prec_fisico): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->prec_fisico ?></strong> <img src="<?php echo img('layout/icones/prec_tai.png') ?>" /><br /><?php echo t('formula.prec_fisico')?>
		</p>
		<?php endif; ?>
		<?php if($i->prec_magico): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->prec_magico ?></strong> <img src="<?php echo img('layout/icones/prec_nin_gen.png') ?>" /><br /><?php echo t('formula.prec_magico')?>
		</p>
		<?php endif; ?>
		<?php if($i->crit_min): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->crit_min ?></strong> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.crit_min')?> 
		</p>
		<?php endif; ?>
		<?php if($i->crit_max): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->crit_max ?></strong> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.crit_max')?> 
		</p>
		<?php endif; ?>



		<?php if($i->bonus_hp): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->bonus_hp ?>%</strong> <img src="<?php echo img('layout/icones/p_hp.png') ?>" /><br /><?php echo t('formula.hp')?> 
		</p>
		<?php endif; ?>
		<?php if($i->bonus_sp): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->bonus_sp ?>%</strong> <img src="<?php echo img('layout/icones/p_chakra.png') ?>" /><br /><?php echo t('formula.sp')?> 
		</p>
		<?php endif; ?>
		<?php if($i->bonus_sta): ?>
		<p>
		<strong class="verde" style="font-size:13px">+ <?php echo $i->bonus_sta ?>%</strong> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.sta')?> 
		</p>
		<?php endif; ?>
	</td>
	<td width="92"  align="center">
		<?php if($reqs && !$basePlayer->hasItem($i->id)): ?>
		<form method="post" action="?acao=invocacao_treinar">
			<input type="hidden" name="id" value="<?php echo $i->id ?>" />
			<a class="button" data-trigger-form="1"><?php echo t('botoes.treinar') ?></a>
		</form>
		<?php else: ?>
			<?php if($basePlayer->hasItem($i->id)): ?>
			<a class="button ui-state-green"><?php echo t('botoes.treinado') ?></a>
			<?php else: ?>
			<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>

			<?php endif; ?>
		<?php endif; ?>
	</td>
</tr>
<tr height="4"></tr>
