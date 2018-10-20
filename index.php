<?php
if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
    && $_SERVER['PHP_AUTH_USER'] === 'tr37'
    && $_SERVER['PHP_AUTH_PW'] === 'goleta') {
		
    include 'main.php';
} else if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
    && $_SERVER['PHP_AUTH_USER'] === 'admin'
    && $_SERVER['PHP_AUTH_PW'] === 'password') {
	
	include 'adminAuth.php';
    
} else {
    header('WWW-Authenticate: Basic realm="Secure Site"');
    header('HTTP/1.0 401 Unauthorized');
    exit('This site requires authentication');
}
?>