<?php
	if(isset($_SESSION['acc'])) unset($_SESSION['acc']);
	if(isset($_SESSION['res'])) unset($_SESSION['res']);
	//cek apakah user sudah/dalam keadaan login atau tidak
	if(!isset($_SESSION['log'])) header('location:login');
?>