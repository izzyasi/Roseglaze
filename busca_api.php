<?php

require 'conexao.php';

header('Content-Type: application/json');

$resposta = [
    'sucesso' => false,
    'produtos' => []
];

$dados = json_decode(file_get_contents('php://input'), true);
$termo_busca = $dados['termo_busca'] ?? '';

if (!empty($termo_busca)) {
    try {
        $sql = "SELECT id, modelo, preco FROM oculos 
                WHERE modelo LIKE ? OR cor LIKE ? 
                ORDER BY data_registro DESC
                LIMIT 6"; 
        
        $termo_sql = '%' . $termo_busca . '%';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$termo_sql, $termo_sql]);
        $produtos_encontrados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $resposta['sucesso'] = true;
        $resposta['produtos'] = $produtos_encontrados;
        
    } catch (PDOException $e) {
        $resposta['mensagem'] = "Erro ao buscar: " . $e->getMessage();
    }
}

echo json_encode($resposta);
exit;
?>