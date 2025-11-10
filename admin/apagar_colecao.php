<?php
/*
 * Documentação: Apagar Coleção (admin/apagar_colecao.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

if (!isset($_GET['id'])) {
    header('Location: gerir_colecoes.php');
    exit;
}

$id_colecao = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id_colecao) {
    header('Location: gerir_colecoes.php');
    exit;
}

try {
    $sql = "DELETE FROM colecoes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_colecao]);
    
} catch (PDOException $e) {
    die("Erro ao apagar a coleção: " . $e->getMessage());
}

header('Location: gerir_colecoes.php');
exit;

?>