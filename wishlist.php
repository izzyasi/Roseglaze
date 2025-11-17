<?php
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$produtos_wishlist = [];

try {
    $sql = "SELECT o.* FROM oculos o 
            INNER JOIN wishlist w ON o.id = w.produto_id 
            WHERE w.usuario_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $produtos_wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $produtos_wishlist = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Minha Wishlist</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-wishlist">
        
        <div class="wishlist-header">
            <h1>MINHA WISHLIST</h1>
        </div>

        <?php if (empty($produtos_wishlist)): ?>
            <div class="wishlist-vazia-container">
                <h2>CRIAR UMA WISHLIST</h2>
                <p>Adicione produtos e looks à sua wishlist e compartilhe.</p>
                <a href="catalogo.php" class="btn btn-add-to-bag" style="width: auto; margin-top: 20px;">Explorar Coleção</a>
            </div>

        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($produtos_wishlist as $produto): ?>
                    <div class="wishlist-card" id="wishlist-item-<?php echo $produto['id']; ?>">
                        
                        <button class="btn-remover-wishlist" data-id-produto="<?php echo $produto['id']; ?>" title="Remover da Wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                                 fill="currentColor" stroke="currentColor" stroke-width="2" 
                                 stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>

                        <a href="produto.php?id=<?php echo $produto['id']; ?>" class="wishlist-img-link">
                            <div class="wishlist-img-placeholder"></div>
                        </a>

                        <div class="wishlist-info">
                            <h3><?php echo htmlspecialchars(strtoupper($produto['modelo'])); ?></h3>
                            <p class="wishlist-desc"><?php echo htmlspecialchars($produto['cor']); ?></p>
                            
                            <?php if($produto['preco'] > 0): ?>
                                <p class="wishlist-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                            <?php else: ?>
                                <p class="wishlist-price">Preço sob consulta</p>
                            <?php endif; ?>

                            <a href="produto.php?id=<?php echo $produto['id']; ?>" class="wishlist-ver-detalhes">
                                Ver detalhes >
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    <script src="js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const botoesRemover = document.querySelectorAll('.btn-remover-wishlist');
        
        botoesRemover.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const idProduto = this.dataset.idProduto;
                const card = document.getElementById('wishlist-item-' + idProduto);
                fetch('wishlist_acoes.php', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ acao: 'remover', id_produto: idProduto })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.remove();
                            if (document.querySelectorAll('.wishlist-card').length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                })
                .catch(error => console.error('Erro:', error));
            });
        });
    });
    </script>
</body>
</html>