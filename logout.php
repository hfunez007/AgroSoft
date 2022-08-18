<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
		localStorage.clear();
		window.location="index.html?logout";
	</script>
</head>
</html>

<?php 
 
	session_start();

	unset($_SESSION['autenticado']); 
	unset($_SESSION['username']);
	unset($_SESSION['psw']);

	session_unset();
	session_destroy();

	//header ("location:index.php?logout"); 
?>

