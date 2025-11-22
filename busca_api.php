<?php
require 'conexao.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$termo = $input['termo_busca'] ?? '';

if ($termo) {
    try {
        $sql = "SELECT id, modelo, preco, imagem FROM oculos WHERE modelo LIKE ? LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$termo%"]);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'sucesso' => true,
            'produtos' => $produtos
        ]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor']);
    }
} else {
    echo json_encode(['sucesso' => false, 'produtos' => []]);
}
?>