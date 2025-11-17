<?php
require 'conexao.php';

$sql_colecoes = "SELECT * FROM colecoes WHERE ativa = 1 ORDER BY data_registro DESC";
try {
    $stmt_colecoes = $pdo->prepare($sql_colecoes);
    $stmt_colecoes->execute();
    $colecoes = $stmt_colecoes->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erro ao buscar coleções: " . $e->getMessage());
}

$sql_oculos = "SELECT id, modelo, cor, preco 
               FROM oculos 
               ORDER BY data_registro DESC
               LIMIT 4";
try {
    $stmt_oculos = $pdo->prepare($sql_oculos);
    $stmt_oculos->execute();
    $novidades = $stmt_oculos->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erro ao buscar óculos: " . $e->getMessage());
}

$sql_destaques = "SELECT id, modelo, cor, preco 
                  FROM oculos 
                  WHERE em_destaque = 1 
                  ORDER BY data_registro DESC
                  LIMIT 4";
try {
    $stmt_destaques = $pdo->prepare($sql_destaques);
    $stmt_destaques->execute();
    $nossa_selecao = $stmt_destaques->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erro ao buscar destaques: " . $e->getMessage());
}

$sql_espacos = "SELECT id, nome_local, endereco_curto, imagem_local 
                FROM espacos 
                ORDER BY data_registro DESC";
try {
    $stmt_espacos = $pdo->prepare($sql_espacos);
    $stmt_espacos->execute();
    $espacos = $stmt_espacos->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erro ao buscar espaços: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <?php require 'header.php'; ?>
    <section class="hero-section">
    
    <div class="hero-carousel" id="hero-carousel">
        
        <div class="hero-slide active" style="background-image: url('caminho/para/sua/imagem1.jpg');"></div>
        
        <div class="hero-slide" style="background-image: url('caminho/para/sua/imagem2.jpg');"></div>
        
        <div class="hero-slide" style="background-image: url('caminho/para/sua/imagem3.jpg');"></div>

        <button class="carousel-btn prev-btn" id="prev-slide">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button class="carousel-btn next-btn" id="next-slide">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
        <div class="carousel-indicators">
            <div class="indicator-bar active" data-slide="0"></div>
            <div class="indicator-bar" data-slide="1"></div>
            <div class="indicator-bar" data-slide="2"></div>
        </div>
    </div>

    <div class="hero-text-overlay">
        <div class="hero-text">
            <h1>ROSEGLAZE 2025</h1>
            
            <div class="hero-buttons">
                <a href="colecoes.php" class="btn-hero-outline">Ver Coleção</a>
            </div>
        </div>
    </div>

</section>

    <section class="container-produtos">
        <h2 class="secao-titulo">Novidades</h2>
        <div class="product-grid">
            <?php if (count($novidades) > 0): ?>
                <?php foreach ($novidades as $oculo): ?>
                    <div class="product-card">
                        <a href="produto.php?id=<?php echo htmlspecialchars($oculo['id']); ?>">
                            <div class="product-image-placeholder"></div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($oculo['modelo']); ?></h3>
                                <p>R$ <?php echo number_format($oculo['preco'], 2, ',', '.'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="container-produtos">
        <h2 class="secao-titulo">Nossa Seleção</h2>
        <div class="product-grid">
            <?php if (count($nossa_selecao) > 0): ?>
                <?php foreach ($nossa_selecao as $oculo): ?>
                    <div class="product-card">
                        <a href="produto.php?id=<?php echo htmlspecialchars($oculo['id']); ?>">
                            <div class="product-image-placeholder"></div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($oculo['modelo']); ?></h3>
                                <p>R$ <?php echo number_format($oculo['preco'], 2, ',', '.'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">Nenhum produto em destaque encontrado.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="container-espacos">
        <h2 class="secao-titulo">Espaços Roseglaze</h2>
        <div class="espacos-grid">
            <?php if (count($espacos) > 0): ?>
                <?php foreach ($espacos as $espaco): ?>
                    <a href="#" class="espaco-card" 
                       style="background-image: url('<?php echo htmlspecialchars($espaco['imagem_local']); ?>');">
                        <div class="espaco-overlay">
                            <div class="espaco-info">
                                <h3><?php echo htmlspecialchars($espaco['nome_local']); ?></h3>
                                <p><?php echo htmlspecialchars($espaco['endereco_curto']); ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">Nenhum espaço encontrado.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    <script src="js/main.js"></script>
    
</body>
</html>