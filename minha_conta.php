<?php
/*
 * Documentação: Página Minha Conta (minha_conta.php)
 */

require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; 
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar dados do utilizador: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Minha Conta</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <?php require 'header.php'; ?>

    <div class="conta-bemvindo-faixa">
        <p>BEM-VINDO(A)</p>
        <h2><?php echo htmlspecialchars(strtoupper($usuario['nome'])); ?></h2>
    </div>

    <main class="container-conta">
        
        <div class="conta-dashboard-grid">
            
            <div class="conta-dashboard-coluna">
                <h4>OS SEUS PRODUTOS</h4>
                <div class="conta-link-item">
                    <span class="material-icons-outlined">shopping_bag_outline</span>
                    <div>
                        <strong>Pedidos</strong>
                        <p>Ainda não foram realizadas encomendas.</p>
                        <a href="meus_pedidos.php">Ver todos os pedidos</a>
                    </div>
                </div>
            </div>
            
            <div class="conta-dashboard-coluna">
                <h4>SELEÇÕES</h4>
                <div class="conta-link-item">
                    <span class="material-icons-outlined">star_border</span>
                    <div>
                        <strong>Lista de desejos</strong>
                        <p>A sua lista de desejos está atualmente vazia.</p>
                        <a href="#">Ver lista de desejos</a>
                    </div>
                </div>
            </div>

            <div class="conta-dashboard-coluna">
                <h4>SERVIÇOS</h4>
                <div class="conta-link-item">
                    <span class="material-icons-outlined">support_agent</span>
                    <div>
                        <strong>Recomendações e serviços</strong>
                        <p>Oferecemos serviços de reparação e manutenção.</p>
                        <a href="#">Encontrar um Espaço Roseglaze</a>
                    </div>
                </div>
            </div>

        </div> <hr style="border: 0; border-top: 1px solid #ddd; margin: 60px 0;">

        <div class="conta-detalhes-grid">
            <h4 class="secao-titulo">DETALHES DA CONTA</h4>
            
            <nav class="conta-detalhes-links">
                <a href="#">Início de sessão e Segurança</a>
                <a href="#">Detalhes pessoais</a>
                <a href="#">Endereços</a>
                <a href="#">Métodos de pagamento</a>
                <a href="#">Preferências</a>
                
                <a href="logout.php">Sair</a>
            </nav>
        </div>

    </main>

    <?php require 'footer.php'; ?>

</body>
</html>