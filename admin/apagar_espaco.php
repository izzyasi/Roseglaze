<?php
/*
 * Documentação: Apagar Espaço (admin/apagar_espaco.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

if (!isset($_GET['id'])) {
    header('Location: gerir_espacos.php');
    exit;
}

$id_espaco = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id_espaco) {
    header('Location: gerir_espacos.php');
    exit;
}

try {
    $sql = "DELETE FROM espacos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_espaco]);
    
} catch (PDOException $e) {
    die("Erro ao apagar o espaço: " . $e->getMessage());
}

header('Location: gerir_espacos.php');
exit;

?>