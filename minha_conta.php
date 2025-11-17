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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                         fill="none" stroke="currentColor" stroke-width="2" 
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                         fill="none" stroke="currentColor" stroke-width="2" 
                         stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <div>
                        <strong>Lista de desejos</strong>
                        <p>A sua lista de desejos está atualmente vazia.</p>
                        <a href="wishlist.php">Ver lista de desejos</a>
                    </div>
                </div>
            </div>

            <div class="conta-dashboard-coluna">
                <h4>SERVIÇOS</h4>
                <div class="conta-link-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                         fill="none" stroke="currentColor" stroke-width="2" 
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path>
                    </svg>
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
                <a href="login-seguranca.php">Início de sessão e Segurança</a>
                <a href="detalhes-pessoais.php">Detalhes pessoais</a>
                <a href="#">Endereços</a>
                <a href="#">Métodos de pagamento</a>
                <a href="#">Preferências</a>
                
                <a href="logout.php">Sair</a>
            </nav>
        </div>

    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>

</body>
</html>