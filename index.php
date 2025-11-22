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

$sql_lojas = "SELECT id, nome, endereco, imagem 
                FROM lojas 
                ORDER BY id DESC";
try {
    $stmt_lojas = $pdo->prepare($sql_lojas);
    $stmt_lojas->execute();
    $lojas = $stmt_lojas->fetchAll(PDO::FETCH_ASSOC);
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
        
        <div class="hero-slide active" 
             style="background-image: url('imagens/colecao_roseglaze25.jpg');"
             data-title="ROSEGLAZE 2025"
             data-button="Ver Coleção"
             data-link="catalogo.php?colecao_id=4">
        </div>
        
        <div class="hero-slide" 
             style="background-image: url('imagens/colecao_cozy.jpg');"
             data-title="COZY COLLECTION"
             data-button="Ver Coleção"
             data-link="catalogo.php?colecao_id=5">
        </div>
        
        <div class="hero-slide" 
             style="background-image: url('imagens/colecao_blossom.jpg');"
             data-title="BLOSSOM COLLECTION"
             data-button="Ver Coleção"
             data-link="catalogo.php?colecao_id=6"
             data-header-color="white">
        </div>

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

    <section class="novidades-section">
    
    <div class="novidades-header">
        <h2>MAIS RECENTE: NOVIDADES ROSEGLAZE</h2>
        <a href="catalogo.php" class="link-more">MAIS</a>
    </div>
    <div class="carousel-wrapper">
        
        <button class="scroll-arrow prev-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
    <div class="novidades-scroll-container">
        
        <?php
        $sql_novidades = "SELECT * FROM oculos ORDER BY id ASC LIMIT 6";
        $stmt_novidades = $pdo->query($sql_novidades);
        $novidades = $stmt_novidades->fetchAll(PDO::FETCH_ASSOC);

        foreach ($novidades as $prod): 
        ?>
            <div class="novidades-card">
                <a href="produto.php?id=<?php echo $prod['id']; ?>" class="novidades-img-link">
                    <?php 
                        $img_url = !empty($prod['imagem_destaque']) 
                                    ? 'imagens/' . $prod['imagem_destaque'] 
                                    : (!empty($prod['imagem']) ? 'imagens/' . $prod['imagem'] : ''); 
                    ?>

                    <div class="novidades-img" 
                         style="background-image: url('<?php echo $img_url; ?>'); 
                                background-color: #f0f0f0;">
                    </div>
                </a>

                <div class="novidades-info">
                    <h3><?php echo htmlspecialchars($prod['modelo']); ?></h3>
                    <p>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>
                    
                </div>
                <button class="scroll-arrow next-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
            </div>
        <?php endforeach; ?>

    </div>
</section>

    <section class="novidades-section">
    
    <div class="novidades-header">
        <h2>FAVORITOS: SELEÇÃO ROSEGLAZE</h2>
        <a href="catalogo.php" class="link-more">MAIS</a>
    </div>

    <div class="carousel-wrapper">
        
        <button class="scroll-arrow prev-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>

    <div class="novidades-scroll-container">
        
        <?php
        $sql_selecao = "SELECT * FROM oculos WHERE em_destaque = 1 ORDER BY id DESC LIMIT 6";
        
        try {
            $stmt_selecao = $pdo->query($sql_selecao);
            $selecao = $stmt_selecao->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $selecao = []; 
        }

        if (count($selecao) > 0):
            foreach ($selecao as $prod): 
        ?>
            <div class="novidades-card">
                <a href="produto.php?id=<?php echo $prod['id']; ?>" class="novidades-img-link">
                    
                    <?php 
                        $img_url = !empty($prod['imagem']) ? 'imagens/' . $prod['imagem'] : ''; 
                    ?>
                    <div class="novidades-img" 
                         style="background-image: url('<?php echo $img_url; ?>'); 
                                background-color: #f0f0f0;">
                    </div>
                </a>

                <div class="novidades-info">
                        <h3><?php echo htmlspecialchars($prod['modelo']); ?></h3>
                        <p>R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <p style="color: #777; padding: 20px;">Ainda não selecionámos os destaques.</p>
            <?php endif; ?>

        </div>

        <button class="scroll-arrow next-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    
    </div> </section>

    <section class="container-espacos">
        <div class="espacos-header">
        <h2>Espaços Roseglaze</h2>
    </div>

    <div class="espacos-grid">
        
        <a href="lojas.php" class="espaco-card">
            <div class="espaco-img" style="background-image: url('imagens/espaco_sp.jpg');"></div>
            <div class="espaco-overlay"></div>
            <div class="espaco-info">
                <h3>SÃO PAULO JARDINS</h3>
                <p>São Paulo, Brasil</p>
            </div>
        </a>

        <a href="lojas.php" class="espaco-card">
            <div class="espaco-img" style="background-image: url('imagens/espaco_london.jpg');"></div>
            <div class="espaco-overlay"></div>
            <div class="espaco-info">
                <h3>LONDON MAYFAIR</h3>
                <p>London, United Kingdom</p>
            </div>
        </a>

        <a href="lojas.php" class="espaco-card">
            <div class="espaco-img" style="background-image: url('imagens/espaco_paris.jpg');"></div>
            <div class="espaco-overlay"></div>
            <div class="espaco-info">
                <h3>PARIS MARAIS</h3>
                <p>Paris, France</p>
            </div>
        </a>
    </div>
</section>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    <script src="js/main.js"></script>
    
</body>
</html>