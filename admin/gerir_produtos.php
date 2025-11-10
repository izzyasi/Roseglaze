<?php
/*
 * Documentação: Gerir Produtos (admin/gerir_produtos.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$oculos = []; 
try {
    $sql = "SELECT * FROM oculos ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $oculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Gerir Produtos</title>
    
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
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
            <h2 class="secao-titulo">Gerir Produtos</h2>
            <a href="adicionar_produto.php" class="btn-add-to-bag" style="text-decoration: none;">
                Adicionar Novo Produto
            </a>
        </div>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Preço</th>
                    <th>Stock</th>
                    <th>Destaque?</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($oculos) > 0): ?>
                    <?php foreach ($oculos as $oculo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($oculo['id']); ?></td>
                            <td><?php echo htmlspecialchars($oculo['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($oculo['tipo_produto']); ?></td>
                            <td>R$ <?php echo number_format($oculo['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($oculo['stock']); ?></td>
                            <td><?php echo $oculo['em_destaque'] ? 'Sim' : 'Não'; ?></td>
                            
                            <td class="tabela-acoes">
                                <a href="editar_produto.php?id=<?php echo $oculo['id']; ?>">Editar</a>
                                
                                <a href="apagar_produto.php?id=<?php echo $oculo['id']; ?>" 
                                   class="link-apagar" 
                                   style="color: #c00;">Apagar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum produto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </main>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const linksApagar = document.querySelectorAll('.link-apagar');
        
        linksApagar.forEach(function(link) {
            
            link.addEventListener('click', function(e) {
                
                const confirmacao = confirm("Tem a certeza que deseja apagar este produto? Esta ação é permanente.");
                
                if (confirmacao === false) {
                    e.preventDefault(); 
                }
                
            });
        });
        
    });
    </script>

</body>
</html>