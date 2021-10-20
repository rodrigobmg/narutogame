<div class="titulo-secao"><p>Promoção</p></div>
  <br />
<?php
	if(isset($_GET['ok'])) {
		echo "<div class='msg_gai' style='background:url(".img()."msg/msg_naruto2.jpg);'><div class='msg'><span style='font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px'>Parab&eacute;ns!</span>".
			  "Seu palpite foi cadastrado com sucesso!" .
			  "</div></div><div style='clear: both'></div>";
	}

	$qUsuario = Recordset::query("SELECT * FROM promocao WHERE id_usuario=" . $_SESSION['usuario']['id']);
	
	if($qUsuario->num_rows):
?>
<div class="msg_gai" style="background:url(<?php echo img() ?>msg/msg_error.jpg);">
    <div class="msg">
            <span style="font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px">Você já participou!</span>
           Você já participou da promoção, fique atento as notícias para saber o resultado!
    </div>
</div>	
<?php else: ?>
	<?php
		if($_POST['nome']) {
			Recordset::query("INSERT INTO promocao(id_usuario, nome) VALUES(" . $_SESSION['usuario']['id'] . ", '" . addslashes($_POST['nome']) . "')");

			$redir_script = true;
			redirect_to("promocao", NULL, array("ok" => 1));
		}
	?>
	<script type="text/javascript">
		function doPromocaoSubmit() {
			if(!$("#f-promocao-t-nome").val()) {
				alert("O Nome não pode ser em branco!");
				return;
			}
			
			$("#f-promocao-b-send").attr("disabled", "disabled");
			$("#f-promocao").submit();
		}
	</script>
<div class="msg_gai" style="background:url(<?php echo img() ?>msg/msg_naruto2.jpg);">
    <div class="msg">
            <span style="font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px">Promoção Naruto Game!</span><strong>            Qual é o Kage mais novo do Naruto?</strong><br />
            <br />
            <br />
            <form method="post" id="f-promocao" onsubmit="return false">
		<input type="text" name="nome" id="f-promocao-t-nome" />
		<input type="button" id="f-promocao-b-send" onclick="doPromocaoSubmit()" value="Participar" /><br />
		( Válido até 24/01/2011 )
	</form>
    </div>
</div>		

<?php endif; ?>