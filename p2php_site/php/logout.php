<?php
//Destrói a sessão e redireciona para a página de login 
session_start();
session_destroy();
header("Location: ../views/login/login.php");
exit();
?>