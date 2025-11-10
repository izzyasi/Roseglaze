<?php
/*
 * Documentação: Gerir Coleções (admin/gerir_colecoes.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$colecoes = []; 

try {
    $sql = "SELECT * FROM colecoes ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $colecoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar coleções: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Gerir Coleções</title>
    
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
            <h2 class="secao-titulo">Gerir Coleções</h2>
            <a href="adicionar_colecao.php" class="btn-add-to-bag" style="text-decoration: none;">
                Adicionar Nova Coleção
            </a>
        </div>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Coleção</th>
                    <th>Descrição</th>
                    <th>Ativa?</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($colecoes) > 0): ?>
                    <?php foreach ($colecoes as $colecao): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($colecao['id']); ?></td>
                            <td><?php echo htmlspecialchars($colecao['nome']); ?></td>
                            <td><?php echo htmlspecialchars(substr($colecao['descricao'], 0, 50)); ?>...</td>
                            
                            <td><?php echo $colecao['ativa'] ? 'Sim' : 'Não'; ?></td>
                            
                            <td class="tabela-acoes">
                                <a href="editar_colecao.php?id=<?php echo $colecao['id']; ?>">Editar</a>
                                <a href="apagar_colecao.php?id=<?php echo $colecao['id']; ?>" class="link-apagar" style="color: #c00;">Apagar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Nenhuma coleção encontrada.</td>
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
                const confirmacao = confirm("Tem a certeza que deseja apagar esta coleção? Esta ação é permanente.");
                if (confirmacao === false) {
                    e.preventDefault(); 
                }
            });
        });
    });
    </script>

</body>
</html>