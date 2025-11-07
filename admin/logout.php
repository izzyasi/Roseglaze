<?php

require '../conexao.php';

unset($_SESSION['admin_id']);
unset($_SESSION['admin_nome']);

header('Location: login.php');
exit; 

?>