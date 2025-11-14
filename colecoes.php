<?php

require 'conexao.php';

$colecoes = [];
try {
    $sql = "SELECT * FROM colecoes WHERE ativa = 1 ORDER BY data_registro DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $colecoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar coleções: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Coleções</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body style="background-color: #f0f0f0;"> <?php require 'header.php'; ?>

    <main class="container-produtos">
        
        <?php if (count($colecoes) > 0): ?>
            <?php foreach ($colecoes as $colecao): ?>

                <section class="colecao-banner" style="margin-bottom: 30px;">
                    <div class="colecao-banner-content" 
                         style="background-image: url('<?php echo htmlspecialchars($colecao['imagem_principal']); ?>');">
                        
                        <div class="colecao-banner-overlay">
                            <div class="colecao-banner-text">
                                <h1><?php echo htmlspecialchars($colecao['nome']); ?></h1>
                                <p><?php echo htmlspecialchars($colecao['descricao']); ?></p>
                                
                                <div class="colecao-banner-buttons">
                                    <a href="colecao.php?id=<?php echo htmlspecialchars($colecao['id']); ?>" class="btn-banner-link">
                                        Ver Coleção <span class="material-icons-outlined">north_east</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">
                Nenhuma coleção encontrada no momento.
            </p>
        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>

</body>
</html>