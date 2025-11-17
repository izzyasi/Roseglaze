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
            if (isset($_SESSION['sacola'][$id_produto])) {
                $_SESSION['sacola'][$id_produto]++;
            } else {
                $_SESSION['sacola'][$id_produto] = 1;
            }

            $novo_total_itens = array_sum($_SESSION['sacola']);

            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Produto adicionado à sacola!',
                'novo_total' => $novo_total_itens 
            ]);

        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID do produto inválido.']);
        }
        break;

    case 'get_sacola':
        $produtos_na_sacola = [];
        $preco_total = 0;
        $total_itens = 0;
        
        if (!empty($_SESSION['sacola'])) {
            $ids_na_sacola = array_keys($_SESSION['sacola']);
            
            $placeholders = implode(',', array_fill(0, count($ids_na_sacola), '?'));
            $sql = "SELECT * FROM oculos WHERE id IN ($placeholders)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids_na_sacola);
            $produtos_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($produtos_db as $produto) {
                $id = $produto['id'];
                $quantidade = $_SESSION['sacola'][$id]; 
                $produto['quantidade'] = $quantidade;
                $produtos_na_sacola[] = $produto;  
                $preco_total += $produto['preco'] * $quantidade;
            }
            $total_itens = array_sum($_SESSION['sacola']);
        }
        
        echo json_encode([
            'sucesso' => true,
            'produtos' => $produtos_na_sacola, 
            'total' => number_format($preco_total, 2, ',', '.'),
            'total_itens' => $total_itens
        ]);
        break;

        case 'atualizar_quantidade':
        $id_produto = $dados['id'] ?? null;
        $nova_quantidade = $dados['quantidade'] ?? null;

        if (!$id_produto || !$nova_quantidade || !is_numeric($nova_quantidade) || $nova_quantidade <= 0 || $nova_quantidade > 10) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Quantidade inválida.']);
            break;
        }

        if (isset($_SESSION['sacola'][$id_produto])) {
            $_SESSION['sacola'][$id_produto] = (int)$nova_quantidade;
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Produto não encontrado na sacola.']);
            break;
        }
        $preco_total = 0;
        $total_itens = 0;
        
        if (!empty($_SESSION['sacola'])) {
            $ids_na_sacola = array_keys($_SESSION['sacola']);
            $placeholders = implode(',', array_fill(0, count($ids_na_sacola), '?'));
            $sql = "SELECT id, preco FROM oculos WHERE id IN ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids_na_sacola);
            $produtos_db = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 

            foreach ($_SESSION['sacola'] as $id => $qtd) {
                if (isset($produtos_db[$id])) {
                    $preco_total += $produtos_db[$id] * $qtd;
                }
                $total_itens += $qtd;
            }
        }
        echo json_encode([
            'sucesso' => true,
            'total_formatado' => number_format($preco_total, 2, ',', '.'),
            'total_itens' => $total_itens
        ]);
        break;

        case 'remover':
        $id_produto = $dados['id'] ?? null;
        
        if ($id_produto && isset($_SESSION['sacola'][$id_produto])) {
            unset($_SESSION['sacola'][$id_produto]);

            $novo_total_itens = array_sum($_SESSION['sacola']);

            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Produto removido.',
                'novo_total' => $novo_total_itens
            ]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Produto não encontrado na sacola.']);
        }
        break;
}

exit;
?>