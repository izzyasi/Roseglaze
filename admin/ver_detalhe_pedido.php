<?php
/*
 * Documentação: Detalhe do Pedido (admin/detalhe_pedido.php)

 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

if (!isset($_GET['id'])) {
    die("Erro: ID do pedido não fornecido.");
}
$pedido_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$pedido_id) {
    die("Erro: ID do pedido inválido.");
}

$pedido = null;
$itens_do_pedido = [];

try {
    $sql_pedido = "SELECT * FROM pedidos WHERE id = ?";
    $stmt_pedido = $pdo->prepare($sql_pedido);
    $stmt_pedido->execute([$pedido_id]);
    $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die("Pedido não encontrado.");
    }

    $sql_itens = "SELECT 
                    itens_pedidos.*, 
                    oculos.modelo 
                  FROM 
                    itens_pedidos 
                  LEFT JOIN 
                    oculos ON itens_pedidos.produto_id = oculos.id 
                  WHERE 
                    itens_pedidos.pedido_id = ?";
                    
    $stmt_itens = $pdo->prepare($sql_itens);
    $stmt_itens->execute([$pedido_id]);
    $itens_do_pedido = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar detalhes do pedido: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Detalhe Pedido #<?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></title>
    
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="admin.css"> </head>
<body style="background-color: #f0f0f0;">

    <header class="admin-header">
        <div classs="admin-header-logo">
            <a href="index.php">Roseglaze Admin</a>
        </div>
        <div class="admin-header-user">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?></span>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <main class="admin-dashboard container-produtos">
        
        <div class="admin-page-header">
            <h2 class="secao-titulo">Detalhes do Pedido #<?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></h2>
            <a href="ver_pedidos.php" style="text-decoration: none; color: #555;">
                &larr; Voltar para todos os pedidos
            </a>
        </div>
        
        <div class="admin-detalhes-pedido">
            
            <div class="admin-card" style="margin-bottom: 20px;">
                <h3>Detalhes do Cliente</h3>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['nome_cliente']); ?></p>
                <p><strong>Endereço de Entrega:</strong> <?php echo htmlspecialchars($pedido['endereco_entrega']); ?></p>
                </div>
            
            <div class="admin-card" style="margin-bottom: 20px;">
                <h3>Detalhes do Pedido</h3>
                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($pedido['status_pedido']); ?></p>
                <p><strong>Preço Total:</strong> R$ <?php echo number_format($pedido['preco_total'], 2, ',', '.'); ?></p>
            </div>
            
        </div>
        
        <h2 class="secao-titulo" style="margin-top: 30px;">Itens Neste Pedido</h2>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID do Produto</th>
                    <th>Nome do Produto (Modelo)</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário (Histórico)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($itens_do_pedido) > 0): ?>
                    <?php foreach ($itens_do_pedido as $item): ?>
                        <tr>
                            <td><?php echo $item['produto_id'] ?? '[Produto Apagado]'; ?></td>
                            <td><?php echo htmlspecialchars($item['modelo'] ?? '[Produto Apagado]'); ?></td>
                            <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                            <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Nenhum item encontrado para este pedido.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>