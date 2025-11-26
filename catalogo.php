<?php
require 'conexao.php';

$tipo_produto = $_GET['tipo'] ?? null;
$filtro = $_GET['filtro'] ?? null;
$ordem = $_GET['ordem'] ?? null;
$colecao_id = $_GET['colecao_id'] ?? null;

$titulo_pagina = "CATÁLOGO"; 
$descricao_pagina = "Descubra a nossa gama completa de óculos.";
$imagem_banner = null; 

if ($tipo_produto === 'Sol') {
    $titulo_pagina = "ÓCULOS DE SOL";
    $descricao_pagina = "Explore nossa coleção de óculos de sol.";
} elseif ($tipo_produto === 'Sem Grau') {
    $titulo_pagina = "ÓCULOS DE GRAU";
    $descricao_pagina = "Explore nossa coleção de óculos óticos.";
} elseif ($colecao_id) {
    try {
        $stmt_col = $pdo->prepare("SELECT nome, descricao, imagem_principal FROM colecoes WHERE id = ?");
        $stmt_col->execute([$colecao_id]);
        $dados_colecao = $stmt_col->fetch(PDO::FETCH_ASSOC);
        
        if ($dados_colecao) {
            $titulo_pagina = mb_strtoupper($dados_colecao['nome']);
            $descricao_pagina = $dados_colecao['descricao'];
            $imagem_banner = $dados_colecao['imagem_principal']; 
        }
    } catch (Exception $e) {}
}

$produtos = [];
try {
    $sql = "SELECT * FROM oculos WHERE 1=1"; 
    $params = [];

    if ($tipo_produto) { $sql .= " AND tipo_produto = ?"; $params[] = $tipo_produto; }
    if ($filtro === 'destaques') { $sql .= " AND em_destaque = 1"; }
    if ($colecao_id) { $sql .= " AND colecao_id = ?"; $params[] = $colecao_id; }

    $sql .= " ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $ids_wishlist = [];
    if (isset($_SESSION['usuario_id'])) {
        $stmt_w = $pdo->prepare("SELECT produto_id FROM wishlist WHERE usuario_id = ?");
        $stmt_w->execute([$_SESSION['usuario_id']]);
        $ids_wishlist = $stmt_w->fetchAll(PDO::FETCH_COLUMN);
    }
} catch (PDOException $e) { die("Erro: " . $e->getMessage()); }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_pagina); ?> | ROSEGLAZE</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body> 
    
    <?php require 'header.php'; ?>

    <?php if ($imagem_banner): ?>

        <section class="collection-hero-single" style="background-image: url('<?php echo $imagem_banner; ?>');">
            <div class="collection-hero-overlay">
                <div class="collection-hero-text">
                    <h1><?php echo htmlspecialchars($titulo_pagina); ?></h1>
                    <p><?php echo htmlspecialchars($descricao_pagina); ?></p>
                </div>
            </div>
        </section>

        <main class="container-produtos" style="padding-top: 60px !important;">

    <?php else: ?>

        <main class="container-produtos">
            <div class="catalog-header">
                <h1><?php echo htmlspecialchars($titulo_pagina); ?></h1>
                <p class="catalog-desc"><?php echo htmlspecialchars($descricao_pagina); ?></p>
            </div>

    <?php endif; ?>
        
        <div class="product-grid">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $oculos): ?>
                    <div class="product-card">
                        <a href="produto.php?id=<?php echo htmlspecialchars($oculos['id']); ?>" class="product-link">
                             <?php $img_url = !empty($oculos['imagem']) ? 'imagens/' . $oculos['imagem'] : ''; ?>
                            <div class="product-img" style="background-image: url('<?php echo $img_url; ?>'); background-color: #f7f7f7;"></div>
                        </a>
                        <div class="product-info-row">
                            <div class="info-left">
                                <h3><?php echo htmlspecialchars($oculos['modelo']); ?></h3>
                                <p>R$ <?php echo number_format($oculos['preco'], 2, ',', '.'); ?></p>
                            </div>
                            <?php 
                                $esta_na_lista = in_array($oculos['id'], $ids_wishlist);
                                $classe_vazia = $esta_na_lista ? 'escondido' : '';
                                $classe_cheia = $esta_na_lista ? '' : 'escondido';
                                $acao_inicial = $esta_na_lista ? 'remover' : 'adicionar';
                            ?>
                            <button class="btn-wishlist-catalog btn-wishlist-busca" 
                                    data-id-produto="<?php echo $oculos['id']; ?>" 
                                    data-acao-wishlist="<?php echo $acao_inicial; ?>">
                                <svg class="wishlist-icon-empty <?php echo $classe_vazia; ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <svg class="wishlist-icon-filled <?php echo $classe_cheia; ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1; color: #777; margin-top: 50px;">Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div> 
    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    
    <script src="js/main.js"></script>

</body>
</html>