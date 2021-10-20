<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= array();

	$limit			= 10;
	$page			= isset($_POST['page']) && $_POST['page'] ? $_POST['page'] : 0;
	$start			= $page > 0 ? $page * $limit : 0;
	
	if(isset($_POST['search']) && $_POST['search']) {
		$json->success	= true;
		$color_counter	= 0;
		
		if($_SESSION['universal']) {
			$where		= '';
			
			if($_POST['status']) {
				$where	.= ' AND status="' . addslashes($_POST['status']) . '"';	
			}

			if($_POST['category']) {
				$where	.= ' AND category="' . addslashes($_POST['category']) . '"';	
			}
			
			if($_POST['title']) {
				$where	.= ' AND title LIKE "%' . addslashes($_POST['title']) . '%"';	
			}

			if($_POST['id']) {
				$where	.= ' AND id="' . addslashes($_POST['id']) . '"';	
			}
			
			$tickets	= Recordset::query('SELECT * FROM suporte WHERE 1=1 ' . $where . ' ORDER BY updated_at DESC LIMIT ' . $start . ',' . $limit);
			$tickets_np	= Recordset::query('SELECT * FROM suporte WHERE 1=1 ' . $where . ' ORDER BY updated_at');
		} else {
			$tickets	= Recordset::query('SELECT * FROM suporte WHERE user_id=' . $_SESSION['usuario']['id'] . ' LIMIT ' . $start . ',' . $limit);
			$tickets_np	= Recordset::query('SELECT * FROM suporte WHERE user_id=' . $_SESSION['usuario']['id']);
		}
		
		ob_start();
?>
<table	width="730" border="0" cellpadding="0" cellspacing="0">
	<?php if(!$tickets->num_rows): ?>
		<?php echo t('suporte.nenhum_resultado') ?>
	<?php endif ?>
	<?php foreach($tickets->result_array() as $ticket): ?>
	<?php
		$color	= $color_counter++ % 2 ? 'cor_sim' : 'cor_nao'
	?>
	<tr class="<?php echo $color ?>">
		<td width="220" align="center" height="35">
			<span style="font-size:13px">
				<a href="?secao=suporte_ticket&id=<?php echo $ticket['id'] ?>" class="linkTopo"><?php echo $ticket['title'] ?></a>
				[ #<?php echo $ticket['id'] ?> ]
			</span>
		</td>
		<td width="100" align="center">
			<span class="laranja">
			<?php
				switch($ticket['category']) {
					case 'bug':			$category	= t('suporte.categories.bug'); break;	
					case 'vip':			$category	= t('suporte.categories.vip'); break;	
					case 'question':	$category	= t('suporte.categories.question'); break;	
					case 'suggestion':	$category	= t('suporte.categories.suggestion'); break;	
					case 'other':		$category	= t('suporte.categories.other'); break;	
				}
				
				echo $category;
			?>
			</span>
		</td>
		<td width="110" align="center">
			<span class="azul">
			<?php
				switch($ticket['status']) {
					case 'awaiting':	$status	= t('suporte.statuses.awaiting'); break;	
					case 'replied':		$status	= t('suporte.statuses.replied'); break;	
					case 'new':			$status	= t('suporte.statuses.new'); break;
					case 'closed':		$status	= t('suporte.statuses.closed'); break;	
				}
				
				echo $status;
			?>
			</span>
		</td>
		<td width="140" align="center"><?php echo date('d/m/Y H:i:s', strtotime($ticket['created_at'])); ?></td>
		<td width="140" align="center"><?php echo date('d/m/Y H:i:s', strtotime($ticket['updated_at'])); ?></b></td>
	</tr>
	<tr height="4"></tr>
	<?php endforeach ?>
</table>
<div class="paginator">
	<?php for($f = 1; $f <= ceil($tickets_np->num_rows / $limit); $f++): ?>
		<a style="color:#FFF; text-decoration:none" href="javascript:;" class="page <?php echo $f - 1 == $page ? 'current' : '' ?>" data-page="<?php echo $f - 1 ?>"><?php echo $f ?></a>
	<?php endfor ?>
</div>
<?php
		$json->content	= ob_get_clean();
	} elseif(isset($_POST['ticket_token'])) {
		$json->success	= $_SESSION['ticket_token'] == $_POST['ticket_token'];
		$json->step		= $_SESSION['support_step'] + 1;
		
		if($_SESSION['ticket_token'] == $_POST['ticket_token']) {
			$_SESSION['support_step_' . ($_SESSION['support_step'] + 1)]	= true;
		}
	} elseif(isset($_POST['keyword']) && $_SESSION['support_step'] == 3) {
		$json->success	= true;
		$results		= Recordset::query('
			SELECT
				id,
				title,
				LEFT(content, 100) AS content,
				MATCH(title) AGAINST ("' . addslashes($_POST['keyword']) . '") AS ret1,
				MATCH(content) AGAINST ("' . addslashes($_POST['keyword']) . '") AS ret2
			FROM
				kb_topics
			
			WHERE
				 MATCH (title, content) AGAINST ("' . addslashes($_POST['keyword']) . '" IN BOOLEAN MODE)
			
			ORDER BY (ret1*1.5) + ret2 DESC
		');
		
		$data	= ob_start();
		?>
		<?php if($results->num_rows): ?>
			<div class="step1-topics">
				<?php foreach($results->result_array() as $result): ?>
				<div class="topic">
					<div class="title"><a class="linkTopo" href="?secao=suporte_topico&id=<?php echo $result['id'] ?>"><?php echo $result['title'] ?></a></div>
					<div><?php echo strip_tags($result['content']) ?>...</div>				
				</div>
				<?php endforeach ?>
				<div class="break"></div>
			</div>
			<p class="laranja" style="font-weight: bold"><?php echo t('suporte.steps.step3.msg3') ?></p>
		<?php else: ?>
			<p class="laranja" style="font-weight: bold"><?php echo t('suporte.steps.step3.msg4') ?></p>		
		<?php endif ?>
		<p><a class="button" id="support-next-step"><?php echo t('suporte.steps.next') ?></a></p>
		<?php
		$json->data	= ob_get_clean();
	}
	
	echo json_encode($json);
