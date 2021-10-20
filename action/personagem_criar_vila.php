<?php
	$_POST['vila'] = decode($_POST['vila']);

	if(!is_numeric($_POST['vila'])) {
		redirect_to("negado", NULL, array('e' => 'vila'));
	}
?>
<script type="text/javascript">
	var _arCL = [];
</script>
<div align="right">
  <img style="cursor:pointer" name='btEscolher' id='btEscolher' src='<?php echo img(); ?>bt_trocar_vila.gif' onclick="doTrocaVila()"/>
</div>
<hr />
<style>
	.imgPers {
		float: left;
	}
	
	.char-l {
		position:relative;
		float: left;
		width: 145px;
		height: 118px;
		margin-right: -43px;	
	}
	
	.char-m {
		position:relative;
		float: left;
		width: 145px;
		height: 118px;
		top: 59px;	
	}
	
	.char-r {
		position:relative;
		float: left;
		width: 145px;
		height: 118px;
		margin-left: -43px;	
	}
	hr{
		color: #2d2d2d;
		border: 1px dotted #2d2d2d;	
	}
</style>
<form name="fPersonagem" id="fPersonagem" onsubmit="return false;">
  <input type="hidden" name="id_classe" />
  <input type="hidden" name="id_vila" value="<?php echo encode($_POST['vila']) ?>" />
   <table border="0" cellpadding="2" width="100%">
          <tr>
            <td colspan="3"><img src="<?php echo img(); ?>bg_sel_per2.jpg" alt="Selecione um personagem - Naruto Game"/></td>
          </tr>
       </table>
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td valign="top" style="padding: 0px !important; width: 360px;">
     
      <div style="height: 530px; position: relative">
          <?php
	$break = 3;

	$qClasses = new Recordset("
		SELECT 
			a.id,
			a.nome,
			a.imagem, 
			a.especial,
			(SELECT nome_".Locale::get()." FROM vila WHERE id=a.id_vila) AS vila
		
		FROM 
			classe a
			
			
		WHERE a.ativo = 1 ORDER BY ordem ", true);
	
	$qClasseTipo = new Recordset('SELECT * FROM classe_tipo', true);
	
	$arAt	= array();
	$cn		= 1;
	$cnb	= 0;
	
	foreach($qClasses->result_array() as $r) {		
		switch($cn) {
			case 1:	$class = 'char-l'; break;
			case 2:	$class = 'char-m'; break;
			case 3:	$class = 'char-r'; break;
		}
		
		if(++$cn > 3) {
			$cn = 1;
		}
		
		$r['especial'] = 0;
		$id = $r['id'];
?>
<div style="position: relative" class="<?php echo $class ?> chars chars-<?php echo floor($cnb++ / 12) ?>">
	<img class="imgPers" id="c<?php echo $id ?>" onerror="this.src='<?php echo img('personagens/pers_unknown.gif'); ?>'" src="<?php echo $r['imagem'] ? img('criacao/pequenas/' . $r['id'] . '.png') : img('personagens/pers_unknown.gif') ?>" />
	<?php if($r['especial']): ?>
	<img src="<?php echo img('lock.png') ?>" id="l<?php echo $id ?>" class="cLocks" style="position: absolute; left: 6px" onclick="doSelecionaClasse($('#c<?php echo $r['id'] ?>'), '<?php echo $id ?>')" />
	<?php endif; ?>
</div>
<?php
		$arAt[$id] = array('nome' => $r['nome'], 'vila' => $r['vila'], 'imagem' => $r['imagem'], 'especial' => $r['especial']);
		
		foreach($qClasseTipo->result_array() as $classe_tipo) {
			$p = new Player(0, true, $classe_tipo['id']);
			$p->atCalc();
			
			$at = array(
				'TAI_'  . $classe_tipo['id'] => $p->getAttribute('tai_calc'),
				'KEN_'  . $classe_tipo['id'] => $p->getAttribute('ken_calc'),
				'NIN_'  . $classe_tipo['id'] => $p->getAttribute('nin_calc'),
				'GEN_'  . $classe_tipo['id'] => $p->getAttribute('gen_calc'),
				'ENE_'  . $classe_tipo['id'] => $p->getAttribute('ene_calc'),
				'RES_'  . $classe_tipo['id'] => $p->getAttribute('res_calc'),
				'CON_'  . $classe_tipo['id'] => $p->getAttribute('con_calc'),
				'AGI_'  . $classe_tipo['id'] => $p->getAttribute('agi_calc'),
				'FORC_' . $classe_tipo['id'] => $p->getAttribute('for_calc'),
				'INTE_' . $classe_tipo['id'] => $p->getAttribute('int_calc'),
				'HP_'   . $classe_tipo['id'] => $p->getAttribute('hp'),
				'SP_'   . $classe_tipo['id'] => $p->getAttribute('sp'),
				'STA_'  . $classe_tipo['id'] => $p->getAttribute('sta')
			);
			
			$arAt[$id] = array_merge($arAt[$id], $at);
		}
	}
?>
	<div style="position: absolute; bottom: 0px; width: 100%">
		<div style="float: left">
			<input type="image" src="<?php echo img('criacao/seta_anterior.jpg') ?>" id="b-char-p" value="Anterior" />
		</div>
		<div style="float: right">
			<input type="image" src="<?php echo img('criacao/seta_proximo.jpg') ?>" id="b-char-n" value="Próximo" />
		</div>
		<div style="clear: both"></div>
	</div>
	  </div>        
     </td>
      <td valign="top" style="padding: 0px !important">
		<img src="<?php echo img() ?>personagens/pers_unknown.gif" id="imgP" />
  		<table>
			<tr>
				<td colspan="2"><img src="<?php echo img(); ?>bg_historia.jpg" alt="Historia - Naruto Game"/></td>
			</tr>
			<tr>
				<td colspan="2" id="cnHistP" style="font-weight:none; text-align:justify; width:300px">-</td>
			</tr>
        </table>     
     </td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="350" valign="top">

		<table width="100%" border="0" cellpadding="3">
			<tr>
				<td colspan="2" valign="top"><img src="<?php echo img(); ?>bg_atributos.jpg" alt="Atributos - Naruto Game"/></td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtHP'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtSP'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtSTA'") ?>
				</td>
			</tr>
			<tr>
				<td><hr /></td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtAgi'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtCon'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtFor'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtEne'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtInt'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtTai'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtKen'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtNin'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtGen'") ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php barra_exp(0, 0, 350, "", "#2C531D", "#537F3D", 3, "id='cnAtRes'") ?>
				</td>
			</tr>
		</table>

      </td>
      <td valign="top">
      	<table>
		  <tr>
            <td colspan="2"><img src="<?php echo img(); ?>bg_crie_personagem.jpg" alt="Crie seu personagem - Naruto Game"/></td>
          </tr>
		  
          <tr>
            <td><div align="right">Nome:</div></td>
            <td align="left"><input type="text" name="nome" id="nome" size="30"  style="width:150px" maxlength="14" /></td>
          </tr>
		   <tr>
          	<td align="right">Tipo do Personagem</td>
          	<td align="left">
				<select name="classe_tipo" id="classe_tipo" onchange="doSelecionaClasse(_ps_o, _ps_id)" style="width:155px">
					<?php foreach($qClasseTipo->result_array()	 as $classe_tipo): ?>
					<option value="<?php echo $classe_tipo['id'] ?>"><?php echo $classe_tipo['nome'] ?></option>
					<?php endforeach; ?>
				</select>          	
          	</td>
          </tr>
         <!-- <tr>
            <td><div align="right">Vila Oculta:</div></td>
            <td align="left" id="cnVilaP">-</td>
          </tr>
		  
          <tr>
            <td><div align="right">Personagem:</div></td>
            <td align="left" id="cnNomeP">-</td>
          </tr>
		  -->
         
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center"><img src="<?php echo img(); ?>bt_criar.gif" style="cursor:pointer" onclick="doCriarPersonagem()" />            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<script type="text/javascript">
	var _use_big	= true;
	var _char_c		= 0;
	
	<?php foreach($arAt as $k => $v): ?>
	_arCL['<?php echo $k ?>'] = [];
	<?php
		$mx = array();
		foreach($v as $kk => $vv):
			if(is_numeric($vv) && !on($kk, 'HP_1,HP_2,HP_3,HP_4,SP_1,SP_2,SP_3,SP_4,STA_1,STA_2,STA_3,STA_4')) {
				$mx[] = $vv;
			}
	?>
	_arCL['<?php echo $k ?>']['<?php echo $kk ?>']='<?php echo addslashes($vv)  ?>';
	<?php endforeach; ?>
	_arCL['<?php echo $k ?>']['mx'] = <?php echo (int)max($mx) ?>;
	<?php endforeach; ?>

	$(document).ready(function () {
		$('.imgPers').bind('click', function () {
			var id = $(this).attr('id');
		
			doSelecionaClasse(this, id.replace(/[^\d]+/, ''));
		}).animate({opacity: .2}, 100);
		
		$('#b-char-p').bind('click', function () {
			if(_char_c > 0) {
				_char_c--;
			}		
			
			$('.chars').hide();
			$('.chars-' + _char_c).show();			
		}).trigger('click');

		$('#b-char-n').bind('click', function () {
			if(!$('.chars-' + (_char_c + 1)).length) {
				return;
			}
			
			_char_c++;
			
			$('.chars').hide();
			$('.chars-' + _char_c).show();
		});
		
		$($('.imgPers')[0]).trigger('click');
	});
</script>