<?php
/*
 * Documentação: Página de Logout (logout.php)
 */
require 'conexao.php';

session_unset();
session_destroy();

header('Location: index.php');
exit;

?>