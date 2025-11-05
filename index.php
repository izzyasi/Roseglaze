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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>
    <?php require 'header.php'; ?>
    <section class="hero-section">
        <?php
        if (count($colecoes) > 0):
            $destaque = $colecoes[0]; 
        ?>
            <div class="hero-content" 
                 style="background-image: url('<?php echo htmlspecialchars($destaque['imagem_principal']); ?>');">
                <div class="hero-text-overlay">
                    <div class="hero-text">
                        <h1><?php echo htmlspecialchars($destaque['nome']); ?></h1>
                        <p><?php echo htmlspecialchars($destaque['descricao']); ?></p>
                        <div class="hero-buttons">
                            <a href="colecao.php?id=<?php echo htmlspecialchars($destaque['id']); ?>" class="btn btn-primary">Compre Agora</a>
                            <a href="#" class="btn btn-secondary">Veja a Campanha</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="hero-content-vazio">
                <h2>Bem-vindo à Roseglaze</h2>
            </div>
        <?php endif; ?>
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
    <script src="js/main.js"></script>
    
</body>
</html>