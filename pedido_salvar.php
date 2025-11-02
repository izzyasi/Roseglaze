<?php
/*
 * Documentação: Salvar Pedido (pedido_salvar.php)
 */

require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Acesso inválido.";
    exit;
}

if (!isset($_SESSION['sacola']) || empty($_SESSION['sacola'])) {
    header('Location: index.php');
    exit;
}

$nome_cliente = trim($_POST['nome_cliente']);
$email_cliente = trim($_POST['email_cliente']);
$endereco_entrega = trim($_POST['endereco_entrega']);
$ids_na_sacola = $_SESSION['sacola'];
$placeholders = implode(',', array_fill(0, count($ids_na_sacola), '?'));
$sql = "SELECT * FROM oculos WHERE id IN ($placeholders)";

$stmt = $pdo->prepare($sql);
$stmt->execute($ids_na_sacola);
$produtos_da_sacola = $stmt->fetchAll(PDO::FETCH_ASSOC);

$preco_total_seguro = 0;
foreach ($produtos_da_sacola as $produto) {
    $preco_total_seguro += $produto['preco'];
}

try {
    $pdo->beginTransaction();

    $sql_pedido = "INSERT INTO pedidos (preco_total, nome_cliente, endereco_entrega) 
                   VALUES (?, ?, ?)";
    
    $stmt_pedido = $pdo->prepare($sql_pedido);
    $stmt_pedido->execute([
        $preco_total_seguro,
        $nome_cliente,
        $endereco_entrega
    ]);

    $pedido_id = $pdo->lastInsertId();

    $sql_item = "INSERT INTO itens_pedidos (pedido_id, produto_id, quantidade, preco_unitario) 
                 VALUES (?, ?, ?, ?)";
    $stmt_item = $pdo->prepare($sql_item);
    
    foreach ($produtos_da_sacola as $produto) {
        $stmt_item->execute([
            $pedido_id,
            $produto['id'],
            1, 
            $produto['preco'] 
        ]);
    }

    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro ao salvar o pedido: ". $e->getMessage());
}

unset($_SESSION['sacola']);

header('Location: obrigado.php');
exit;

?>