<?php
function authMain() {
if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
    && $_SERVER['PHP_AUTH_USER'] === 'tr37'
    && $_SERVER['PHP_AUTH_PW'] === 'goleta') {
	return "user";
} else if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
    && $_SERVER['PHP_AUTH_USER'] === 'admin'
    && $_SERVER['PHP_AUTH_PW'] === 'password') {
	
	return "admin";
} else {
    header('WWW-Authenticate: Basic realm="Secure Site"');
    header('HTTP/1.0 401 Unauthorized');
    die('This site requires authentication');
}
}
?>