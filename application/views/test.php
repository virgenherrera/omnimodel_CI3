<?php
	$counter = 1;
	foreach ($querys as $query) {
		echo '<h2>Consulta numero: '.$counter.'</h2>';
		echo '<pre>';
		var_dump($query);
		echo '</pre>';
		$counter++;
	}

?>