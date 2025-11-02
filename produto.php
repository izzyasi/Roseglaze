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

    <?php require 'header.php'; ?>

    <main class="product-page-container">
        
        <div class="product-gallery">
            <div class="product-image-main-placeholder">
                </div>
        </div>

        <div class="product-details">
            
            <h1 class="product-title"><?php echo htmlspecialchars($produto['modelo']); ?></h1>
            
            <p class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            
            <button type="button" class="btn-add-to-bag" data-id-produto="<?php echo htmlspecialchars($produto['id']); ?>">Add to Bag</button>
            
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

        </div> </main>

    <script>
     document.addEventListener('DOMContentLoaded', function() {
        
        const addBtn = document.querySelector('.btn-add-to-bag');
        
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                
                const idProduto = this.dataset.idProduto;
                
                fetch('sacola_acoes.php', {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json' 
                    },
                    body: JSON.stringify({
                        acao: 'adicionar',
                        id: idProduto
                    })
                })
                .then(response => response.json()) 
                .then(data => {
                    alert(data.mensagem); 
                    if (data.sucesso) {
                        this.textContent = 'Adicionado!';
                        this.disabled = true; 
                    }
                })
                .catch(error => {
                    console.error('Erro no fetch:', error);
                    alert('Ocorreu um erro ao conectar. Tente novamente.');
                });
            });
        }
    });
    </script>
    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>