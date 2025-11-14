<?php

require 'conexao.php';

if (!isset($_SESSION['sacola'])) {
    $_SESSION['sacola'] = [];
}

header('Content-Type: application/json');

$dados = json_decode(file_get_contents('php://input'), true);
$acao = $dados['acao'] ?? null; 

switch ($acao) {
    
    case 'adicionar':
        $id_produto = $dados['id'] ?? null;
        
        if ($id_produto) {
            if (!in_array($id_produto, $_SESSION['sacola'])) {
                $_SESSION['sacola'][] = $id_produto;
                echo json_encode(['sucesso' => true, 'mensagem' => 'Produto adicionado à sacola!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Este produto já está na sua sacola.']);
            }
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID do produto inválido.']);
        }
        break;

    case 'get_sacola':
        $produtos_na_sacola = [];
        $preco_total = 0;
        
        if (!empty($_SESSION['sacola'])) {
            $ids_na_sacola = $_SESSION['sacola'];
            
            $placeholders = implode(',', array_fill(0, count($ids_na_sacola), '?'));
            
            $sql = "SELECT * FROM oculos WHERE id IN ($placeholders)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids_na_sacola);
            $produtos_na_sacola = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($produtos_na_sacola as $produto) {
                $preco_total += $produto['preco'];
            }
        }
        
        echo json_encode([
            'sucesso' => true,
            'produtos' => $produtos_na_sacola, 
            'total' => number_format($preco_total, 2, ',', '.') 
        ]);
        break;

    default:
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Ação desconhecida.'
        ]);
        break;
}

exit;
?>