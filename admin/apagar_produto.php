<?php
/*
 * Documentação: Apagar Produto (admin/apagar_produto.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

if (!isset($_GET['id'])) {
    header('Location: gerir_produtos.php');
    exit;
}

$id_produto = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id_produto) {
    header('Location: gerir_produtos.php');
    exit;
}

try {
    $sql = "DELETE FROM oculos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_produto]);
    
} catch (PDOException $e) {
    die("Erro ao apagar o produto: " . $e->getMessage());
}

header('Location: gerir_produtos.php');
exit;

?>