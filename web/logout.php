<?php
	$page = 'logout';
	session_start();
	$_SESSION = array();
	if (session_destroy()) {
		header("Location: index.php");
	}
?>