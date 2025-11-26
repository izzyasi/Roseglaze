<?php

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

if (!isset($_GET['id'])) {
    header('Location: gerir_espacos.php');
    exit;
}

$id_loja = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id_loja) {
    header('Location: gerir_espacos.php');
    exit;
}

try {
    $sql = "DELETE FROM lojas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_loja]);
    
} catch (PDOException $e) {
    die("Erro ao apagar a loja: " . $e->getMessage());
}

header('Location: gerir_espacos.php');
exit;

?>