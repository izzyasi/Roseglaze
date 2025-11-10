<?php
/*
 * Documentação: Gerir Espaços (admin/gerir_espacos.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$espacos = []; 

try {
    $sql = "SELECT * FROM espacos ORDER BY data_registro DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $espacos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar espaços: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Gerir Espaços</title>
    
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
            <h2 class="secao-titulo">Gerir "Espaços Roseglaze"</h2>
            <a href="adicionar_espaco.php" class="btn-add-to-bag" style="text-decoration: none;">
                Adicionar Novo Espaço
            </a>
        </div>
        
        <table class="tabela-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Local</th>
                    <th>Endereço Curto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($espacos) > 0): ?>
                    <?php foreach ($espacos as $espaco): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($espaco['id']); ?></td>
                            <td><?php echo htmlspecialchars($espaco['nome_local']); ?></td>
                            <td><?php echo htmlspecialchars($espaco['endereco_curto']); ?></td>
                            
                            <td class="tabela-acoes">
                                <a href="editar_espaco.php?id=<?php echo $espaco['id']; ?>">Editar</a>
                                <a href="apagar_espaco.php?id=<?php echo $espaco['id']; ?>" class="link-apagar" style="color: #c00;">Apagar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Nenhum espaço encontrado.</td>
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
                const confirmacao = confirm("Tem a certeza que deseja apagar este espaço? Esta ação é permanente.");
                if (confirmacao === false) {
                    e.preventDefault(); 
                }
            });
        });
    });
    </script>

</body>
</html>