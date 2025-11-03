<?php
/*
 * Documentação: Página Meus Pedidos (meus_pedidos.php)
 */

require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; 
}

$usuario_id = $_SESSION['usuario_id'];
$pedidos = [];

try {
    $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
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
    <title>Roseglaze - Meus Pedidos</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-cadastro" style="max-width: 800px;">
        
        <div class="cadastro-header">
            <div class="cadastro-abas">
                <a href="minha_conta.php" class="aba-inativa">MINHA CONTA</a>
                <a href="meus_pedidos.php" class="aba-ativa">MEUS PEDIDOS</a>
            </div>
        </div>
        
        <div class="conta-painel">
            
            <?php if (count($pedidos) > 0): ?>
                
                <table class="tabela-pedidos">
                    <thead>
                        <tr>
                            <th>Nº do Pedido</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                
                                <td><?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?></td>
                                
                                <td><?php echo htmlspecialchars($pedido['status_pedido']); ?></td>
                                
                                <td>R$ <?php echo number_format($pedido['preco_total'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>
                
                <div style="text-align: center; padding: 40px;">
                    <p>Ainda não foram realizadas encomendas.</p>
                    <a href="index.php" class="btn-add-to-bag" style="width: 300px; text-decoration: none;">Começar a comprar</a>
                </div>

            <?php endif; ?>
            
        </div>

    </main>


    <?php require 'footer.php'; ?>

</body>
</html>