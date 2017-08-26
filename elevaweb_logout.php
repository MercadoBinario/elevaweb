<?php
if(isset($_SESSION['elevaweb_login']) && $_SESSION['elevaweb_login'] == 1) {
	unset($_SESSION['elevaweb_login']);
	$url = admin_url('admin.php?page=elevaweb');
	echo'<script> window.location="'.$url.'"; </script> ';
}