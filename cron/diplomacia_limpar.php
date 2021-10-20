<pre>
<?php
	require('_config.php');

	Recordset::query("DELETE FROM diplomacia_voto");
	Recordset::query("ALTER TABLE diplomacia_voto AUTO_INCREMENT=1");
