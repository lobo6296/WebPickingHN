<?php
		$data['php_version'] = phpversion();
		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		$db = array(
			'mysql', 
			'mysqli', 
			'pgsql', 
			'pdo'
		);

		if (!array_filter($db, 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		echo "GD: ".extension_loaded('gd')."<br>";
		echo "CURL: ".extension_loaded('curl')."<br>";
		echo "mcrypt: ".function_exists('mcrypt_encrypt')."<br>";
		echo "zlib: ".extension_loaded('zlib')."<br>";
		echo "zip: ".extension_loaded('zip')."<br>";
		

?>