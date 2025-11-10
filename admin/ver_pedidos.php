<?php
/*
 * Documentação: Ver Pedidos (admin/ver_pedidos.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$pedidos = []; // Inicializa um array vazio

try {
    $sql = "SELECT * FROM pedidos ORDER BY data_pedido DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar pedidos: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Ver Pedidos</title>
    
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
            <h2 class="secao-titulo">Ver Pedidos de Clientes</h2>
            <a href="index.php" style="text-decoration: none; color: #555;">
                &larr; Voltar ao Dashboard
            </a>
        </div>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>Nº Pedido</th>
                    <th>Data</th>
                    <th>Nome Cliente</th>
                    <th>Endereço</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pedidos) > 0): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nome_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['endereco_entrega']); ?></td>
                            <td>R$ <?php echo number_format($pedido['preco_total'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['status_pedido']); ?></td>
                            
                            <td class="tabela-acoes">
                                <a href="ver_detalhe_pedido.php?id=<?php echo $pedido['id']; ?>">Ver Itens</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum pedido encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </main>

</body>
</html>