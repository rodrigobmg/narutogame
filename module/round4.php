<table width="730" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2"><img src="<?php echo img() ?>bt_round4.jpg" alt="Round 4  - Naruto Game" /></td>
</tr>
</table>
<script type="text/javascript">
	var __pergaminho_e = false;
	
	function expande_pergaminho() {
		if(__pergaminho_e) {
			$('#p-meio').show('blind');
		} else {
			$('#p-meio').hide('blind');
		}
		
		__pergaminho_e = !__pergaminho_e;
	}
	
	function expande_div(t, id) {
		if(!parseInt($(t).attr('expandido'))) {
			$(t).attr('src', '<?php echo img('pergaminho/menos.png') ?>');			
			$(t).attr('expandido', 1);
			
			$('#' + id).show('blind');
		} else {
			$(t).attr('src', '<?php echo img('pergaminho/mais.png') ?>');
			$(t).attr('expandido', 0);

			$('#' + id).hide('blind');
		}
	}
</script>
<div class="msg_gai" style="background:url(<?php echo img() ?>msg/msg_naruto.jpg);">
	<div class="msg">
			<span style="font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px">Novidades do Round 4,</span>
			Conheça abaixo todas as mudanças feitas para o Round 4 que serão testadas por você
			no Round Pré Oficial R4.<br /><br />
			
			Nesse Pré Oficial iremos validar todas as melhorias com quem mais entende 
			do Naruto Game, você!
	</div>
</div>
<div style="clear: both"></div>		
<div id="pergaminho" >
	<div id="p-topo" onclick="expande_pergaminho()">
    </div>
    <div id="p-meio">
			
               
  		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Escolha a classe do seu personagem</b>
                    <b class="p-texto">O tipo do seu ninja só depende de sua preferência no Round 4.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-1')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-1">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
						Na criação do personagem o jogador agora possui uma opção para escolher qual o tipo de atributos iniciais que ele terá, ou seja, o jogador escolhe se seu personagem será Ninjutsu, Taijutsu, Genjutsu ou Balanceado.
						<br /><br />
						Os personagens já criados deverão usar a opção vip de mudança de classe do personagem na página do jogador vip.
						<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=507" target="_blank">Clique aqui</a>
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novos Atributos e Formulas</b>
                    <b class="p-texto">Formulas simplificadas e atributos renomeados!</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-12')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-12">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
						As formulas do jogo foram refeitas de forma mais simples e o atributo "Conhecimento" agora chama-se "Selo".
								<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=515" target="_blank">Clique aqui</a>	
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Ataques Criticos, Esquivas e Defesa Extra</b>
                    <b class="p-texto">Muita coisa mudou e agora você vai entender como e porque.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-13')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-13">
						
						<b>Defesa Extra</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
						Não existe mais no Naruto Game apartir do Round 4.
						<br /><br />
						</p>
						
						<b>Critico e Esquiva ( Nova Precisão de Jutsus )</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora o ninja no Naruto Game tem novos objetivos para ser bem sucedido em combate:<br />
							1º - Aprender o jutsu ( Usando ( Tai ou Nin ou Gen ) + ( Força ou Inteligência )<br />
							2º - Ter Precisão no Jutsu em combate ( Novo sistema de critico e esquiva )<br /><br />
							
							A Precisão nada mais é que o aperfeiçoamento do ninja em um jutsu, ou seja, cada jutsu possui um requerimento dentro de combate.<br /><br />
							Taijutsus ( Somente Agilidade )<br />
							Ninjutsus ( Selo e Agilidade )<br />
							Genjutsus ( Selo e Agilidade )<br /><br />
							
							Um jutsu para ter 100% de acerto o ninja precisa o total do requerimento do jutsu, ou seja:<br />
							
							Jutsu A<br />
							Requer: Agilidade ( 5 ) e Selo ( 20 )<br /><br />
							
							Se o personagem possui exatamente 5 de Agilidade e 20 de Selo, o jutsu será aplicado com Precisão de 100% ( sem chance de errar )<br /><br />
							
							Caso o personagem possua 10 de Agilidade e 30 de Selo, o jutsu ainda terá 100% de precisão e pelo jogador ter mais requerimento do que o necessário, agora o seu jutsu terá a chance de ser executado com excelência, podendo ser um ataque critico.<br /><br />
							
							Mas o personagem pode não possuir os requerimentos do golpe e com isso o mesmo possui chances de errar o jutsu em combate. ( nova esquiva )
							
						<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=515" target="_blank">Clique aqui</a>	
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novos Personagens e Novas Fotos</b>
                    <b class="p-texto">Foram adicionados novos personagens e novas fotos de alguns.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-2')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-2">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Novos personagens foram adicionados ( +- 15 novos personagens ) e estamos no momento tentando adicionar mais!
						<br /><br />
							Novas fotos também foram adicionadas de alguns personagens e a idéia é sempre adicionar mais! ( Lembrando que somente jogadores VIP podem trocar a foto da página de status ).
							<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=509" target="_blank">Clique aqui</a>		
					
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novas Conquistas</b>
                    <b class="p-texto">Muitas conquistas foram cadastradas para maior diversão</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-3')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-3">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Muitas conquistas novas foram cadastradas relacionadas aos prêmios do Sorte Ninja, Eventos Ninjas, Créditos Vip, Ryous e dos personagens novos.	
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
      
       <div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Equipes com Level e Prêmios</b>
                    <b class="p-texto">Ganhe prêmios importantes sendo fiel e ativo em sua equipe</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-4')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-4">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora as Equipes possuem Level e todos seus integrantes ganham experiência em suas atividades normais no jogo.
							<br /><br />							
							Esses pontos ganhos são somados e transformados para level da própria equipe ( A Equipe e os integrantes possuem um limite de experiência diária ).
							<br /><br />
							Ao alcançar os pontos descritos na nova página de Equipes, os integrantes upam sua Equipe e ainda ganham prêmios importantissimos!
								<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=513" target="_blank">Clique aqui</a>		
					
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novo Módulo: Estudo Ninja</b>
                    <b class="p-texto">Teste seus Conhecimentos de Naruto e ganhe pontos!</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-5')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-5">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							O Estudo Ninja é uma grande diversão, todos os ninjas terão que responder 10 perguntas e as perguntas certas serão somadas em sua pontuação.
							<br /><br />							
							Esses pontos poderão ser trocados por diversos prêmios que estão anunciados na própria página do Estudo Ninja e de quebra os Ninjas terão um novo Ranking no Site que mostra os ninjas que mais acertaram as questões do Estudo Ninja.					
						<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=509" target="_blank">Clique aqui</a>
						<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=523" target="_blank">Clique aqui</a>			
					
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novo Módulo: Bingo Book</b>
                    <b class="p-texto">Derrote os alvos de seu Bingo Book e ganhe recompensas</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-6')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-6">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Ninjas da graduação Jounin possuem agora o famoso Bingo Book ( Livro que possui informações de seus alvos ) e que quando derrotados dão prêmios especiais.			
							<br /><br />
							Toda semana é adicionado um ninja novo para seu Bingo Book para caça-lo nos mapas do Naruto Game.
							<br /><br />
							Engana-se quem acha que essa é uma tarefa fácil, pois, nem todos os ninjas são fáceis de ser caçados, o que torna a diversão ainda mais legal.
							<br /><br />
							Nota: Agora os ninjas que estão no seu Bingo Book possuem a cor amarela no icone dos mapas ( Mundi e da Vila ). 
								<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=500" target="_blank">Clique aqui</a>							
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novas regras no sorteio das Bijuus</b>
                    <b class="p-texto">Ninjas com mais atividade no site agora possuem mais chance de ganhar bijuu.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-7')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-7">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Depois de receber muitas reclamações sobre o fator "sorte" no sorteio das bijuus, resolvemos realmente criar um novo sistema para beneficiar aos jogadores que logam diariamente em nosso jogo.
							<br /><br />
							Todos os jogadores começam com 10 pontos e ao logar-se no site o jogador ganha +1 ponto ( Somado apenas no primeiro login do dia ). 
							<br /><br />
							Foram criados alguns grupos de range de pontos ( 1 ~ 10 / 11 ~ 20 .... 91 ~ 100 ), o jogador é classificado atraves da quantidade de pontos que possui e ao ser escolhido e ganhar o bijuu o mesmo volta a ter 1 ponto.
								<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=520" target="_blank">Clique aqui</a>	
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novo sistema de treino de jutsus</b>
                    <b class="p-texto">Agora os ninjas precisam de pontos para treinar seus jutsus.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-8')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-8">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora o ninja precisa ter pontos para treinar seus jutsus ( level 1 / 2 / 3 ).
							<br /><br />
							Esses pontos são ganhos a cada 5 pontos nos atributos ( Taijutsu, Ninjutsu e Genjutsu ). Logo 5 pontos de Taijutsu dá o direito de um ninja escolher um jutsu e destrava-lo para treina-lo até o level 3.
							<br /><br />
							Outra mudança é que cada ninja só pode treinar até 3000 exp por dia.
							<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=522" target="_blank">Clique aqui</a>	
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Mudança nos Clãs, Invocações, Selos, M. Sennin e Portões</b>
                    <b class="p-texto">Agora atributos básicos são adicionados fora de combate</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-9')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-9">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Todas as habilidades acima citadas agora possuem 2 tipos de liberação de seus bonus para o personagem:
							<br /><br />
							Atributos fora de combate: Taijutsu, Ninjutsu, Genjutsu, Energia, Chakra, Stamina e HP<br />
							( Esses atributos são somados fora de combate, quando o jogador aprende as habilidades )
							<br /><br />
							Atributos dentro de combate: Agilidade, Selo, Força, Inteligência e Resistência<br />
							( Esses atributos são somados apenas no dojo, quando o jogador clica nas habilidades )
							<br /><br />
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=524" target="_blank">Clique aqui</a>	
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		
		
		<div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Ranking das Vilas</b>
                    <b class="p-texto">Acompanhe o status de sua vila nas invasões.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-14')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-14">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora todos podem acompanhar o ranking de vilas, que mostrarão as vilas que melhor desempenharam seu papel nas missões de invasão. ( Derrotar guardiões ).
							<br /><br />
							
							Uma organização ao aceitar uma missão de invasão tem 24 horas para conclui-la e ganhar 1 vitória para sua organização e 1 derrota para a vila atacada caso vença o desafio.<br /><br />
							
							Se a organização falhar a mesma ganha 1 derrota e a vila atacada ganha 1 vitória.
							
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Novas Vantagens Vips e Mudanças</b>
                    <b class="p-texto">Algumas novas vantagens foram adicionadas, acompanhe.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-15')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-15">
						<b>Memória Ninja</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Ao comprar essa vantagem seu ninja está seguro em desaprender jutsus de level 2 e 3, pois, ao aprender os jutsus novamente ele lembrará de seu treino e os jutsus já virão no level adequado.
							<br /><br />
						</p>
						<b>Desaprendendo Jutsus level 1 , 2 e 3</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Libere pontos para outros treinamentos de jutsus usando essa vantagem vip.
							<br /><br />
						</p>
						<b>Mudança de Classe Ninja</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Altere os atributos iniciais do seu ninja.
							<br /><br />
						</p>
                </div>
        </div>
        <div style="clear: both"></div>
		
			
		<div class="p-cima ok2">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Batalhas de Equipe ( 4x1 e 4x4 )</b>
                    <b class="p-texto">Algumas novas vantagens foram adicionadas, acompanhe.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-16')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-16">
						<b>Entendendo melhor</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
						O sistema de batalha é bem parecido com o já existente no jogo, porem tem algumas diferenças.<br /><br />

						- Somente um jogador pode atacar por vez. Você terá que escolher um alvo, usar uma habilidade ou genjutsu caso tenha, e efetuar o ataque. O inimigo irá reagir ao ataque e assim seu turno será encerrado e será a vez do inimigo atacar. Ele irá executar a mesma sequência de operações(mesmo que seja um npc) e quem for escolhido como alvo vai ter que fazer alguma ação, e assim segue sucessivamente até todos terem efectuado suas acções.<br /><br />
						
						- Usuários de golpes genjutsu em batalhas 4x4 poderão aplicar seus genjutsus em um inimigo e atacar outro completamente diferente, tornando assim a batalha mais estratégica.<br /><br />
						
						- Golpes medicinais estarão disponíveis nas batalhas 4x1 e 4x4. Porem de modo diferente. Você não pode usar em você mesmo, terá que escolher um integrante da sua equipe para ser curado e você ira passar o seu turno com isso.<br /><br />
						
						Acesse o link da noticia completa: <a href="http://narutogame.com.br/?secao=ler_noticia&id=525" target="_blank">Clique aqui</a>	
						


					
							<br /><br />
						</p>
					
                </div>
        </div>
        <div style="clear: both"></div>
		
		<div class="p-cima ok">
        		<div class="p-flag">
                		   <img src="<?php echo img() ?>pergaminho/button_ok.png" class="p-align" />
                </div>
                <div class="p-description">
                	<b class="p-titulo">Diversas melhorias para o jogo</b>
                    <b class="p-texto">Diversas melhorias foram feitas para o jogo, vejam algumas.</b>
                </div>
                <div class="p-button">
               			<img src="<?php echo img() ?>pergaminho/mais.png" style="cursor:pointer;" onclick="expande_div(this, 'p-info-11')" class="p-align2"/>
                </div>
                <div style="clear: both"></div>
                <div class="p-baixo" id="p-info-11">
						<b>5 Merendas Ninjas</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Ao criar um novo personagem o mesmo já começa com 5 Merenda Ninja para ajudar na recuperação de seus treinamentos e lutas no dojo.	
							<br /><br />
						</p>
						<b>Graduação Gennin</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora o Ninja só precisa de 5 vitórias contra o dojo para se graduar ( Antes 10 vitórias ).	
							<br /><br />
						</p>
						<b>Noticias otimizadas!</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora o jogador consegue pular de noticia a noticia com botões de próxima e anterior, sem precisar voltar para a home.
							<br /><br />
						</p>
						<b>Lider de Equipe mandando mensagem para a Equipe</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							O Lider da Equipe agora pode mandar mensagem para todos os membros da Equipe na página de detalhes da própria.
							<br /><br />
						</p>
						<b>Armas do Ninja Shop Reformuladas</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							O Número de armas para usuarios de força e de inteligência foram balanceadas e agora os jogadores vips podem comprar armas com um pouco mais de dano usando créditos vips.
							<br /><br />
						</p>
						<b>Talentos Ninja</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora os ninjas já começam a ganhar pontos desde o level 5.
							<br /><br />
						</p>
						<b>Nova página de Formulas</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora a formula do Ranking de Ninjas, Equipes, Organizações e demais são mostradas na página de Formulas do Jogo.
							<br /><br />
						</p>
						<b>Clãs, Selos, Mode Sennin, Portões de Chakra e Invocações</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora com tooltip mostrando o total de bonus que o usuário ganha ao ativa-lo em combate.
							<br /><br />
						</p>
						<b>Nova Disposição do Menu</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							O Link do Chat e dos Eventos foram reposicionados e um menu novo foi adicionado com as tecnicas de portões de chakra ( Separadas dos jutsus de Taijutsu padrão ).
							<br /><br />
						</p>
						<b>Cancelando os Eventos Ninja</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							O Ninja que quiser cancelar o evento agora pode, porém, ao cancelar o ninja abre mão do mesmo.
							<br /><br />
						</p>
						<b>Vitórias Dojo NPC</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora qualquer vitória contra NPC é computada como DOJO PVP ( Removendo o problema que tivemos no módulo de Guardião e Evento de Equipes )
							<br /><br />
						</p>
						<b>Equipes com novas regras.</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							* O lider só poderá deletar a equipe se a mesma não possuir membros.<br />
							* Em dia de Evento é bloqueado qualquer ação de exclusão nas Equipes.	
							<br /><br />
						</p>
						<b>Missões de Invasão ( Organizações )</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora as Organizações só conseguem derrotar um npc de invasão por semana, ao vencer o desafio a Organização fica proibida de tentar derrotar outros guardiões nessa semana.
							<br /><br />
						</p>
						<b>Pontos ganhos em combate</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora os pontos ganhos ao usar os jutsus em combate foram aumentados de 10 para 20 pontos por jutsu usado.
							<br /><br />
						</p>
						<b>Prêmios melhorados nas missões de invasão</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							O Prêmio para as organizações que finalizarem com sucesso a missão de invasão foi melhorado de 2000 exp e 5000 ryous para 5000 exp e 10000 ryous.
							<br /><br />
						</p>
						<b>Treinamento de Jutsus</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora todos os jogadores possuem limite de 3000 de exp de treino por dia.<br />
							E os valores por tempo também foram melhorados. 1 hora = 1000 exp , 2 horas = 2000 exp e 3 horas = 3000 exp.<br />
							Você pode escolher 3 jutsus diferentes e treinar 1 hora em cada, como também pode escolher 1 para treinar 2 horas e outro 1 hora.
							<br /><br />
						</p>
						<b>Jutsus com Buffs e DeBuffs</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora todos os ninjas podem executar 1 jutsu buff ( golpe que adiciona poder ao seu poder ) e juntamente com esse jutsu podem usar um jutsu debuff ( Jutsu que remove atributos do personagem ).<br />
							Taijutsu e Ninjutsu possuem jutsus de buff e Genjutsu possuem jutsus debuff.
							
							
							<br /><br />
						</p>
						<b>Dojo com novas Abas</b>
                		<p style="width:640px; text-align:left; padding-left:10px">
							Agora as armas possuem uma aba exclusiva para diferenciar no combate.
							<br /><br />
						</p>
						
                </div>
        </div>
        <div style="clear: both"></div>
		
    </div>
    <div id="p-fim" onclick="expande_pergaminho()">
    </div>	
</div>

