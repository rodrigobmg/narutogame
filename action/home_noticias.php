<?php
	if(!headers_sent()) {
		header("Content-Type: text/html; charset=utf-8");
	}

	$cn = 0;
	$imn = 0;
?>
<div id="noticias">
<div class="areaTitulos1"><p>Notícias</p></div>
<?php
 	$start = !isset($_POST['start']) || (isset($_POST['start']) && $_POST['start'] <= 0) ? 0 : $_POST['start'];

	$qNews = new Recordset("
            SELECT
                   a.*
            FROM noticia a WHERE a.round = 'r30'
            ORDER BY a.id DESC
            LIMIT " . ($start * 5) . ", 4", true);

	$next = $start + 1;
	$back = $start - 1;

	if($back < 0) {
        $back = 0;
    }

    foreach($qNews->result_array() as $k=> $r) {
        $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";
        $img = ++$imn % 2 ? "layout".LAYOUT_TEMPLATE."/home/detalhe.jpg" : "layout".LAYOUT_TEMPLATE."/home/detalhe1.jpg";
?>
    <?php if ($k==0): ?>
        <div id="primeira-noticia" class="<?php echo $cor; ?>" style="background-image:url(<?php echo img();?><?php echo $img; ?>);">
            <div class="data"><?php echo date("d/M", strtotime($r['data_ins'])); ?></div>
            <div class="imagem"><img src="<?php echo img();?>layout/home/noticia-trofeu.png" /></div>
            <div class="previa">
                <p><a href="?secao=ler_noticia&id=<?php echo $r['id'];?>"><?php echo $r['titulo_'. Locale::get()];?></a></p>
                <?php $previa = $r['conteudo_'. Locale::get()]; $previa = substr($previa, 0, 130); ?><?php echo $previa; ?>
                <span class="autor"><?php echo t('ler_noticia.l4')?> <?php echo $r['nome'];?></span>
            </div>
        </div>
	<?php else: ?>
    	<div id="proximas-noticias" class="<?php echo $cor; ?>" style="background-image:url(<?php echo img();?><?php echo $img; ?>);">
        <div class="data"><?php echo date("d/M", strtotime($r['data_ins'])); ?></div>
        <div class="titulos-proxima">
            <a href="?secao=ler_noticia&id=<?php echo $r['id'];?>"><?php echo $r['titulo_'. Locale::get()];?></a><br />
            <span class="autor"><?php echo t('ler_noticia.l4')?> <?php echo  $r['nome'];?></span>
        </div>
        </div>
    <?php endif; ?>
<?php
  }
?>
<div class="area-nav">
    <?php
        $qTotal = Recordset::query('SELECT COUNT(id) AS mx FROM noticia WHERE round = "r30"', true)->row_array();
    ?>
    <a href="javascript:void(0);" onclick="homeNoticias(<?php echo $back ?>)">
        <div class="esq-nav">
            <img src="<?php echo img();?>layout/home/left.png" />
        </div>
    </a>
    <strong style="color:#FFFFFF; font-size:14px;">
        <?php echo t('actions.a172')?> <?php echo $qTotal['mx'] ?>
        <?php echo t('actions.a173')?>
    </strong>
    <a href="javascript:void(0);" onclick="homeNoticias(<?php echo $next ?>)">
        <div class="dir-nav"><img src="<?php echo img();?>layout/home/right.png" /></div>
    </a>
</div>
</div>
