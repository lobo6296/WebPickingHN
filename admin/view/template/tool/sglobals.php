<?php
$globals = array(
	'$_SERVER' => $_SERVER, '$_ENV' => $_ENV,
	'$_REQUEST' => $_REQUEST, '$_GET' => $_GET,
	'$_POST' => $_POST, '$_COOKIE' => $_COOKIE,
	'$_FILES' => $_FILES,
	);

// Generate the output

// echo '<hr>';

foreach ($globals as $globalkey => $global) {
	echo '<h3>' . $globalkey . '</h3>';
	foreach ($global as $key => $value) {
		if (!is_array($value)) {
			echo '<span class="left1">' . $globalkey . '[<span class="key1">\'' . $key . '\'</span>]</span> = <span class="right1">' . $value . '</span><br />';
		} else {
			foreach ($value as $val) {
			echo '--><span class="left1">' . $globalkey . '[<span class="key1">\'' . $key . '\'</span>]</span> = <span class="right1">' . $val . '</span><br />';
			}
		}
	}
}
?>
