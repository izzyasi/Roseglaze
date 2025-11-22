<?php
require 'conexao.php';

$produto = null;
$ids_wishlist = [];

if (isset($_GET['id'])) {
    $id_produto = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    try {
        $sql = "SELECT * FROM oculos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_produto]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($_SESSION['usuario_id'])) {
            $stmt_w = $pdo->prepare("SELECT produto_id FROM wishlist WHERE usuario_id = ?");
            $stmt_w->execute([$_SESSION['usuario_id']]);
            $ids_wishlist = $stmt_w->fetchAll(PDO::FETCH_COLUMN);
        }

    } catch (PDOException $e) {
        die("Erro: " . $e->getMessage());
    }
}

if (!$produto) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - <?php echo htmlspecialchars($produto['modelo']); ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="product-page-container">
        
        <div class="product-gallery">
            <?php
                $img1 = !empty($produto['imagem']) ? 'imagens/' . $produto['imagem'] : '';
                $img2 = !empty($produto['imagem_2']) ? 'imagens/' . $produto['imagem_2'] : '';
                $img3 = !empty($produto['imagem_3']) ? 'imagens/' . $produto['imagem_3'] : '';
            ?>

            <div class="product-image-slot" 
                 style="<?php echo $img1 ? "background-image: url('$img1');" : "background-color: #f6f6f6;"; ?>">
            </div>
            
            <?php if ($img2): ?>
                <div class="product-image-slot" 
                     style="background-image: url('<?php echo $img2; ?>');">
                </div>
            <?php endif; ?>

            <?php if ($img3): ?>
                <div class="product-image-slot" 
                     style="background-image: url('<?php echo $img3; ?>');">
                </div>
            <?php endif; ?>
        </div>

        <div class="product-details">
            
            <div class="product-header-row">
                <h1 class="product-title"><?php echo htmlspecialchars($produto['modelo']); ?></h1>

                <?php 
                    $esta_na_lista = in_array($produto['id'], $ids_wishlist);
                    $classe_vazia = $esta_na_lista ? 'escondido' : '';
                    $classe_cheia = $esta_na_lista ? '' : 'escondido';
                    $acao_inicial = $esta_na_lista ? 'remover' : 'adicionar';
                ?>
                <button class="btn-wishlist-catalog btn-wishlist-busca" 
                        data-id-produto="<?php echo $produto['id']; ?>" 
                        data-acao-wishlist="<?php echo $acao_inicial; ?>"
                        style="background:none; border:none; cursor:pointer;">
                    
                    <svg class="wishlist-icon-empty <?php echo $classe_vazia; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <svg class="wishlist-icon-filled <?php echo $classe_cheia; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </button>
            </div>
            
            <p class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

            <button type="button" class="btn-add-to-bag" 
                    id="btn-adicionar-sacola" 
                    data-produto-id="<?php echo htmlspecialchars($produto['id']); ?>">
                ADD TO BAG
            </button>
            
            <div class="product-info-accordion">
                <details open> 
                    <summary><span>DETALHES</span><span>+</span></summary>
                    <div class="info-content">
                        <p><strong>Cor:</strong> <?php echo htmlspecialchars($produto['cor']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($produto['tipo_produto']); ?></p>
                    </div>
                </details>
            </div>

            <div class="product-info-accordion">
                <details>
                    <summary><span>FRETE & DEVOLUÇÕES GRATIS</span><span>+</span></summary>
                    <div class="info-content">
                        <p>Envio gratuito para todo o Brasil.</p>
                        <p>Devoluções gratuitas em até 30 dias.</p>
                    </div>
                </details>
            </div>

        </div> 
    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    
    <script src="js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addBtn = document.getElementById('btn-adicionar-sacola');
        if (addBtn) {
            const originalText = addBtn.textContent;
            addBtn.addEventListener('click', function() {
                const id = this.dataset.produtoId;
                this.textContent = 'ADICIONANDO...';
                this.disabled = true;

                fetch('sacola_acoes.php', {
                    method: 'POST', headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ acao: 'adicionar', id: id })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.sucesso) {
                        this.textContent = 'ADICIONADO!';
                        const contador = document.getElementById('contador-sacola');
                        if(contador && data.novo_total) {
                            contador.textContent = data.novo_total;
                            contador.classList.remove('escondido');
                        }
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.disabled = false;
                        }, 2000);
                    } else {
                        alert(data.mensagem);
                        this.textContent = originalText;
                        this.disabled = false;
                    }
                });
            });
        }
    });
    </script>

</body>
</html>