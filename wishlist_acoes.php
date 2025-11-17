<?php

require 'conexao.php';

header('Content-Type: application/json');

$resposta = [
    'sucesso' => false,
    'mensagem' => 'Acesso negado.'
];

if (!isset($_SESSION['usuario_id'])) {
    $resposta['mensagem'] = 'Utilizador não está logado. Por favor, faça o login.';
    echo json_encode($resposta);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$dados = json_decode(file_get_contents('php://input'), true);
$acao = $dados['acao'] ?? null;
$produto_id = $dados['id_produto'] ?? null;

if (!$produto_id) {
    $resposta['mensagem'] = 'ID do produto inválido.';
    echo json_encode($resposta);
    exit;
}

try {

    if ($acao === 'adicionar') {
        $sql = "INSERT IGNORE INTO wishlist (usuario_id, produto_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $produto_id]);
        
        $resposta = [
            'sucesso' => true,
            'mensagem' => 'Adicionado à Lista de Desejos!',
            'nova_acao' => 'remover' 
        ];
    } 
    
    elseif ($acao === 'remover') {
        
        $sql = "DELETE FROM wishlist WHERE usuario_id = ? AND produto_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $produto_id]);
        
        $resposta = [
            'sucesso' => true,
            'mensagem' => 'Removido da Lista de Desejos!',
            'nova_acao' => 'adicionar'
        ];
    }
    
    else {
        $resposta['mensagem'] = 'Ação desconhecida.';
    }

} catch (PDOException $e) {
    $resposta['mensagem'] = 'Erro no banco de dados: ' . $e->getMessage();
}

echo json_encode($resposta);
exit;
?>