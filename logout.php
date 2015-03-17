<?php
	include_once "config.php";
	unset($_SESSION['user']);
	header('Location: admin.php');
?>
