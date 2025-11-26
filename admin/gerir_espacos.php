<?php

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$lojas = []; 

try {
    $sql = "SELECT * FROM lojas ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar lojas: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Gerir Lojas</title>
    
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="admin.css"> 
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
            <h2 class="secao-titulo">Gerir Lojas</h2>
            <a href="adicionar_espaco.php" class="btn-add-to-bag" style="text-decoration: none;">
                Adicionar Nova Loja
            </a>
        </div>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Horário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($lojas) > 0): ?>
                    <?php foreach ($lojas as $loja): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($loja['id']); ?></td>
                            <td><?php echo htmlspecialchars($loja['nome']); ?></td>
                            <td><?php echo htmlspecialchars($loja['endereco']); ?></td>
                            <td><?php echo htmlspecialchars($loja['horario'] ?? '-'); ?></td>
                            
                            <td class="tabela-acoes">
                                <a href="editar_espaco.php?id=<?php echo $loja['id']; ?>">Editar</a>
                                <a href="apagar_espaco.php?id=<?php echo $loja['id']; ?>" class="link-apagar" style="color: #c00;">Apagar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Nenhuma loja encontrada.</td>
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
                const confirmacao = confirm("Tem a certeza que deseja apagar esta loja? Esta ação é permanente.");
                if (confirmacao === false) {
                    e.preventDefault(); 
                }
            });
        });
    });
    </script>

</body>
</html>