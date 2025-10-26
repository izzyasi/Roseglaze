<?php
/*
 * Documentação: Página de Detalhe do Produto (produto.php)
 */
require 'conexao.php';

$produto = null;

 if (isset($_GET['id'])) {
    $id_produto = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    $sql = "SELECT * FROM oculos WHERE id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_produto]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (\PDOException $e) {
        die("Erro ao buscar o produto: " . $e->getMessage());
    }
}

    if (!$produto) {
    http_response_code(404);
    echo "<h1>404 - Produto não encontrado</h1>";
    echo "<p>O produto que está a procurar não existe.</p>";
    echo "<a href='index.php'>Voltar à Homepage</a>";
    exit; 
    }

// --- FIM BACK-END ---
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Roseglaze - <?php echo htmlspecialchars($produto['modelo']); ?></title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <header class="main-header">
        <nav class="nav-left">
            <a href="index.php">Sunglasses</a> 
            <a href="#">Coleções</a>
            <a href="#">PFW25</a>
        </nav>
        <div class="logo-container">
            <a href="index.php">Roseglaze</a>
        </div>
        <nav class="nav-right">
            <a href="#"><span class="material-icons-outlined">search</span></a>
            <a href="#"><span class="material-icons-outlined">person_outline</span></a>
            <a href="#"><span class="material-icons-outlined">shopping_bag_outline</span></a>
        </nav>
    </header>

    <main class="product-page-container">
        
        <div class="product-gallery">
            <div class="product-image-main-placeholder">
                </div>
        </div>

        <div class="product-details">
            
            <h1 class="product-title"><?php echo htmlspecialchars($produto['modelo']); ?></h1>
            
            <p class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            
            <button type="button" class="btn-add-to-bag">Add to Bag</button>
            
            <div class="product-info-accordion">
                <details open> <summary>Details</summary>
                    <div class="info-content">
                        <p><strong>Cor:</strong> <?php echo htmlspecialchars($produto['cor']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($produto['tipo_produto']); ?></p>
                        </div>
                </details>
            </div>

            <div class="product-info-accordion">
                <details>
                    <summary>Shipping & Returns</summary>
                    <div class="info-content">
                        <p>Informações de envio e devolução aqui...</p>
                    </div>
                </details>
            </div>

        </div> </main> <footer class="main-footer">
        <div class="footer-container">
            <nav class="footer-nav">
                <a href="#">Contact Us</a>
                <a href="#">Customer Service</a>
                <a href="#">Store Locator</a>
                <a href="#">Legal Notice</a>
            </nav>
            <div class="footer-copyright">
                <p>&copy; 2025 Roseglaze</p>
            </div>
        </div>
    </footer>

</body>
</html>