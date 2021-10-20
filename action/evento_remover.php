<?php
	Recordset::query("UPDATE evento SET removido=1 WHERE id=" . (int)$_POST['id']);
