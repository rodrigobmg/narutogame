<?php
	$redir_script = true;

	if(!is_numeric($_GET['id'])) {
		redirect_to("negado");
	}
	
	if($_SESSION['universal'] && isset($_GET['block']) && $_GET['block']) {
		add_player_bloqueio($_GET['usuario'], $_GET['player']);
	}
?>
<div class="titulo-secao"><p><?php echo t('ler_noticia.l1')?></p></div><br />
<!-- Comentario adicionado -->
<?php if(isset($_GET['ok']) && $_GET['ok']) { ?>

<?php msg('6',''.t('ler_noticia.l2').'', ''.t('ler_noticia.l3').'');?>
<?php } ?>
<!-- fim do comentario adicionado -->
<!-- Noticia postada -->
<?php
	$qNew = Recordset::query("SELECT a.*,b.name AS nome_usuario FROM noticia a JOIN global.user b ON b.id=a.id_usuario AND a.id=" . addslashes($_GET['id']) . " ORDER BY id DESC");
	while($r = $qNew->row_array()) {
?>
<div id="HOTWordsTxt" name="HOTWordsTxt">
  <table width="730" border="0" cellpadding="0" cellspacing="0" style="clear:left;">
    <tr>
      <td height="49" class="subtitulo-home" style="color:#FFFFFF">
     
  			  <b style="font-size:16px;">
              <?php echo $r['titulo_'. Locale::get()];?>
              </b> - <?php echo t('ler_noticia.l4')?> 
              <span class="laranja"><?=  $r['nome_usuario'];?></span>
               <?php echo t('ler_noticia.l5')?>
              <?php echo  date("d/m/Y", strtotime($r['data_ins'])) . " &agrave;s " . date("H:i:s", strtotime($r['data_ins'])); ?>

        
        </td>
    </tr>
  </table>
  <table width="730" border="0" cellpadding="4" cellspacing="0" id="newsLink">
    <tr>
      <td align="left">
	  	<?php if($r['id']==218){?>
	  		<?php echo $r['conteudo_'. Locale::get()];?>
		<?php }else{?>
			<?php echo nl2br($r['conteudo_'. Locale::get()]);?>
		<?php }?>	
		<hr style="color:#2e2d2d; border:1px solid <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>; clear: both" />
		<div style="clear: both"></div>
		<div style="float:left">
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode('http://narutogame.com.br/?secao=ler_noticia&id=' . $r['id']) ?>&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=trebuchet+ms&amp;colorscheme=dark&amp;height=65" scrolling="no" frameborder="0" style="border:none; color:#FFF; overflow:hidden; width:450px; height:65px;" allowTransparency="true"></iframe> 
		</div>
	  </td>
    </tr>
  </table>
</div>
<br />
<br />
<?php } ?>
<!-- fim da noticia postada -->

<?php if($basePlayer && get_player_bloqueio($basePlayer->id_usuario)): ?>
	<div style="font-size:14px; font-weight:bold; background:#1c1c1c; border:1px solid #151515; padding:10px"><?php echo t('ler_noticia.l6')?></div>
<?php endif; ?>

<!-- Adicionar comentarios -->
<?php /* if($basePlayer && ($basePlayer->vip || $basePlayer->level > 14 && !get_player_bloqueio($basePlayer->id))) { */?>
<table width="730" border="0" cellpadding="0" cellspacing="0" >
<tr>
  <td height="49" class="subtitulo-home" style="color:#FFFFFF" align="left">
         <b style="color:#FFFFFF; font-size:16px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo t('ler_noticia.l7')?></b>    
    </td>
</tr>
      <tr>
        <td height="34">
        	<form method="post" action="?acao=ler_noticia_comentar">
            	<input type="hidden" name="n" value="<?php echo addslashes($_GET['id']) ?>" />
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
        		<tr>
                    <td><textarea style="width: 98%" rows="7" name="conteudo"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><br /><a class="button"  data-trigger-form="1"><?php echo t('ler_noticia.l7')?></a></td>
                </tr>
                </table>
            </form>
        </td>
      </tr>
    </table>
<?php /* } */ ?>
<br /><br />
<!-- Comentarios aqui -->
<?php
	$cn				= 0;
	$qComentario	= Recordset::query("SELECT a.*, b.name AS nome_usuario FROM noticia_comentario a JOIN global.user b ON b.id=a.id_usuario 
							   WHERE a.id_noticia=". addslashes($_GET['id'])." ORDER BY a.id asc");
	while($r = $qComentario->row_array()) {
	$cor = ++$cn % 2 ? "cor_sim" : "cor_nao";
?>

<div id="HOTWordsTxt" name="HOTWordsTxt">	
   <table width="730" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td height="49" class="subtitulo-home" style="color:#FFFFFF" align="left">
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b><?php echo t('ler_noticia.l8')?></b> - <i><?php echo t('ler_noticia.l4')?></i> <strong> <?= $r['nome_usuario'];?></strong> em <?= date("d/m/Y", strtotime($r['data_ins'])) . " &agrave;s " . date("H:i:s", strtotime($r['data_ins']));?>
			<?php if($_SESSION['universal']){?>
					<div style="float:right; margin-right:20px"><a href="?secao=ler_noticia&id=<?php echo $_GET['id'] ?>&block=1&usuario=<?php echo $r['id_usuario'] ?>&player=<?php echo $r['id_player'] ?>"><img border="0" src="<?php echo img()?>layout/block.png" alt="Bloquear" style="cursor:pointer" /></a></div>
			<?php }?>
			
			
        </td>
    </tr>
      <tr class="<?php echo $cor;?>">
        <td height="34" align="left">
        	<?php 
				if($r['id_usuario']==2 || $r['id_usuario']==4 || $r['id_usuario']==1 || $r['id_usuario']==3 || $r['id_usuario']==6 || $r['id_usuario']==11){
					$cor="color:#10b9f0;";
				}else{
					$cor="color:#FFFFFF;";
				}
			?>
            <p style="padding:10px; <?php echo $cor?>"><?= nl2br(str_replace("<", "&lt;", html_entity_decode($r['conteudo'], ENT_COMPAT))); ?></p>
        </td>
      </tr>
    </table>
    <br />
    <br />
</div>    

<?php } ?>
<?php 
	$previous	= new Recordset('SELECT id FROM noticia WHERE id < ' . (int)$_GET['id'] . ' ORDER BY id DESC LIMIT 1');
	$next		= new Recordset('SELECT id FROM noticia WHERE id > ' . (int)$_GET['id'] . ' ORDER BY id ASC LIMIT 1');
?>

<?php if($previous->num_rows): ?>
	<a class="button" style="float: left" href="?secao=ler_noticia&id=<?php echo $previous->row()->id ?>"><?php echo t('botoes.anterior')?></a>
<?php endif; ?>


<?php if($next->num_rows): ?>
	<a class="button" style="float: right" href="?secao=ler_noticia&id=<?php echo $next->row()->id ?>"><?php echo t('botoes.proximo')?></a>

<?php endif; ?>

<!-- fim dos comentarios -->
