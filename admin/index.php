<?php

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_nome = $_SESSION['admin_nome'] ?? 'Admin';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Dashboard</title>
    
    <link rel="stylesheet" href="admin.css">
</head>
<body style="background-color: #f0f0f0;">

    <header class="admin-header">
        <div classs="admin-header-logo">
            <a href="index.php">Roseglaze Admin</a>
        </div>
        <div class="admin-header-user">
            <span>Olá, <?php echo htmlspecialchars($admin_nome); ?></span>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <main class="admin-dashboard container-produtos">
        
        <h2 class="secao-titulo">Dashboard Principal</h2>
        
        <p>Bem-vindo ao seu painel de controlo. A partir daqui, você pode gerir o seu site.</p>
        
        <div class="admin-grid">
        <a href="gerir_produtos.php" class="admin-card">
            <h3>Gerir Produtos</h3>
            <p>Adicionar, editar ou remover óculos.</p>
        </a>

        <a href="gerir_colecoes.php" class="admin-card">
            <h3>Gerir Coleções</h3>
            <p>Adicionar ou editar as suas coleções.</p>
        </a>

        <a href="gerir_espacos.php" class="admin-card">
            <h3>Gerir "Espaços"</h3>
            <p>Adicionar ou editar as suas lojas-conceito.</p>
        </a>

        <a href="ver_pedidos.php" class="admin-card">
            <h3>Ver Pedidos</h3>
            <p>Ver os pedidos feitos pelos clientes.</p>
        </a>
        </div>

    </main>
  </body>
</html>