<div class="titulo-secao"><p><?php echo t('calculadora.ca1')?></p></div>
<style>
	.item-filtro-at p {
		width: 25%;
		text-align: center;
		float: left;
		height: 35px
	}
</style>
<script type="text/javascript">
	head.ready(function() {
		$(document).ready(function () {
			var _avail_pt	= 0;
			var _used_pt	= 0;
			var _points		= [];
			
			<?php
				$treino = Recordset::query('SELECT id, ponto_total FROM treino_exp', true);
				
				foreach($treino->result_array() as $k => $v):
			?>
			_points[<?php echo $v['id'] ?>]	= <?php echo $v['ponto_total'] ?>;
			<?php endforeach; ?>
			
			function _update() {
				var t = _tipos[$('#s-tipo').val()];
	
				_used_pt = 0;
				
				$('.spinner').each(function () {
					_used_pt += !this.value ? 0 : parseInt(this.value);
				});
	
				$('#c-ene').html(parseInt(t.ene) + (3 * $('#t-level').val()));
	
				//console.log('AVAIL: ' + _avail_pt + ' / USED: ' + _used_pt);
			}
			
			head.ready(function() {
				$('.spinner').each(function () {
					var val		= $(this).val();
					var _this	= this;
					
					$(this).spinit({
						height:		30,
						width:		50,
						min:		0,
						max:		200,
						initValue:	val,
						callback: function (v) {
							
							_update();
						}
					});
				});
			});
					
			$('#t-training').keyup(function () {
				$('#d-training-points').html('--');
				$('#d-training-days').html('--');
				$('#d-training-days-a').html('--');
				$('#d-training-days-b').html('--');
				$('#d-training-days-c').html('--');
	
				for(var i in _points) {
					if($(this).val() >= _points[i]) {
						_avail_pt = i;
						
						$('#d-training-points').html(_avail_pt);
						$('#d-training-days').html(Math.floor(_points[i] / 6000) || '0');
						$('#d-training-days-a').html(Math.floor(_points[i] / 7000) || '0');
						$('#d-training-days-b').html(Math.floor(_points[i] / 8000) || '0');
						$('#d-training-days-c').html(Math.floor(_points[i] / 9000) || '0');
						
						//console.log(Math.floor(_points[i] / 6000) + '/' + _points[i]);
						
						//console.log('found ' + i + ' at ' + _points[i]);
						//break;
					} else {
						break;
					}
				}
			});
			
			function _update_checks() {
			
			}
			
			$('.habil').click(function () {
				_update_checks();
			});
			
			$('.habil-link').click(function () {
				$('.habil-block').hide();
				$('.habil-block-' + $(this).attr('rel')).show();
			});
			
			$('.filtro').change(function () {
				$('.item-filtro-' + $(this).attr('role')).hide();
				$('.item-filtro-' + $(this).val()).show();
			});
			
			$('#s-tipo').change(function () {
				var t = _tipos[$(this).val()];
				
				$('#c-nin').html(t.nin);
				$('#c-tai').html(t.tai);
				$('#c-ken').html(t.ken);
				$('#c-gen').html(t.gen);
				$('#c-agi').html(t.agi);
				$('#c-con').html(t.con);
				$('#c-for').html(t.forc);
				$('#c-ene').html(t.ene);
				$('#c-int').html(t.inte);
				$('#c-res').html(t.res);
				
				_update();
			});
			
			$('#t-level').spinit({
				height:		30,
				width:		50,
				min:		1,
				max:		60,
				initValue:	$('#t-level').val(),
				callback: function (v) {
					_update();
				}
			});
			
			$('#s-tipo').trigger('change');
			$('#t-training').trigger('keyup');
	
			_update_checks();
			_update();		
		});		
	});
</script>
<?php if($_POST): ?>
<?php
	$tipo	= Recordset::query('SELECT * FROM classe_tipo WHERE id=' . (int)$_POST['tipo_classe'])->row_array();
	
	$TAI	= (int)$_POST['tai'] + $tipo['tai'];
	$KEN	= (int)$_POST['ken'] + $tipo['ken'];
	$NIN	= (int)$_POST['nin'] + $tipo['nin'];
	$GEN	= (int)$_POST['gen'] + $tipo['gen'];
	$AGI	= (int)$_POST['agi'] + $tipo['agi'];
	$CON	= (int)$_POST['con'] + $tipo['con'];
	$FOR	= (int)$_POST['for'] + $tipo['forc'];
	$ENE	= (int)$_POST['ene'] + $tipo['ene'] + ($_POST['level'] * 3);
	$INT	= (int)$_POST['int'] + $tipo['inte'];
	$RES	= (int)$_POST['res'] + $tipo['res'];

	$TAI_RAW	= $TAI;
	$KEN_RAW	= $KEN;
	$NIN_RAW	= $NIN;
	$GEN_RAW	= $GEN;
	$AGI_RAW	= $AGI;
	$CON_RAW	= $CON;
	$FOR_RAW	= $FOR;
	$ENE_RAW	= $ENE;
	$INT_RAW	= $INT;
	$RES_RAW	= $RES;

	$TAI_IT	= 0;
	$KEN_IT	= 0;
	$NIN_IT	= 0;
	$GEN_IT	= 0;
	$AGI_IT	= 0;
	$CON_IT	= 0;
	$FOR_IT	= 0;
	$ENE_IT	= 0;
	$INT_IT	= 0;
	$RES_IT	= 0;

	$esq_it			= 0;
	$det_it			= 0;
	$conv_it		= 0;
	$conc_it		= 0;
	$prec_fisico_it	= 0;
	$prec_magico_it	= 0;
		
	
	$BONUS_HP = $BONUS_SP = $BONUS_STA = 0;

	if(isset($_POST['c-inv']) && isset($_POST['item-21']) && $_POST['c-inv'] && $_POST['item-21']) { // INVOCACAO
		$item = Recordset::query('SELECT * FROM item WHERE id=' . (int)$_POST['item-21'], true)->row_array();
		
		$TAI	+= $item['tai'];
		$KEN	+= $item['ken'];
		$NIN	+= $item['nin'];
		$GEN	+= $item['gen'];
		$AGI	+= $item['agi'];
		$CON	+= $item['con'];
		$FOR	+= $item['forc'];
		$ENE	+= $item['ene'];
		$INT	+= $item['inte'];
		$RES	+= $item['res'];

		$TAI_IT	+= $item['tai'];
		$KEN_IT	+= $item['ken'];
		$NIN_IT	+= $item['nin'];
		$GEN_IT	+= $item['gen'];
		$AGI_IT	+= $item['agi'];
		$CON_IT	+= $item['con'];
		$FOR_IT	+= $item['forc'];
		$ENE_IT	+= $item['ene'];
		$INT_IT	+= $item['inte'];
		$RES_IT	+= $item['res'];
		
		$esq_it			+= $item['esq'];
		$det_it			+= $item['det'];
		$conv_it		+= $item['conv'];
		$conc_it		+= $item['conc'];
		$prec_fisico_it	+= $item['prec_fisico'];
		$prec_magico_it	+= $item['prec_magico'];
		
		$BONUS_HP	+= $item['bonus_hp'];
		$BONUS_SP	+= $item['bonus_sp'];
		$BONUS_STA	+= $item['bonus_sta'];
	}
	
	if(isset($_POST['c-sel']) && isset($_POST['item-20']) && $_POST['c-sel'] && $_POST['item-20']) { // SELO
		$item = Recordset::query('SELECT * FROM item WHERE id=' . (int)$_POST['item-20'], true)->row_array();
		
		$TAI	+= $item['tai'];
		$KEN	+= $item['ken'];
		$NIN	+= $item['nin'];
		$GEN	+= $item['gen'];
		$AGI	+= $item['agi'];
		$CON	+= $item['con'];
		$FOR	+= $item['forc'];
		$ENE	+= $item['ene'];
		$INT	+= $item['inte'];
		$RES	+= $item['res'];
		
		$TAI_IT	+= $item['tai'];
		$KEN_IT	+= $item['ken'];
		$NIN_IT	+= $item['nin'];
		$GEN_IT	+= $item['gen'];
		$AGI_IT	+= $item['agi'];
		$CON_IT	+= $item['con'];
		$FOR_IT	+= $item['forc'];
		$ENE_IT	+= $item['ene'];
		$INT_IT	+= $item['inte'];
		$RES_IT	+= $item['res'];
		
		$esq_it			+= $item['esq'];
		$det_it			+= $item['det'];
		$conv_it		+= $item['conv'];
		$conc_it		+= $item['conc'];
		$prec_fisico_it	+= $item['prec_fisico'];
		$prec_magico_it	+= $item['prec_magico'];
		
		$BONUS_HP	+= $item['bonus_hp'];
		$BONUS_SP	+= $item['bonus_sp'];
		$BONUS_STA	+= $item['bonus_sta'];	
	}
	
	if(isset($_POST['c-cla']) && isset($_POST['item-16']) && $_POST['c-cla'] && $_POST['item-16']) { // CLA
		$item = Recordset::query('SELECT * FROM item WHERE id=' . (int)$_POST['item-16'], true)->row_array();
		
		$TAI	+= $item['tai'];
		$KEN	+= $item['ken'];
		$NIN	+= $item['nin'];
		$GEN	+= $item['gen'];
		$AGI	+= $item['agi'];
		$CON	+= $item['con'];
		$FOR	+= $item['forc'];
		$ENE	+= $item['ene'];
		$INT	+= $item['inte'];
		$RES	+= $item['res'];
		
		$TAI_IT	+= $item['tai'];
		$KEN_IT	+= $item['ken'];
		$NIN_IT	+= $item['nin'];
		$GEN_IT	+= $item['gen'];
		$AGI_IT	+= $item['agi'];
		$CON_IT	+= $item['con'];
		$FOR_IT	+= $item['forc'];
		$ENE_IT	+= $item['ene'];
		$INT_IT	+= $item['inte'];
		$RES_IT	+= $item['res'];
		
 		$esq_it			+= $item['esq'];
		$det_it			+= $item['det'];
		$conv_it		+= $item['conv'];
		$conc_it		+= $item['conc'];
		$prec_fisico_it	+= $item['prec_fisico'];
		$prec_magico_it	+= $item['prec_magico'];
		
		$BONUS_HP	+= $item['bonus_hp'];
		$BONUS_SP	+= $item['bonus_sp'];
		$BONUS_STA	+= $item['bonus_sta'];		
	} elseif(isset($_POST['c-por']) && isset($_POST['item-17']) && $_POST['c-por'] && $_POST['item-17']) { // PORTAO
		$item = Recordset::query('SELECT * FROM item WHERE id=' . (int)$_POST['item-17'], true)->row_array();
		
		$TAI	+= $item['tai'];
		$KEN	+= $item['ken'];
		$NIN	+= $item['nin'];
		$GEN	+= $item['gen'];
		$AGI	+= $item['agi'];
		$CON	+= $item['con'];
		$FOR	+= $item['forc'];
		$ENE	+= $item['ene'];
		$INT	+= $item['inte'];
		$RES	+= $item['res'];
		
		$TAI_IT	+= $item['tai'];
		$KEN_IT	+= $item['ken'];
		$NIN_IT	+= $item['nin'];
		$GEN_IT	+= $item['gen'];
		$AGI_IT	+= $item['agi'];
		$CON_IT	+= $item['con'];
		$FOR_IT	+= $item['forc'];
		$ENE_IT	+= $item['ene'];
		$INT_IT	+= $item['inte'];
		$RES_IT	+= $item['res'];
		
		$esq_it			+= $item['esq'];
		$det_it			+= $item['det'];
		$conv_it		+= $item['conv'];
		$conc_it		+= $item['conc'];
		$prec_fisico_it	+= $item['prec_fisico'];
		$prec_magico_it	+= $item['prec_magico'];
		
		$BONUS_HP	+= $item['bonus_hp'];
		$BONUS_SP	+= $item['bonus_sp'];
		$BONUS_STA	+= $item['bonus_sta'];		
	} elseif(isset($_POST['c-sen']) && isset($_POST['item-26']) && $_POST['c-sen'] && $_POST['item-26']) { // SENNIN
		$item = Recordset::query('SELECT * FROM item WHERE id=' . (int)$_POST['item-26'], true)->row_array();
		
		$TAI	+= $item['tai'];
		$KEN	+= $item['ken'];
		$NIN	+= $item['nin'];
		$GEN	+= $item['gen'];
		$AGI	+= $item['agi'];
		$CON	+= $item['con'];
		$FOR	+= $item['forc'];
		$ENE	+= $item['ene'];
		$INT	+= $item['inte'];
		$RES	+= $item['res'];
		
		$TAI_IT	+= $item['tai'];
		$KEN_IT	+= $item['ken'];
		$NIN_IT	+= $item['nin'];
		$GEN_IT	+= $item['gen'];
		$AGI_IT	+= $item['agi'];
		$CON_IT	+= $item['con'];
		$FOR_IT	+= $item['forc'];
		$ENE_IT	+= $item['ene'];
		$INT_IT	+= $item['inte'];
		$RES_IT	+= $item['res'];
		
		$esq_it			+= $item['esq'];
		$det_it			+= $item['det'];
		$conv_it		+= $item['conv'];
		$conc_it		+= $item['conc'];
		$prec_fisico_it	+= $item['prec_fisico'];
		$prec_magico_it	+= $item['prec_magico'];
		
		$BONUS_HP	+= $item['bonus_hp'];
		$BONUS_SP	+= $item['bonus_sp'];
		$BONUS_STA	+= $item['bonus_sta'];	
	}

	switch($_POST['tipo_classe']) {
		case 2:
		case 3: // NIN/GEN
			$sp		= round(($ENE * 10) + ($NIN + $GEN) * 12) + $BONUS_SP;
			$sta	= round(($ENE * 5) + ($TAI * 6)) + $BONUS_STA;
			
			break;

		case 4:
		case 1: // TAI
			$sp		= round(($ENE * 5) + (($NIN + $GEN) * 6)) + $BONUS_SP;
			$sta	= round(($ENE * 10) + (($TAI + $KEN) * 12)) + $BONUS_STA;
		
			break;

		/*case 4: // BAL
			$sp		= round(($ENE * 6) + (($NIN + $GEN) * 8));
			$sta	= round(($ENE * 6) + ($TAI * 8)) + $BONUS_STA;
		
			break;*/
	}
	$hp			= round(($ENE * 8));
	
	$BONUS_HP	= percent($BONUS_HP,  $hp);
	$BONUS_SP	= percent($BONUS_SP,  $sp);
	$BONUS_STA	= percent($BONUS_STA, $sta);

	$esq		= round(($CON + $CON_IT + $AGI + $AGI_IT ) / 8 + ( ($TAI + $TAI_IT) + ($NIN + $NIN_IT) + ($GEN + $GEN_IT) + ($KEN + $KEN_IT)) / 4 , 2) + $esq_it;
	$det		= round(($ENE + $ENE_IT) / 4 , 2) + $det_it;
	$conv		= round(($RES + $RES_IT) / 8 + ( ($TAI + $TAI_IT) + ($NIN + $NIN_IT) + ($GEN + $GEN_IT) + ($KEN + $KEN_IT)) / 4, 2) + $conv_it;
	$conc		= round(( ($FOR + $FOR_IT) + ($INT + $INT_IT) ) / 8 + ( ($TAI + $TAI_IT) + ($NIN + $NIN_IT) + ($GEN + $GEN_IT) + ($KEN + $KEN_IT)) / 4 , 2) + $conc_it;

	$prec_fisico	= ($AGI + $AGI_IT) + $prec_fisico_it;
	$prec_magico	= ($CON + $CON_IT) + $prec_magico_it;
	
	$maxNin		= max($sp, $sta);
	
?>
<br />
<?php msg('4',''.t('calculadora.ca2').'', ''.t('calculadora.ca3').'');?>
<table width="730" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table border="0" cellpadding="4" cellspacing="0" style="width: 350px !important">
        <tr class="cor_sim">
          <td width="124" height="25" align="left" valign="top" ><?php echo t('formula.hp')?></td>
          <td width="105" align="center" valign="top" >
		  <img src="<?php echo img() ?>layout/icones/p_hp.png" width="16" height="16" style="cursor:pointer" id="t-hp"/>
          <?php echo generic_tooltip('t-hp',''.t('calculadora.ca4').'');?>
		 </td>
          <td width="44" align="left" valign="top"  class="amarelo_claro">+ <?php echo $hp + $BONUS_HP ?></td>
          <td width="45" align="left" valign="top" ><?php barra_exp3($hp + $BONUS_HP, $maxNin, 132, $hp + $BONUS_HP, "#2C531D", "#537F3D", 1) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('formula.sp')?></td>
          <td align="center" valign="top" >
		  	<img src="<?php echo img() ?>layout/icones/p_chakra.png" width="16" height="16" style="cursor:pointer" id="t-cha"/>
		   <?php echo generic_tooltip('t-cha',''.t('calculadora.ca5').'');?>
		  </td>
          <td align="left" valign="top"  class="amarelo_claro">+ <?php echo $sp; ?></td>
          <td align="left" valign="top" ><?php barra_exp3($sp, $maxNin, 132, $sp, "#2C531D", "#537F3D", 2) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('formula.sta')?></td>
          <td align="center" valign="top" >
		  	 <img src="<?php echo img() ?>layout/icones/p_stamina.png" width="16" height="16" style="cursor:pointer" id="t-sta" />
			 <?php echo generic_tooltip('t-sta',''.t('calculadora.ca6').'');?>
		  </td>
          <td align="left" valign="top"  class="amarelo_claro">+
            <?php echo $sta; ?>
            </td>
          <td align="left" valign="top" ><?php barra_exp3($sta, $maxNin, 132, $sta, "#2C531D", "#537F3D", 1) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('formula.atk_fisico')?></td>
          <td align="center" valign="top" >
		  	<img src="<?php echo img() ?>layout/icones/atk_fisico.png" width="16" height="16" style="cursor:pointer" id="t-atai"/>
			<?php echo generic_tooltip('t-atai',''.t('formula.desc.atk_fisico').'');?>
			</td>
          <td align="left" valign="top"  class="amarelo_claro">+ <?php echo round( $FOR/2 + $AGI/4) ?></td>
          <td align="left" valign="top" ><?php barra_exp3(round($FOR/2 + $AGI/4), $maxNin, 132, round($FOR/2 + $AGI/4), "#2C531D", "#537F3D", 2) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('formula.atk_magico')?></td>
          <td align="center" valign="top" >
		  <img src="<?php echo img() ?>layout/icones/atk_magico.png" width="16" height="16" style="cursor:pointer" id="t-anin"/>
          <?php echo generic_tooltip('t-anin',''.t('formula.desc.atk_magico').'');?>
		 </td>
          <td align="left" valign="top"  class="amarelo_claro"> + <?php echo round($INT/2 + $CON/4) ?></td>
          <td align="left" valign="top" ><?php barra_exp3(round($INT/2 + $CON/4), $maxNin, 132, round($INT/2 + $CON/4), "#2C531D", "#537F3D",1) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('formula.def_base')?></td>
          <td align="center" valign="top" >
		  <img src="<?php echo img() ?>layout/icones/shield.png" width="16" height="16" style="cursor:pointer" id="t-defb"/> </div>
          <?php echo generic_tooltip('t-defb',''.t('formula.desc.def_base').'');?>
			</td>
          <td align="left" valign="top"  class="amarelo_claro"> + <?php echo round(($RES)/2 + ($AGI + $CON) / 4) ?></td>
          <td align="left" valign="top" ><?php barra_exp3(round(($RES)/2 + ($AGI + $CON) / 4), $maxNin, 132, round(($RES)/2 + ($AGI + $CON) / 4), "#2C531D", "#537F3D",2) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('formula.prec_fisico')?></td>
          <td align="center" valign="top" >
		    <img src="<?php echo img() ?>layout/icones/prec_tai.png" width="16" height="16" style="cursor:pointer" id="t-prect"/>
			<?php echo generic_tooltip('t-prect',''.t('formula.desc.prec_fisico').'');?>
			 </td>
          <td align="left" valign="top"  class="amarelo_claro"> <?php echo $prec_fisico ?> </td>
          <td align="left" valign="top" ><?php barra_exp3($prec_fisico, 5, 132, $prec_fisico, "#2C531D", "#537F3D",1) ?></td>
        </tr>
<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('formula.prec_magico')?></td>
          <td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/prec_nin_gen.png" width="16" height="16" style="cursor:pointer" id="t-precn"/>
            <?php echo generic_tooltip('t-precn',''.t('formula.desc.prec_magico').'');?>
</td>
          <td align="left" valign="top"  class="amarelo_claro"> <?php echo $prec_magico ?> </td>
          <td align="left" valign="top" ><?php barra_exp3($prec_magico, 5, 132, $prec_magico, "#2C531D", "#537F3D",2) ?></td>
        </tr>
<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('formula.esq')?></td>
          <td align="center" valign="top" >
		   <img src="<?php echo img() ?>layout/icones/esquiva.png" width="16" height="16" style="cursor:pointer" id="t-per"/>
		   <?php echo generic_tooltip('t-precn',''.t('formula.desc.esq').'');?>
</td>
          <td align="left" valign="top"  class="amarelo_claro"> <?php echo $esq ?> </td>
          <td align="left" valign="top" ><?php barra_exp3($esq, 5, 132, $esq, "#2C531D", "#537F3D",1) ?></td>
        </tr>
<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('formula.det')?></td>
          <td align="center" valign="top" >
		  	 <img src="<?php echo img() ?>layout/icones/deter.png" width="16" height="16" style="cursor:pointer" id="t-det"/>
             <?php echo generic_tooltip('t-det',''.t('formula.desc.det').'');?>
			 </td>
          <td align="left" valign="top"  class="amarelo_claro"> <?php echo $det ?> </td>
          <td align="left" valign="top" ><?php barra_exp3($det, 5, 132, $det, "#2C531D", "#537F3D",2) ?></td>
        </tr>
<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('formula.conv')?></td>
          <td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/convic.png" width="16" height="16" style="cursor:pointer" id="t-conv"/>
			 <?php echo generic_tooltip('t-conv',''.t('formula.desc.conv').'');?>
			 </td>
          <td align="left" valign="top"  class="amarelo_claro"> <?php echo $conv ?> </td>
          <td align="left" valign="top" ><?php barra_exp3($conv, 5, 132, $conv, "#2C531D", "#537F3D",1) ?></td>
        </tr>
<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('formula.conc')?></td>
          <td align="center" valign="top" >
		  <img src="<?php echo img() ?>layout/icones/target2.png" width="16" height="16" style="cursor:pointer" id="t-conc"/>
		  <?php echo generic_tooltip('t-conc',''.t('formula.desc.conc').'');?>

			</td>
          <td align="left" valign="top"  class="amarelo_claro"> <?php echo $conc ?> </td>
          <td align="left" valign="top" ><?php barra_exp3($conc, 5, 132, $conc, "#2C531D", "#537F3D",2) ?></td>
        </tr>
        
      </table></td>
    <td style="vertical-align:top; width: 350px !important"><table style="width: 350px !important" border="0" cellpadding="4" cellspacing="0">
        <tr class="cor_nao">
          <td width="100" height="25" align="left" valign="top" ><?php echo t('at.nin')?></td>
          <td width="16" align="center" valign="top" >
		  	<img src="<?php echo img() ?>layout/icones/nin.png" width="16" height="16" style="cursor:pointer" id="t-nin"/> 
			<?php echo generic_tooltip('t-nin',''.t('at.desc.nin').'');?>
		  </td>
          <td width="95"  class="amarelo_claro">+ <?php echo $NIN_RAW ?> (+ <?php echo $NIN_IT ?>)</td>
          <td width="130" align="left" valign="top" ><?php barra_exp3($NIN_RAW+$NIN_IT, $NIN_RAW+$NIN_IT, 132, $NIN_RAW+$NIN_IT, "#2C531D", "#537F3D", 1) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
        	<td height="25" align="left" valign="top" ><?php echo t('at.tai')?></td>
        	<td align="center" valign="top" >
			<img src="<?php echo img() ?>layout/icones/tai.png" width="16" height="16" style="cursor:pointer" id="t-tai"/>	 
		    <?php echo generic_tooltip('t-tai',''.t('at.desc.tai').'');?>

			</td>
        	<td  class="amarelo_claro">+ <?php echo $TAI_RAW ?> (+ <?php echo $TAI_IT ?>)</td>
        	<td align="left" valign="top" ><?php barra_exp3($TAI_RAW+$TAI_IT, $TAI_RAW+$TAI_IT, 132, $TAI_RAW+$TAI_IT, "#2C531D", "#537F3D", 2) ?></td>
        </tr>
		<tr height="4"></tr>
		<tr class="cor_nao">
        	<td height="25" align="left" valign="top" ><?php echo t('at.ken')?></td>
        	<td align="center" valign="top" >
			<img src="<?php echo img() ?>layout/icones/ken.png" width="16" height="16" style="cursor:pointer" id="t-tai"/>	 
		    <?php echo generic_tooltip('t-ken',''.t('at.desc.ken').'');?>

			</td>
        	<td  class="amarelo_claro">+ <?php echo $KEN_RAW ?> (+ <?php echo $KEN_IT ?>)</td>
        	<td align="left" valign="top" ><?php barra_exp3($KEN_RAW+$KEN_IT, $KEN_RAW+$KEN_IT, 132, $KEN_RAW+$TAI_IT, "#2C531D", "#537F3D", 2) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
        	<td height="25" align="left" valign="top" ><?php echo t('at.gen')?></td>
        	<td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/gen.png" width="16" height="16" style="cursor:pointer" id="t-gen" />
        		
			<?php echo generic_tooltip('t-gen',''.t('at.desc.gen').'');?>

			</td>
        	<td  class="amarelo_claro">+ <?php echo $GEN_RAW ?> (+ <?php echo $GEN_IT ?>)</td>
        	<td align="left" valign="top" ><?php barra_exp3($GEN_RAW+$GEN_IT, $GEN_RAW+$GEN_IT, 132, $GEN_RAW+$GEN_IT, "#2C531D", "#537F3D", 1) ?></td>
        	</tr>
		<tr height="4"></tr>
        <tr class="cor_nao">
        	<td height="25" align="left" valign="top" ><?php echo t('at.agi')?></td>
        	<td align="center" valign="top" >
			<img src="<?php echo img() ?>layout/icones/agi.png" width="16" height="16" style="cursor:pointer" id="t-agi"/>
		    <?php echo generic_tooltip('t-agi',''.t('at.desc.agi').'');?>
			</td>
        	<td  class="amarelo_claro">+ <?php echo $AGI_RAW ?> (+ <?php echo $AGI_IT ?>)</td>
        	<td align="left" valign="top" ><?php barra_exp3($AGI_RAW+$AGI_IT, $AGI_RAW+$AGI_IT, 132, $AGI_RAW+$AGI_IT, "#2C531D", "#537F3D", 2) ?></td>
        	</tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('at.con')?></td>
          <td align="center" valign="top" >
		  <img src="<?php echo img() ?>layout/icones/conhe.png" width="16" height="16" style="cursor:pointer" id="t-selo"/>
         
		  <?php echo generic_tooltip('t-selo',''.t('at.desc.con').'');?>
		  </td>
          <td  class="amarelo_claro">+ <?php echo $CON_RAW ?> (+ <?php echo $CON_IT ?>)</td>
          <td align="left" valign="top" ><?php barra_exp3($CON_RAW+$CON_IT, $CON_RAW+$CON_IT, 132, $CON_RAW+$CON_IT, "#2C531D", "#537F3D", 1) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('at.for')?></td>
          <td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/forc.png" width="16" height="16" style="cursor:pointer" id="t-for"/>
             
			<?php echo generic_tooltip('t-for',''.t('at.desc.for').'');?>
		</td>
          <td  class="amarelo_claro">+ <?php echo $FOR_RAW ?> (+ <?php echo $FOR_IT ?>)</td>
          <td align="left" valign="top" ><?php barra_exp3($FOR_RAW+$FOR_IT, $FOR_RAW+$FOR_IT, 132, $FOR_RAW+$FOR_IT, "#2C531D", "#537F3D", 2) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('at.ene')?></td>
          <td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/ene.png" width="16" height="16" style="cursor:pointer" id="t-ene"/>
            
			<?php echo generic_tooltip('t-ene',''.t('at.desc.ene').'');?>

			 </td>
          <td  class="amarelo_claro">+ <?php echo $ENE_RAW ?> (+ <?php echo $ENE_IT ?>)</td>
          <td align="left" valign="top" ><?php barra_exp3($ENE_RAW+$ENE_IT, $ENE_RAW+$ENE_IT, 132, $ENE_RAW+$ENE_IT, "#2C531D", "#537F3D", 1) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_nao">
          <td height="25" align="left" valign="top" ><?php echo t('at.int')?></td>
          <td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/inte.png" width="16" height="16" style="cursor:pointer" id="t-int"/>
		  			<?php echo generic_tooltip('t-int',''.t('at.desc.int').'');?>

            </div></td>
          <td  class="amarelo_claro">+ <?php echo $INT_RAW ?> (+ <?php echo $INT_IT ?>)</td>
          <td align="left" valign="top" ><?php barra_exp3($INT_RAW+$INT_IT, $INT_RAW+$INT_IT, 132, $INT_RAW+$INT_IT, "#2C531D", "#537F3D", 2) ?></td>
        </tr>
		<tr height="4"></tr>
        <tr class="cor_sim">
          <td height="25" align="left" valign="top" ><?php echo t('at.res')?></td>
          <td align="center" valign="top" > <img src="<?php echo img() ?>layout/icones/defense.png" width="16" height="16" style="cursor:pointer" id="t-res"/>
          <?php echo generic_tooltip('t-res',''.t('at.desc.res').'');?>
		</td>
          <td  class="amarelo_claro">+ <?php echo $RES_RAW ?> (+ <?php echo $RES_IT ?>)</td>
          <td align="left" valign="top" ><?php barra_exp3($RES_RAW+$RES_IT, $RES_RAW+$RES_IT, 132, $RES_RAW+$RES_IT, "#2C531D", "#537F3D",1) ?></td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
<?php endif; ?>
<?php msg('1',''.t('calculadora.ca7').'', ''.t('calculadora.ca8').'');?>
<br />
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Formulas -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="9880426177"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

<div style="clear:both"></div>
<br />
  <table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="30" align="center">&nbsp;</td>
          <td width="250" align="left"><b style="color:#FFFFFF"><?php echo t('calculadora.ca9')?></b></td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
<form method="post">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="25%"><?php echo t('calculadora.ca10')?></td>
      <td width="25%"><select name="tipo_classe" id="s-tipo">
          <?php
				$tipos = Recordset::query('SELECT * FROM classe_tipo', true);
			?>
          <?php foreach($tipos->result_array() as $tipo): ?>
          <option <?php echo $tipo['id'] == (isset($_POST['tipo_classe']) ? $_POST['tipo_classe'] : 0) ? 'selected="selected"' : '' ?> value="<?php echo $tipo['id'] ?>"><?php echo $tipo['nome'] ?></option>
          <?php endforeach; ?>
        </select></td>
      <td width="25%"><?php echo t('calculadora.ca11')?></td>
      <td width="25%"><input type="text" name="level" id="t-level" value="<?php echo isset($_POST['level']) && $_POST['level'] ? $_POST['level'] : 1 ?>" /></td>
    </tr>
  </table>
  <br />
  <script type="text/javascript">
	var _tipos = [];
	
	<?php foreach($tipos->result_array() as $tipo): ?>
	<?php
		$tmp = array();
		
		foreach($tipo as $k => $v) {
			$tmp[] = $k . ': \'' . $v . '\'';
		}
	?>
	_tipos[<?php echo $tipo['id'] ?>] = {<?php echo join(',', $tmp) ?>};
	<?php endforeach; ?>
</script>
  <br />
  <table style="width: 740px !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td style="width: 350px !important" valign="top"><table style="width: 350px !important" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="4" align="left"><div class="titulo-home"><p><span class="laranja">//</span><?php echo t('calculadora.ca12')?>  ..........</p></div></td>
          </tr>
          <tr class="cor_sim">
          	<td width="27%" align="left" >&nbsp;<?php echo t('at.nin')?></td>
          	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/nin.png" width="16" height="16" style="cursor:pointer" /></span></td>
          	<td width="23%" align="left"  class="amarelo_claro"><span id="c-nin"></span>+ </td>
          	<td width="38%" align="center" ><input type="text" class="spinner" name="nin" value="<?php echo isset($_POST['nin']) && $_POST['nin'] ? $_POST['nin'] : 0 ?>" /></td>
          	</tr>
			<tr height="4"></tr>
		  <tr class="cor_nao">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.tai')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/tai.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-tai"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="tai" value="<?php echo isset($_POST['tai']) && $_POST['tai'] ? $_POST['tai'] : 0 ?>" /></td>
	  	</tr>
		<tr height="4"></tr>
		<tr class="cor_sim">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.ken')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/ken.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-ken"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="ken" value="<?php echo isset($_POST['ken']) && $_POST['ken'] ? $_POST['ken'] : 0 ?>" /></td>
	  	</tr>
		<tr height="4"></tr>
		  <tr class="cor_nao">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.gen')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/gen.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-gen"></span> + </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="gen" value="<?php echo isset($_POST['gen']) && $_POST['gen'] ? $_POST['gen'] : 0 ?>" /></td>
	  	</tr>
		  <tr class="cor_sim">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.agi')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/agi.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-agi"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="agi" value="<?php echo isset($_POST['agi']) && $_POST['agi'] ? $_POST['agi'] : 0 ?>" /></td>
	  	</tr>
		<tr height="4"></tr>
		  <tr class="cor_nao">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.con')?></td>
		  	<td width="12%" align="center" ><img src="<?php echo img() ?>layout/icones/conhe.png" width="16" height="16" style="cursor:pointer" /></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-con"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="con" value="<?php echo isset($_POST['con']) && $_POST['con'] ? $_POST['con'] : 0 ?>" /></td>
	  	</tr>
		<tr height="4"></tr>
		  <tr class="cor_sim">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.for')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/forc.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-for"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="for" value="<?php echo isset($_POST['for']) && $_POST['for'] ? $_POST['for'] : 0 ?>" /></td>
	  	</tr>
		  <tr class="cor_nao">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.ene')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/ene.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-ene"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="ene" value="<?php echo isset($_POST['ene']) && $_POST['ene'] ? $_POST['ene'] : 0 ?>" /></td>
	  	</tr>
		<tr height="4"></tr>
		  <tr class="cor_sim">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.int')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/inte.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-int"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="int" value="<?php echo isset($_POST['int']) && $_POST['int'] ? $_POST['int'] : 0 ?>" /></td>
	  	</tr>
		<tr height="4"></tr>
		  <tr class="cor_nao">
		  	<td width="27%" align="left" >&nbsp;<?php echo t('at.res')?></td>
		  	<td width="12%" align="center" ><span class="trigger"><img src="<?php echo img() ?>layout/icones/defense.png" width="16" height="16" style="cursor:pointer" /></span></td>
		  	<td width="23%" align="left"  class="amarelo_claro"><span id="c-res"></span>+ </td>
		  	<td width="38%" align="center" ><input type="text" class="spinner" name="res" value="<?php echo isset($_POST['res']) && $_POST['res'] ? $_POST['res'] : 0 ?>" /></td>
	  	</tr>
        </table>
        <br />
	  </td>
      <td style="width: 350px !important" valign="top"><table style="width: 350px !important" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td colspan="5" align="center"><div class="titulo-home"><p><span class="verde">//</span> <?php echo t('calculadora.ca13')?> .....</p></div></td>
          </tr>
          <tr>
            <td width="20%" align="center"><input id="c-cla" name="c-cla" <?php echo isset($_POST['c-cla']) && $_POST['c-cla'] ? 'checked="checked"' : '' ?> class="habil" type="checkbox" />
              <a rel="16" class="habil-link" href="javascript:;"><br />
              <?php echo t('calculadora.ca14')?></a></td>
            <td width="20%" align="center"><input id="c-inv" name="c-inv" <?php echo isset($_POST['c-inv']) && $_POST['c-inv'] ? 'checked="checked"' : '' ?> class="habil" type="checkbox" />
              <br />
              <a rel="21" class="habil-link" href="javascript:;"><?php echo t('calculadora.ca15')?></a></td>
            <td width="20%" align="center"><input id="c-sel" name="c-sel" <?php echo isset($_POST['c-sel']) && $_POST['c-sel'] ? 'checked="checked"' : '' ?> class="habil" type="checkbox" />
              <br />
              <a rel="20" class="habil-link" href="javascript:;"><?php echo t('calculadora.ca16')?></a></td>
            <td width="20%" align="center"><input id="c-sen" name="c-sen" <?php echo isset($_POST['c-sen']) && $_POST['c-sen'] ? 'checked="checked"' : '' ?> class="habil" type="checkbox" />
              <br />
              <a rel="26" class="habil-link" href="javascript:;"><?php echo t('calculadora.ca17')?></a></td>
            <td width="20%" align="center"><input id="c-por" name="c-por" <?php echo isset($_POST['c-por']) && $_POST['c-por'] ? 'checked="checked"' : '' ?> class="habil" type="checkbox" />
              <br />
              <a rel="17" class="habil-link" href="javascript:;"><?php echo t('calculadora.ca18')?></a></td>
          </tr>
          <tr>
            <td colspan="5" align="center">
            <?php
				$tipos			= array(16, 17, 20, 21, 26);
				$tipos_filtro	= array(
					'16'			=> array('id_cla', 'cla'),
					'20'			=> array('id_selo', 'selo'),
					'21'			=> array('id_invocacao', 'invocacao')
				);
			?>
			<?php foreach($tipos as $tipo): ?>
			<div class="habil-block habil-block-<?php echo $tipo ?>" style="display: none">
				<?php if(isset($tipos_filtro[$tipo])): ?>
				<p> <?php echo t('calculadora.ca19')?>:
					<select class="filtro" id="s-filtro-<?php echo $tipo ?>" role="<?php echo $tipos_filtro[$tipo][0] ?>">
					<?php
						$filtros = Recordset::query('SELECT * FROM ' . $tipos_filtro[$tipo][1], true);
					?>
					<?php foreach($filtros->result_array() as $filtro): ?>
						<option value="<?php echo $tipos_filtro[$tipo][0] . '-' . $filtro['id'] ?>"><?php echo isset($filtro['nome']) ? $filtro['nome'] : $filtro['nome_' . Locale::get()] ?></option>
					<?php endforeach; ?>
					</select>
				</p>
	            <?php else: ?>
				<?php
					$filtro_tipo = NULL;
				?>
				<?php endif; ?>
                <table border="0" style="width: 350px !important" cellpadding="3" cellspacing="0">
					<?php
						$c		= 0;
						$items	= Recordset::query('SELECT * FROM item WHERE id_tipo=' . $tipo, true);
					?>
					<?php foreach($items->result_array() as $item):	?>
					<?php
						$bg = ++$c % 2 ? "cor_sim" : "cor_nao";
					?>
					<tr class="item-filtro-<?php echo isset($tipos_filtro[$tipo]) ? $tipos_filtro[$tipo][0] : '' ?> <?php echo isset($tipos_filtro[$tipo]) && $tipos_filtro[$tipo] ? 'item-filtro-' . $tipos_filtro[$tipo][0] . '-' . $item[$tipos_filtro[$tipo][0]] : '' ?>">
						<td rowspan="2" align="center">
							<img src="<?php echo img("layout/".$item['imagem']) ?>" width="48" height="48" />
						</td>
						<td>
							<input type="radio" name="item-<?php echo $tipo ?>" class="item item-<?php echo $tipo ?>" value="<?php echo $item['id'] ?>" <?php echo (isset($_POST['item-' . $tipo]) ? $_POST['item-' . $tipo] : 0) == $item['id'] ? 'checked="checked"' : '' ?> />
							<b style="font-size:13px;" class="amarelo"><?php echo $item['nome_'.Locale::get()] ?></b>
						</td>
					</tr>
					<tr class="<?php echo $bg ?> item-filtro-<?php echo isset($tipos_filtro[$tipo]) ? $tipos_filtro[$tipo][0] : '' ?> <?php echo isset($tipos_filtro[$tipo]) && $tipos_filtro[$tipo] ? 'item-filtro-' . $tipos_filtro[$tipo][0] . '-' . $item[$tipos_filtro[$tipo][0]] : '' ?>">
						<td class="item-filtro-at">
							<?php if($item['nin']): ?>
							<p>
							<b>+ <?php echo $item['nin'] ?></b> <img src="<?php echo img('layout/icones/nin.png') ?>" /><br /><?php echo t('at.nin')?>
							</p>
							<?php endif; ?>
							<?php if($item['tai']): ?>
							<p>
							<b>+ <?php echo $item['tai'] ?></b> <img src="<?php echo img('layout/icones/tai.png') ?>" /><br /><?php echo t('at.tai')?>
							</p>
							<?php endif; ?>
							<?php if($item['ken']): ?>
							<p>
							<b>+ <?php echo $item['ken'] ?></b> <img src="<?php echo img('layout/icones/ken.png') ?>" /><br /><?php echo t('at.ken')?>
							</p>
							<?php endif; ?>
							<?php if($item['gen']): ?>
							<p>
							<b>+ <?php echo $item['gen'] ?></b> <img src="<?php echo img('layout/icones/gen.png') ?>" /><br /><?php echo t('at.gen')?>
							</p>
							<?php endif; ?>
							<?php if($item['ene']): ?>
							<p>
							<b>+ <?php echo $item['ene'] ?></b> <img src="<?php echo img('layout/icones/ene.png') ?>" /><br /><?php echo t('at.ene')?>
							</p>
							<?php endif; ?>
							<?php if($item['forc']): ?>
							<p>
							<b>+ <?php echo $item['forc'] ?></b> <img src="<?php echo img('layout/icones/forc.png') ?>" /><br /><?php echo t('at.for')?> 
							</p>
							<?php endif; ?>
							<?php if($item['inte']): ?>
							<p>
							<b>+ <?php echo $item['inte'] ?></b> <img src="<?php echo img('layout/icones/inte.png') ?>" /><br /><?php echo t('at.int')?>
							</p>
							<?php endif; ?>
							<?php if($item['con']): ?>
							<p>
							<b>+ <?php echo $item['con'] ?></b> <img src="<?php echo img('layout/icones/conhe.png') ?>" /><br /><?php echo t('at.con')?>
							</p>
							<?php endif; ?>
							<?php if($item['agi']): ?>
							<p>
							<b>+ <?php echo $item['agi'] ?></b> <img src="<?php echo img('layout/icones/agi.png') ?>" /><br /><?php echo t('at.agi')?>
							</p>
							<?php endif; ?>
							<?php if($item['res']): ?>
							<p>
							<b>+ <?php echo $item['res'] ?></b> <img src="<?php echo img('layout/icones/defense.png') ?>" /><br /><?php echo t('at.res')?>
							</p>
							<?php endif; ?>
				
							<?php if((float)$item['det']): ?>
							<p>
							<b>+ <?php echo $item['det'] ?>%</b> <img src="<?php echo img('layout/icones/deter.png') ?>" /><br /><?php echo t('formula.det')?>
							</p>
							<?php endif; ?>
							<?php if((float)$item['conc']): ?>
							<p>
							<b>+ <?php echo $item['conc'] ?>%</b> <img src="<?php echo img('layout/icones/target2.png') ?>" /><br /><?php echo t('formula.conc')?>
							</p>
							<?php endif; ?>
							<?php if((float)$item['conv']): ?>
							<p>
							<b>+ <?php echo $item['conv'] ?>%</b> <img src="<?php echo img('layout/icones/convic.png') ?>" /><br /><?php echo t('formula.conv')?>
							</p>
							<?php endif; ?>
							<?php if((float)$item['esq']): ?>
							<p>
							<b>+ <?php echo $item['esq'] ?>%</b> <img src="<?php echo img('layout/icones/esquiva.png') ?>" /><br /><?php echo t('formula.esq')?> 
							</p>
							<?php endif; ?>
							<?php if($item['atk_fisico']): ?>
							<p>
							<b>+ <?php echo $item['atk_fisico'] ?></b> <img src="<?php echo img('layout/icones/atk_fisico.png') ?>" /><br /><?php echo t('formula.atk_fisico')?>
							</p>
							<?php endif; ?>
							<?php if($item['atk_magico']): ?>
							<p>
							<b>+ <?php echo $item['atk_magico'] ?></b> <img src="<?php echo img('layout/icones/atk_magico.png') ?>" /><br /><?php echo t('formula.atk_magico')?>
							</p>
							<?php endif; ?>
				
							<?php if((float)$item['prec_fisico']): ?>
							<p>
							<b>+ <?php echo $item['prec_fisico'] ?></b> <img src="<?php echo img('layout/icones/prec_tai.png') ?>" /><br /><?php echo t('formula.prec_fisico')?>
							</p>
							<?php endif; ?>
							<?php if((float)$item['prec_magico']): ?>
							<p>
							<b>+ <?php echo $item['prec_magico'] ?></b> <img src="<?php echo img('layout/icones/prec_nin_gen.png') ?>" /><br /><?php echo t('formula.prec_magico')?>
							</p>
							<?php endif; ?>
							<?php if((float)$item['crit_min']): ?>
							<p>
							<b>+ <?php echo $item['crit_min'] ?></b> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.crit_min')?>
							</p>
							<?php endif; ?>
							<?php if((float)$item['crit_max']): ?>
							<p>
							<b>+ <?php echo $item['crit_max'] ?></b> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.crit_max')?>
							</p>
							<?php endif; ?>
				
							<?php if($item['bonus_hp']): ?>
							<p>
							<b>+ <?php echo $item['bonus_hp'] ?>%</b> <img src="<?php echo img('layout/icones/p_hp.png') ?>" /><br /><?php echo t('formula.hp')?>
							</p>
							<?php endif; ?>
							<?php if($item['bonus_sp']): ?>
							<p>
							<b>+ <?php echo $item['bonus_sp'] ?>%</b> <img src="<?php echo img('layout/icones/p_chakra.png') ?>" /><br /><?php echo t('formula.sp')?>
							</p>
							<?php endif; ?>
							<?php if($item['bonus_sta']): ?>
							<p>
							<b>+ <?php echo $item['bonus_sta'] ?>%</b> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.sta')?>
							</p>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<?php endforeach; ?>
			<script type="text/javascript">
				$(document).ready(function () {
			<?php foreach($tipos as $tipo): ?>
				<?php if(isset($tipos_filtro[$tipo])): ?>
				$('#s-filtro-<?php echo $tipo ?>').trigger('change');
				<?php endif; ?>
			<?php endforeach; ?>
				});
			</script>
			<div class="habil-block"><?php echo t('calculadora.ca20')?></div>
			<div style="clear: both"></div></td>
          </tr>
        </table>
        <br /></td>
    </tr>
  </table>
  <br />
  <div style="clear: both"></div>
  <a class="button" data-trigger-form="1"><?php echo t('botoes.ver_resultados')?></a>
</form>