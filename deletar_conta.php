<?php
/*
 * Documentação: Deletar Conta (deletar_conta.php)
 */

require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; 
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    
} catch (PDOException $e) {
    die("Erro ao deletar a conta: " . $e->getMessage());
}

session_unset();
session_destroy();

header('Location: index.php');
exit;

?>