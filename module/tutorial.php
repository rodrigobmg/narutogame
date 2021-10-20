<div class="titulo-secao"><p><?php echo t('menus.tutorial')?></p></div>

<div class="msg_gai">
	<div class="msg">
		<div class="tutorial_base">
		<div class="tutorial <?php echo $basePlayer->tutorial()->status ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->status ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=personagem_status">Status</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->clas ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->clas ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=clas"><?php echo t('tutorial.cla') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->invocacao ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->invocacao ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=invocacao"><?php echo t('tutorial.invocacao') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->selo ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->selo ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=selo"><?php echo t('tutorial.selo') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->sennin ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->sennin ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=mode_sennin">Sennin</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->portoes ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->portoes ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=portoes"><?php echo t('tutorial.portoes') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->talentos ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->talentos ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=arvore_talento"><?php echo t('tutorial.talentos') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->elementos ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->elementos ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=personagem_elementos"><?php echo t('tutorial.elementos') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->bijuus ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->bijuus ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=bijuus">Bijuus</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->espadas ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->espadas ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=espadas"><?php echo t('tutorial.espadas') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->estudo ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->estudo ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=estudo_ninja"><?php echo t('tutorial.estudo') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->sorte ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->sorte ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=sorte_ninja"><?php echo t('tutorial.sorte') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->profissao ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->profissao ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=profissao"><?php echo t('tutorial.profissao') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->graduacao ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->graduacao ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=graduacoes"><?php echo t('tutorial.graduacoes') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->treinamento ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->treinamento ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=academia_treinamento"><?php echo t('tutorial.treinamento') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->golpes ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->golpes ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=personagem_jutsu"><?php echo t('tutorial.aprimoramento') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->jutsus ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->jutsus ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=academia_jutsu">Jutsus</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->medicinal ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->medicinal ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=academia_jutsu&tipo=3"><?php echo t('tutorial.medicinal') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->kinjutsu ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->kinjutsu ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=academia_jutsu&tipo=6">Kinjutsu</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->vila ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->vila ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=vila"><?php echo t('tutorial.vila') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->missoes ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->missoes ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=missoes"><?php echo t('tutorial.missoes') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->eventos ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->eventos ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=vila_eventos"><?php echo t('tutorial.eventos') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->objetivos ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->objetivos ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=vila_objetivos"><?php echo t('tutorial.objetivo') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->ramen ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->ramen ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=ramen_shop">Ramen</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->shop ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->shop ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=ninja_shop">Ninja Shop</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->equips ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->equips ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=equipamentos_ninja"><?php echo t('tutorial.equipamentos') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->battle_npc ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->battle_npc ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=dojo">Dojo</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->battle_4x4 ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->battle_4x4 ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=dojo4x4">Dojo 4x4</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->battle ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->battle ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=dojo"><?php echo t('tutorial.batalha') ?></a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->vip ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->vip ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=jogador_vip">Vip</a>
			</div>
		</div>
		<div class="tutorial <?php echo $basePlayer->tutorial()->fidelity ? 'cor_sim' : 'cor_nao'?>">
			<div class="image">
				<img src="<?php echo $basePlayer->tutorial()->fidelity ? img('layout/ok_16x16.gif') : img('layout/delete_16x16.gif')?>" />
			</div>
			<div class="texto">	
				<a href="?secao=fidelidade"><?php echo t('tutorial.fidelidade') ?></a>
			</div>
		</div>
		</div>
		<span style="position: relative; top: -35px; left: 50px; font-size: 14px" class="verde"><?php echo t('tutorial.premio') ?></span>
	</div>
</div>
<div class="break"></div>