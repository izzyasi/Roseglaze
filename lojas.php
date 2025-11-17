<?php

require 'conexao.php';

$espacos = [];
try {
    $sql = "SELECT * FROM espacos ORDER BY data_registro DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $espacos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar espaços: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Lojas</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body style="background-color: #f0f0f0;"> <?php require 'header.php'; ?>

    <main class="container-produtos">
        
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
                <p style="text-align: center; grid-column: 1 / -1;">
                    Nenhum espaço encontrado no momento.
                </p>
            <?php endif; ?>

        </div> </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>

</body>
</html>